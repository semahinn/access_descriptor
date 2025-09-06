<?php

namespace Snr\AccessDescriptor\Tests\Manager;

use Snr\AccessDescriptor\Annotation\AccessDescriptorHandler;
use Snr\AccessDescriptor\Factory\HandlerFactory;
use Snr\AccessDescriptor\HandlerGroup;
use Snr\AccessDescriptor\Manager\HandlerPluginManager;
use Snr\AccessDescriptor\Manager\HandlerPluginManagerInterface;
use Snr\AccessDescriptor\Plugin\AccessDescriptorHandler\HandlerPluginInterface;
use Snr\Plugin\Manager\ByPluginClassTrait;
use Snr\Plugin\Manager\DefaultPluginManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TestHandlerPluginManager extends HandlerPluginManager {

  public function __construct(array $namespaces, EventDispatcherInterface $event_dispatcher)
  {
    parent::__construct($namespaces, $event_dispatcher);
    $this->subdir = "Dir\\SubDir";
  }

}