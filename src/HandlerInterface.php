<?php

namespace Snr\AccessDescriptor;

use Snr\AccessDescriptor\AccessDescriptor\AccessDescriptorInterface;
use Snr\AccessResult\AccessResultInterface;

interface HandlerInterface
{
  /**
   * Вычисляет доступ пользователя $account на основе
   * данных из дескриптора доступа $access_descriptor
   *
   * @param AccessDescriptorInterface $access_descriptor
   *
   * @param mixed $account
   *
   * @param string $operation
   *
   * @return AccessResultInterface
   */
  public function access(AccessDescriptorInterface $access_descriptor, $account, string $operation = 'all');

  /**
   * @return array
   *  Субъекты доступа
   */
  public function availableSubjects();

}
