<?php

namespace Snr\AccessDescriptor\AccessDescriptor;

/**
 * Class UsersAndRoles
 *
 * Формирует строковое значение для разграничения доступа к экземпляру сущности
 * Для каждой операции описывает перечень ролей (roles) и пользователей (users), которые имеют доступ к экземпляру
 */
class UsersAndRoles extends AccessDescriptor implements UsersAndRolesInterface
{
  use UsersTrait;
  use RolesTrait;

  public function __construct($sd_xml = null)
  {
    parent::__construct(['roles', 'users'], $sd_xml);
  }
}
