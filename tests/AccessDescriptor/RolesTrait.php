<?php

namespace Snr\AccessDescriptor\Tests\AccessDescriptor;

trait RolesTrait
{
  /**
   * {@inheritdoc}
   */
  public function getRoles(string $operation = 'all')
  {
    return $this->getIdsWithAccess('roles', $operation);
  }

  /**
   * {@inheritdoc}
   */
  public function addRoles($ids, string $operation = 'all')
  {
    $this->addAccess($ids, 'roles', $operation);
  }

  /**
   * {@inheritdoc}
   */
  public function removeRoles($ids, string $operation = 'all')
  {
    $this->removeAccess($ids, 'roles', $operation);
  }


  /**
   * {@inheritdoc}
   */
  public function hasRoleAccess($id, string $operation = 'all')
  {
    return $this->hasAccess($id, 'roles', $operation);
  }
}
