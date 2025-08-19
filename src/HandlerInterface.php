<?php

namespace Snr\AccessDescriptor;

use Snr\AccessDescriptor\AccessDescriptor\AccessDescriptorInterface;

interface HandlerInterface
{
  /**
   * @param AccessDescriptorInterface $security_descriptor
   *
   * @param $account
   *
   * @param string $operation
   *
   * @return bool
   */
  public function access(AccessDescriptorInterface $security_descriptor, $account, string $operation = 'all');

  /**
   * @return array
   *  Субъекты доступа
   */
  public function availableSubjects();

}
