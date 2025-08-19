<?php

namespace Snr\AccessDescriptor\Plugin\AccessDescriptorHandler;

use Snr\AccessDescriptor\AccessDescriptor\AccessDescriptorInterface;

/**
 * @SecurityDescriptorHandler(
 *   id = "users_or_roles",
 *   label = "Пользователи и роли",
 *   description = "Пользователи и роли",
 *   subjects = {
 *     "users",
 *     "roles",
 *   },
 * )
 */
class UsersOrRoles extends Handler
{
  /**
   * {@inheritdoc}
   */
  public function access(AccessDescriptorInterface $security_descriptor, $account, string $operation = 'all') {
    $users = $security_descriptor->getIdsWithAccess('users', $operation);
    if (in_array($account->id(), $users))
      return true;

    if (in_array('administrator', $account->getRoles())) return true;

    $roles = $security_descriptor->getIdsWithAccess('roles', $operation);
    if (array_intersect($roles, $account->getRoles()))
      return true;

    return false;
  }
}
