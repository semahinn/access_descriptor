<?php

namespace Snr\AccessDescriptor\AccessDescriptor;

use Snr\AccessDescriptor\HandlerInterface;

interface AccessDescriptorInterface
{
  /**
   * Доступные операции
   *
   * @return array
   */
  public function getOperations();

  /**
   * Доступные типы субъектов доступа
   *
   * @return array
   */
  public function getSubjects();

  /**
   * Настройки, с которыми работает этот экземпляр AccessDescriptorInterface
   *
   * @return array
   */
  public function getSettings();

  /**
   * @return HandlerInterface
   */
  public function getHandler();

  /**
   * Проверяет, предоставлен ли доступ определенному пользователю
   *
   * @param $account
   *
   * @param string $operation
   *  Операция, к которой проверяется доступ ('all' - ко всем операциям)
   *
   * @return bool
   *  Разрешен/Запрещен
   */
  public function access($account, string $operation = 'all');

  /**
   * Предоставить доступ субъекту к определенной операции
   *
   * @param mixed $ids
   *  Идентификатор субъекта или массив идентификаторов субъектов
   *
   * @param string $subject
   *  Тип субъекта
   *
   * @param string $operation
   *  Операция, к которой предоставляется доступ ('all' - ко всем операциям)
   */
  public function addAccess($ids, string $subject, string $operation = 'all');

  /**
   * Отнять доступ субъекта к определенной операции
   *
   * @param $ids
   *  Идентификатор субъекта или массив идентификаторов субъектов
   *
   * @param string $subject
   *  Тип субъекта
   *
   * @param string $operation
   *  Операция, к которой предоставляется доступ ('all' - ко всем операциям)
   */
  public function removeAccess($ids, string $subject, string $operation = 'all');

  /**
   * Получить идентификаторы субъектов, которым предоставлен доступ
   *
   * @param string $subject
   *  Тип субъекта
   *
   * @param string $operation
   *  Операция, к которой предоставляется доступ ('all' - ко всем операциям)
   *
   * @return array|mixed
   *  Идентификаторы субъектов, которым предоставлен доступ
   */
  public function getIdsWithAccess(string $subject, string $operation = 'all');

  /**
   * Предоставлен ли доступ этому субъекту
   *
   * @param $id
   *  Идентификатор субъекта
   *
   * @param string$subject
   *  Тип субъекта
   *
   * @param string $operation
   *  Операция, к которой предоставляется доступ ('all' - ко всем операциям)
   *
   * @return bool
   *  true, если доступ предоставлен
   */
  public function hasAccess($id, string $subject, string $operation = 'all');

  /**
   * @return string
   *  Возвращает значение (строку), описывающую доступ
   */
  public function getAccessString();

  /**
   * Массив, ключами которого являются идентификаторы операций ('all', 'view', 'update', 'delete' и др.),
   *  а значение каждого ключа - массив, ключами которого являются идентификаторы
   *  типов субъектов доступа ('roles', 'users' и др.),
   *  каждый из которых содержит массив идентификаторов субъектов, которым предоставляется доступ.
   *
   * Набор операций и субъектов не фиксирован, это сделано для того,
   *  чтобы можно было настроить доступ для собственной уникальной модели.
   *
   * Например:
   * @code
   *  $array = [
   *    'view' => [
   *      'users' => [
   *        '0' => '23',
   *        '1' => '48',
   *        ...,
   *        '5' => '3',
   *      ],
   *      'roles' => [],
   *    ],
   *    'edit' => [],
   *  ];
   * @endcode
   *
   * @return  array
   */
  public function toArray();
}
