<?php

namespace Snr\AccessDescriptor\AccessDescriptor;

use Snr\AccessDescriptor\HandlerInterface;

/**
 * Class AccessDescriptor
 *
 * Основные понятия:
 *
 * Объект доступа - это информационная единица, доступ к
 * которой регламентируется строкой раграничения доступа (результат работы метода getAccessDescriptorString).
 *
 * Субъект доступа - лицо или процесс,
 * действия которого регламентируются правилами разграничения доступа
 * Примеры субъектов доступа: roles - Роли, users - Пользователи.
 *
 * Тип субъекта доступа - группа, в которую субъекты доступа объединяются по смыслу.
 *
 * Операция доступа - действие над объектом,
 * к которому разграничивается доступ для субъекта
 * Примеры операций: view - Просмотр, edit - Редактирование, delete - Удаление.
 *
 * Основное применение:
 * Формирует строковое значение для разграничения доступа к информационной единице.
 *
 * Дополнительное применение:
 * Можно использовать в любой модели разграничения доступа, где существует необходимость описать разграничение
 * доступа каких - либо типов объектов ко всевозможным операциям с ними.
 * Лучшим решением будет создать класс, наследующий AccessDescriptor.
 */
abstract class AccessDescriptor implements AccessDescriptorInterface {

  /**
   * @var array
   *
   * @see AccessDescriptorInterface::toArray()
   */
  protected $value = array();

  /**
   * @var array
   *
   * @see AccessDescriptorInterface::getSubjects()
   */
  protected $subjects = array();

  /**
   * @var array
   *
   * @see AccessDescriptorInterface::getOperations()
   */
  protected $operations = array();

  /**
   * @var HandlerInterface
   */
  protected $handler;

  /**
   * @var bool
   */
  protected $throwIfSdStringDiffWithOperations;

  /**
   * Создаёт новый экземпляр AccessDescriptor
   *
   * @param array $subjects
   *   Объекты, которым возможно будет предоставляться доступ
   *
   * @param mixed $access_value
   *   Информация о доступе
   *
   * @param array $operations
   *   Операции, по которым разграничивается доступ
   *
   * @param bool $throw_if_sd_string_diff_with_operations
   *   true, чтобы возникало исключение, если структура $sd_xml
   *    не совпадает с оперциями $operations
   *
   * @throws \Exception
   */
  public function __construct(array $subjects, $access_value = null, array $operations = array('default'),
                              bool  $throw_if_sd_string_diff_with_operations = false) {
    $this->subjects = $subjects;
    $this->operations = $operations;
    $this->throwIfSdStringDiffWithOperations = $throw_if_sd_string_diff_with_operations;

    // $sd_value - строка в формате xml, необходимо проверить ее корректность
    if ($access_value != null) $this->value = $this->decode($access_value);
  }

  /**
   * @param mixed $access_value
   * @return array
   * @throws \Exception
   */
  protected function decode($access_value) {

    $decoded = json_decode($access_value, true);

    $interface = AccessDescriptorInterface::class;
    // Ключами первого уровня должны быть операции
    if (!is_array($decoded) || empty($decoded))
      throw new \Exception(
        'В массиве, описывающем доступ, ключами первого уровня должны быть доступные операции ' .
        "(см. {$interface}::getOperations())");

    if (in_array('default', $this->operations)) {
      if (count($decoded) != 1)
        throw new \Exception(
          "Ключ операции 'default' используется для хранения значений ВСЕХ операций. " .
            "Другие ключи операций в таком случае недопустимы");
    }

    $decoded_only_default = false;
    if (count($decoded) == 1 && key($decoded) == 'default') {
      if ($this->throwIfSdStringDiffWithOperations && array_diff($this->operations, ['default']))
        throw new \Exception(
          "Формат строки (xml) для описания экземпляра AccessDescriptorInterface не " .
          "совпадает с допустимыми операциями (см. {$interface}::getOperations())");
      $decoded_only_default = true;
    }

    // Полученный массив содержит ключи типа (key_...), необходимо избавиться от них, все ключи должны быть целыми числами (0, 1, 2...)
    $result = [];
    foreach ($this->operations as $operation) {
      foreach ($this->subjects as $subject) {
        $row = null;
        if ($decoded_only_default)
          $row = (!empty($decoded['default'][$subject]) ? $decoded['default'][$subject] : null);
        elseif (isset($decoded[$operation][$subject]) ||
          ($operation == 'default' && isset($decoded[$operation][$subject]))
        ) {
          $row = $decoded[$operation][$subject];
        }

        if (is_array($row) && !empty($row)) {
          $values = [];
          foreach ($row as $key => $value) {
            $values[$key] = $value;
            if (!is_numeric($key)) {
              unset($values[$key]);
              $values[] = $value;
            }
          }
          $result[$operation][$subject] = $values;
        }
      }
    }

    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function getOperations() {
    return $this->operations;
  }

  /**
   * {@inheritdoc}
   */
  public function getSubjects() {
    return $this->subjects;
  }

  /**
   * {@inheritdoc}
   */
  public function getSettings() {
    return [
      'subjects' => $this->subjects,
      'operations' => $this->operations
    ];
  }

  /**
   * {@inheritdoc}
   */
  public abstract function getHandler();

  /**
   * {@inheritdoc}
   */
  public function access($account, $operation = 'all') {
    $handler = $this->getHandler();
    return $handler->access($this, $account, $operation);
  }

  /**
   * {@inheritdoc}
   */
  public function addAccess($ids, string $subject, string $operation = 'all') {
    if (in_array($subject, $this->subjects) && (in_array($operation, $this->operations) || $operation == 'all')) {
      if ($operation == 'all') {
        foreach ($this->operations as $operation) {
          $this->addAccessToOperation($ids, $subject, $operation);
        }
      }
      else {
        $this->addAccessToOperation($ids, $subject, $operation);
      }
    }
  }

  private function addAccessToOperation($ids, $subject, $operation) {
    if (in_array($subject, $this->subjects) && (in_array($operation, $this->operations))) {
      if (is_array($ids)) {
        foreach ($ids as $id) {
          if (isset($this->value[$operation][$subject])) {
            // Если идентификатор уже в списке, то не ничего не добавляем
            if (!in_array($id, $this->value[$operation][$subject])) {
              $this->value[$operation][$subject][] = $id;
            }
          }
          else {
            $this->value[$operation][$subject][] = $id;
          }
        }
      }
      else {
        if (isset($this->value[$operation][$subject])) {
          // Если идентификатор уже в списке, то не ничего не добавляем
          if (!in_array($ids, $this->value[$operation][$subject])) {
            $this->value[$operation][$subject][] = $ids;
          }
        }
        else {
          $this->value[$operation][$subject][] = $ids;
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function removeAccess($ids, string $subject, string $operation = 'all') {
    if (in_array($subject, $this->subjects) && (in_array($operation, $this->operations) || $operation == 'all')) {
      if ($operation == 'all') {
        foreach ($this->operations as $operation) {
          $this->removeAccessToOperation($ids, $subject, $operation);
        }
      }
      else {
        $this->removeAccessToOperation($ids, $subject, $operation);
      }
    }
  }

  private function removeAccessToOperation($ids, $subject, $operation) {
    if (in_array($subject, $this->subjects) && (in_array($operation, $this->operations))) {
      if (is_array($ids)) {
        foreach ($ids as $id) {
          if (isset($this->value[$operation][$subject])) {
            // Если идентификатор в списке, то удаляем его
            if (in_array($id, $this->value[$operation][$subject])) {
              foreach ($this->value[$operation][$subject] as $key => $_id) {
                if ($_id == $id) unset($this->value[$operation][$subject][$key]);
              }
            }
          }
        }
      }
      else {
        if (isset($this->value[$operation][$subject])) {
          // Если идентификатор в списке, то удаляем его
          if (in_array($ids, $this->value[$operation][$subject])) {
            foreach ($this->value[$operation][$subject] as $key => $_id) {
              if ($_id == $ids) unset($this->value[$operation][$subject][$key]);
            }
          }
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getIdsWithAccess(string $subject, string $operation = 'all') {
    $ids = array();
    if (in_array($subject, $this->subjects) && (in_array($operation, $this->operations) || $operation == 'all')) {
      if ($operation == 'all') {
        foreach ($this->operations as $_operation) {
          if (isset($this->value[$_operation][$subject])) {
            foreach ($this->value[$_operation][$subject] as $id) {
              if (!in_array($id, $ids)) $ids[] = $id;
            }
          }
        }
      }
      else {
        if (isset($this->value[$operation][$subject])) {
          foreach ($this->value[$operation][$subject] as $id) {
            if (!in_array($id, $ids)) $ids[] = $id;
          }
        }
      }
    }

    return $ids;
  }

  /**
   * {@inheritdoc}
   */
  public function hasAccess($id, string $subject, string $operation = 'all') {
    if (in_array($subject, $this->subjects) && (in_array($operation, $this->operations) || $operation == 'all')) {
      $result = true;
      if ($operation == 'all') {
        foreach ($this->operations as $operation) {
          $result &= $this->hasAccessToOperation($id, $subject, $operation);
        }
      }
      else {
        $result = $this->hasAccessToOperation($id, $subject, $operation);
      }
      return $result;
    }
    return false;
  }

  private function hasAccessToOperation($id, $subject, $operation) {
    if (in_array($subject, $this->subjects) && (in_array($operation, $this->operations))) {
      if (isset($this->value[$operation][$subject])) {
        if (in_array($id, $this->value[$operation][$subject])) return true;
      }
    }
    return false;
  }

  /**
   * {@inheritdoc}
   */
  public function getAccessString() {
    if (empty($this->value)) return null;
    return json_encode($this->value);
  }

  /**
   * {@inheritdoc}
   */
  public function toArray() {
    return $this->value;
  }

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    return $this->getAccessString();
  }

}
