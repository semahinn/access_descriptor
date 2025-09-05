<?php

namespace Snr\AccessDescriptor\Tests\AccessDescriptor;

use Snr\AccessDescriptor\AccessDescriptor\AccessDescriptorInterface;

interface UsersInterface extends AccessDescriptorInterface
{
  /**
   * Возвращает UUID всех пользователей, которым предоставлен доступ к одной или
   *   всем операциям над экземпляром сущности
   *
   * @param string $operation
   *   Операция, к которой предоставлен доступ ('all', 'view', 'update', 'delete')
   *
   * @return array|mixed
   *   UUID пользователей, которым предоставлен доступ
   */
  public function getUsers(string $operation = 'all');

  /**
   * Добавить пользователя (пользователей), которым будет предоставлен доступ
   *
   * @param mixed $uuids
   *   UUID пользователя или массив UUID пользователей
   *
   * @param string $operation
   *   Операция, к которой предоставляется доступ ('all', 'view', 'update', 'delete')
   */
  public function addUsers($uuids, string $operation = 'all');

  /**
   * Удаляет пользователя из перечня тех, которым предоставлен доступ
   *
   * @param mixed $uuids
   *   UUID пользователя или массив UUID пользователей
   *
   * @param string $operation
   *   Операция, к которой предоставляется доступ ('all', 'view', 'update', 'delete')
   */
  public function removeUsers($uuids, string $operation = 'all');

  /**
   * Предоставлен ли пользователю доступ
   *
   * @param $uuid
   *   UUID пользователя
   *
   * @param string $operation
   *   Операция, к которой предоставляется доступ ('all' - ко всем операциям)
   *
   * @return bool
   *   true, если доступ предоставлен
   */
  public function hasUserAccess($uuid, string $operation = 'all');
}
