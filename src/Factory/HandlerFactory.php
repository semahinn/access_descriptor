<?php

namespace Snr\AccessDescriptor\Factory;

use Snr\AccessDescriptor\Plugin\AccessDescriptorHandler\HandlerInterface;
use Snr\Plugin\Factory\DefaultFactory;

class HandlerFactory extends DefaultFactory implements HandlerFactoryInterface {

  /**
   * {@inheritdoc}
   */
  public function createInstance($plugin_id, array $configuration = []) {
    $plugin_definition = $this->discovery->getDefinition($plugin_id);
    $plugin_class = static::getPluginClass($plugin_id, $plugin_definition, $this->interface);

    /**
     * @see HandlerInterface::create()
     */
    return $plugin_class::create($configuration);
  }

}