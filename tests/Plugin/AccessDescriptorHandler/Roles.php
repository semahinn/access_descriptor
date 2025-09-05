<?php

namespace Snr\AccessDescriptor\Tests\Plugin\AccessDescriptorHandler;

use Snr\AccessDescriptor\AccessDescriptor\AccessDescriptorInterface;
use Snr\AccessDescriptor\Manager\HandlerPluginManager;
use Snr\AccessDescriptor\Plugin\AccessDescriptorHandler\HandlerPlugin;
use Snr\AccessDescriptor\Tests\Container\ContainerFactory;
use Snr\AccessDescriptor\Tests\Entity\UserInterface;
use Snr\AccessResult\AccessResultNeutral;
use Snr\AccessResult\AccessResultAllowed;

/**
 * @AccessDescriptorHandler(
 *   id = "roles",
 *   label = "Роли",
 *   description = "Роли",
 *   subjects = {
 *     "roles",
 *   },
 * )
 */
class Roles extends HandlerPlugin
{
  /**
   * {@inheritdoc}
   */
  public function access(AccessDescriptorInterface $access_descriptor, $account, string $operation = 'all') {
    // Если у пользователя есть хотя бы одна из ролей,
    // что хранится в $access_descriptor - доступ разрешён

    /** @var UserInterface $account */
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
