<?php

namespace Snr\AccessDescriptor\Plugin\AccessDescriptorHandler;

use Snr\AccessDescriptor\AccessDescriptor\AccessDescriptorInterface;

/**
 * @SecurityDescriptorHandler(
 *   id = "roles",
 *   label = "Роли",
 *   description = "Роли",
 *   subjects = {
 *     "roles",
 *   },
 * )
 */
class Roles extends Handler
{
  /**
   * {@inheritdoc}
   */
  public function access(AccessDescriptorInterface $security_descriptor, $account, string $operation = 'all') {
    $roles = $security_descriptor->getIdsWithAccess('roles', $operation);
    if (array_intersect($roles, $account->getRoles()))
      return true;

    return false;
  }
}
