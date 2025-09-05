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
 *   id = "users",
 *   label = "Пользователи",
 *   description = "Пользователи",
 *   subjects = {
 *     "users",
 *   },
 * )
 */
class Users extends HandlerPlugin {

  /**
   * {@inheritdoc}
   */
  public function access(AccessDescriptorInterface $access_descriptor, $account, string $operation = 'all') {

    // Админам всегда разрешено
    /** @var UserInterface $account */
    if (in_array('admin', $account->getRoles())) {
      return new AccessResultAllowed();
    }

    // Если идентификатор этого пользователь хранится в $access_descriptor - доступ разрешён
    $users = $access_descriptor->getIdsWithAccess('users', $operation);
    if (in_array($account->id(), $users)) {
      return new AccessResultAllowed();
    }

    return new AccessResultNeutral();
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginManager() {
    return ContainerFactory::getContainer()->get(HandlerPluginManager::class);
  }

}
