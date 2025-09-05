<?php

namespace Snr\AccessDescriptor\Tests\AccessDescriptor;

use Snr\AccessDescriptor\AccessDescriptor\AccessDescriptor as BaseAccessDescriptor;
use Snr\AccessDescriptor\Manager\HandlerPluginManager;
use Snr\AccessDescriptor\Manager\HandlerPluginManagerInterface;
use Snr\AccessDescriptor\Tests\Container\ContainerFactory;

class AccessDescriptor extends BaseAccessDescriptor
{
  public function getHandler()
  {
    /**
     * @var $plugin_manager HandlerPluginManagerInterface
     */
    $plugin_manager = ContainerFactory::getContainer()->get(HandlerPluginManager::class);
    if (!$this->handler)
      $this->handler = $plugin_manager->getHandlerBySubjects($this->subjects);
    return $this->handler;
  }

}