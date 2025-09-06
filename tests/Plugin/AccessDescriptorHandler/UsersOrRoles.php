<?php

namespace Snr\AccessDescriptor\Tests\Plugin\AccessDescriptorHandler;

use Snr\AccessDescriptor\AccessDescriptor\AccessDescriptorInterface;
use Snr\AccessDescriptor\Manager\HandlerPluginManager;
use Snr\AccessDescriptor\Plugin\AccessDescriptorHandler\HandlerPlugin;
use Snr\AccessDescriptor\Tests\Container\ContainerFactory;
use Snr\AccessDescriptor\Tests\Entity\UserInterface;
use Snr\AccessResult\AccessResultAllowed;
use Snr\AccessResult\AccessResultNeutral;

/**
 * @AccessDescriptorHandler(
 *   id = "users_or_roles",
 *   label = "Пользователи и роли",
 *   description = "Пользователи и роли",
 *   subjects = {
 *     "users",
 *     "roles",
 *   },
 * )
 */
class UsersOrRoles extends HandlerPlugin
{
  /**
   * {@inheritdoc}
   */
  public function access(AccessDescriptorInterface $access_descriptor, $account, string $operation = 'all') {

    // Пример комбинации доступа по ролям и пользователям (Когда вас не устраивает, что
    // выполняется в Roles.php и Users.php)

    /** @var UserInterface $account */
    $users = $access_descriptor->getIdsWithAccess('users', $operation);
    if (in_array($account->id(), $users))
      return new AccessResultAllowed();

    if (in_array('admin', $account->getRoles())) return new AccessResultAllowed();

    $roles = $access_descriptor->getIdsWithAccess('roles', $operation);
    if (array_intersect($roles, $account->getRoles()))
      return new AccessResultAllowed();

    return new AccessResultNeutral();
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginManager() {
    return ContainerFactory::getContainer()->get(HandlerPluginManager::class);
  }
}
