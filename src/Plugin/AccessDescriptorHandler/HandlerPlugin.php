<?php

namespace Snr\AccessDescriptor\Plugin\AccessDescriptorHandler;

use Snr\AccessDescriptor\AccessDescriptor\AccessDescriptorInterface;

abstract class HandlerPlugin implements HandlerPluginInterface
{
  public function __construct()
  {
  }

  /**
   * @param array $options
   */
  public function doCreate(array $options)
  {
  }

  /**
   * {@inheritdoc}
   */
  public abstract function access(AccessDescriptorInterface $access_descriptor, $account, string $operation = 'all');

  /**
   * {@inheritdoc}
   */
  public final function availableSubjects() {
    $definition = static::getPluginDefinition();
    return $definition['subjects'];
  }

  /**
   * {@inheritdoc}
   */
  public final function getPluginDefinition() {
    return $this->getPluginManager()->getDefinitionByPluginClass(static::class);
  }

  /**
   * {@inheritdoc}
   */
  public final function getPluginId() {
    return $this->getPluginDefinition()['id'];
  }

}
