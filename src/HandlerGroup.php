<?php

namespace Snr\AccessDescriptor;

use Snr\AccessDescriptor\AccessDescriptor\AccessDescriptorInterface;
use Snr\AccessDescriptor\Manager\HandlerPluginManager;
use Snr\AccessDescriptor\Manager\HandlerPluginManagerInterface;

/**
 * Class HandlerGroup
 *
 * Стандартная ситуация, когда экземпляр SecurityDescriptorInterface
 * настроен на несколько типов субъектов доступа ('users', 'roles' и т.д.),
 * когда у нас есть несколько ОБРАБОТЧИКОВ (SecurityDescriptorHandlerInterface)
 * под каждый тип субъекта доступа
 *
 * Тогда возникает задача свзять логику проверки из Roles и Users, ведь
 * наверняка итоговый результат доступа зависит как от проверок в Roles,
 * так и от проверок в Users
 *
 * Этот класс в своем методе access позволяет учесть результаты
 * доступа каждого из ОБРАБОТЧИКОВ, найденных для определенного экземпляра
 * security descriptor и объединить их, например, при помощи оператора ИЛИ
 *
 * Если требуется описать более сложную логику определения доступа одновременно
 * для нескольких субъектов доступа, то нужно создавать
 * особые обработчики, метод availableSubjects которых возвращает несколько субъектов доступа.
 * В таком обработчике в его методе access можно описать какую угодно логику,
 * возможно более сложную, чем простое ИЛИ, которое предлагает этот класс
 */
class HandlerGroup implements HandlerGroupInterface
{
  /**
   * @var HandlerInterface[]
   */
  protected $handlers = [];

  /**
   * HandlerGroup constructor.
   *
   * @param array $subjects
   *
   * @throws
   */
  public function __construct(array $subjects)
  {
    $candidates = [];
    /**
     * @var $plugin_manager HandlerPluginManagerInterface
     */
    $plugin_manager = ServicesFactory::getInstance()->getService(HandlerPluginManager::class);
    $plugin_definitions = $plugin_manager->getDefinitions();
    foreach ($plugin_definitions as $definition) {
      $definition_subjects = $definition['subjects'];
      $matches = array_intersect($subjects, $definition_subjects);
      ksort($matches);
      if (count($matches) == count($definition_subjects)) {
        foreach ($matches as $match) {
          if (isset($candidates[$match])) {
            if (count($matches) >= $candidates[$match]['subjects_count']) {
              $candidates[$match]['definition'] = $definition;
              $candidates[$match]['subjects_count'] = count($matches);
            }
          }
          else {
            $candidates[$match]['definition'] = $definition;
            $candidates[$match]['subjects_count'] = count($matches);
          }
        }
      }
    }

    $results = [];
    foreach ($subjects as $subject) {
      if (!isset($candidates[$subject])) {
        throw new \Exception(
          "Для типа субъекта '$subject' не удалось найти подходящего обработчика (см. SecurityDescriptorHandlerInterface)");
      }

      $plugin_id = $candidates[$subject]['definition']['id'];
      $results[$plugin_id] = $plugin_manager->createInstance($plugin_id, []);
    }

    $this->handlers = $results;
  }

  /**
   * {@inheritdoc}
   */
  public function getHandlers()
  {
    return $this->handlers;
  }

  /**
   * {@inheritdoc}
   */
  public function access(AccessDescriptorInterface $security_descriptor, $account, string $operation = 'all')
  {
    $result = FALSE;
    foreach ($this->handlers as $handler) {
      if ($result)  break;
      $result = $handler->access($security_descriptor, $account, $operation);
    }
    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function availableSubjects()
  {
    $subjects = [];
    foreach ($this->handlers as $handler) {
      $subjects = array_merge($handler->availableSubjects(), $subjects);
    }
    return $subjects;
  }

}
