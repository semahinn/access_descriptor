<?php

namespace Snr\AccessDescriptor\Factory;

use Snr\AccessDescriptor\Plugin\AccessDescriptorHandler\HandlerPluginInterface;
use Snr\Plugin\Factory\DefaultFactory;

class HandlerFactory extends DefaultFactory implements HandlerFactoryInterface {

  /**
   * {@inheritdoc}
   */
  public function createInstance($plugin_id, array $configuration = []) {
    $plugin_definition = $this->discovery->getDefinition($plugin_id);
    $plugin_class = static::getPluginClass($plugin_id, $plugin_definition, $this->interface);

    $this->preCreate($plugin_class, $configuration);
    $instance = $this->doCreate($plugin_class, $configuration);
    $this->postCreate($instance, $configuration);

    return $instance;
  }

  /**
   * @param string $plugin_class
   * @param array $configuration
   *
   * @return void
   */
  protected function preCreate(string $plugin_class, array &$configuration) {
    if (method_exists($plugin_class, 'preCreate')) {
      $plugin_class::preCreate($configuration);
    }
  }

  /**
   * @param string $plugin_class
   * @param array $configuration
   *
   * @return HandlerPluginInterface
   */
  protected function doCreate(string $plugin_class, array $configuration) {
    $instance = new $plugin_class($configuration);
    if (method_exists($instance, 'doCreate')) {
      $instance->doCreate($configuration);
    }
    return $instance;
  }

  /**
   * @param HandlerPluginInterface $instance
   * @param array $configuration
   *
   * @return void
   */
  protected function postCreate(HandlerPluginInterface $instance, array $configuration) {
    if (method_exists($instance, 'postCreate')) {
      $instance->postCreate($configuration);
    }
  }

}