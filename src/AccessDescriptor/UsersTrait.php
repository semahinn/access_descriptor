<?php

namespace Snr\AccessDescriptor\AccessDescriptor;

trait UsersTrait {

  /**
   * {@inheritdoc}
   */
  public function getUsers(string $operation = 'all')
  {
    return $this->getIdsWithAccess('users', $operation);
  }

  /**
   * {@inheritdoc}
   */
  public function addUsers($uuids, string $operation = 'all')
  {
    $this->addAccess($uuids, 'users', $operation);
  }

  /**
   * {@inheritdoc}
   */
  public function removeUsers($uuids, string $operation = 'all')
  {
    $this->removeAccess($uuids, 'users', $operation);
  }

  /**
   * {@inheritdoc}
   */
  public function hasUserAccess($uuid, string $operation = 'all')
  {
    return $this->hasAccess($uuid, 'users', $operation);
  }
}
