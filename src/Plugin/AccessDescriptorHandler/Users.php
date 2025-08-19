<?php

namespace Snr\AccessDescriptor\Plugin\AccessDescriptorHandler;

use Snr\AccessDescriptor\AccessDescriptor\AccessDescriptorInterface;

/**
 * @SecurityDescriptorHandler(
 *   id = "users",
 *   label = "Пользователи",
 *   description = "Пользователи",
 *   subjects = {
 *     "users",
 *   },
 * )
 */
class Users extends Handler {

  /**
   * {@inheritdoc}
   */
  public function access(
    AccessDescriptorInterface $security_descriptor, $account, string $operation = 'all') {

    if (in_array('administrator', $account->getRoles())) {
      return TRUE;
    }

    $users = $security_descriptor->getIdsWithAccess('users', $operation);
    if (in_array($account->id(), $users)) {
      return TRUE;
    }

    return FALSE;
  }

  // !!!
  // Если понадобится определить по умному 'security_descriptor_selection' или
  //  'access' плагин для этого обработчика, то метод getHandlerInstance будет
  //  искать такой 'security_descriptor_selection' или 'access' плагин.
  // Сейчас же просто пишу логику в методе access, т.к. нет плагинов security_descriptor.manager.access
  // !!!

  //  public function access(
  //    SecurityDescriptorInterface $security_descriptor, AccountInterface $account, string $operation = 'all') {
  //
  //    $id = 'my_access_1';
  //    $instance = $this->getHandlerInstance('access', $id);
  //    return $instance->access();
  //  }

}
