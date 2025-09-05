<?php

namespace Snr\AccessDescriptor\Tests\AccessDescriptor;

use Snr\AccessDescriptor\AccessDescriptor\AccessDescriptorInterface;

interface RolesInterface extends AccessDescriptorInterface
{
  /**
   * Возвращает идентификаторы всех ролей, которым предоставлен доступ к одной или
   *   всем операциям над экземпляром сущности
   *
   * @param string $operation
   *   Операция, к которой предоставлен доступ ('all', 'view', 'update', 'delete')
   *
   * @return array|mixed
   *   Идентификаторы ролей, которым предоставлен доступ
   */
  public function getRoles(string $operation = 'all');

  /**
   * Добавить роль (роли), которым будет предоставлен доступ
   *
   * @param mixed $ids
   *   Идентификатор роли или массив идентификаторов ролей
   *
   * @param string $operation
   *   Операция, к которой предоставляется доступ ('all', 'view', 'update', 'delete')
   */
  public function addRoles($ids, string $operation = 'all');

  /**
   * Удаляет роль из перечня тех, которым предоставлен доступ
   *
   * @param mixed $ids
   *   Идентификатор роли или массив идентификаторов ролей
   *
   * @param string $operation
   *   Операция, к которой предоставляется доступ ('all', 'view', 'update', 'delete')
   */
  public function removeRoles($ids, string $operation = 'all');

  /**
   * Предоставлен ли роли доступ
   *
   * @param $id
   *   Идентификатор роли
   *
   * @param string $operation
   *   Операция, к которой предоставляется доступ ('all' - ко всем операциям)
   *
   * @return bool
   *   true, если доступ предоставлен
   */
  public function hasRoleAccess($id, string $operation = 'all');
}
