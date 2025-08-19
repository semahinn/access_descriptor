<?php

namespace Snr\AccessDescriptor\Plugin\AccessDescriptorHandler;

use Snr\AccessDescriptor\AccessDescriptor\AccessDescriptorInterface;
use Snr\AccessDescriptor\Manager\HandlerPluginManager;
use Snr\AccessDescriptor\ServicesFactory;

abstract class Handler implements HandlerInterface
{
  protected function __construct()
  {
  }

  /**
   * {@inheritdoc}
   */
  public final static function create(array $options)
  {
    $instance = new static();
    $instance->doCreate($options);
    return $instance;
  }

  /**
   * @param array $options
   */
  protected function doCreate(array $options)
  {
  }

  /**
   * {@inheritdoc}
   */
  public abstract function access(AccessDescriptorInterface $security_descriptor, $account, string $operation = 'all');

  /**
   * {@inheritdoc}
   */
  public final function availableSubjects()
  {
    $definition = static::getPluginDefinition();
    return $definition['subjects'];
  }

  // $plugin_type = 'access'
  // $plugin_type = 'security_descriptor_selection
  public function getHandlerInstance($plugin_type, $definition_id, $options = [])
  {
    $plugin = ServicesFactory::getInstance()->getService("access_descriptor.manager.$plugin_type");
    return $plugin->createInstance($definition_id, $options);
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginManager() {
    return ServicesFactory::getInstance()->getService(HandlerPluginManager::class);
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
