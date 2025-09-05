<?php

namespace Snr\AccessDescriptor\Manager;

use Snr\AccessDescriptor\Annotation\AccessDescriptorHandler;
use Snr\AccessDescriptor\Factory\HandlerFactory;
use Snr\AccessDescriptor\HandlerGroup;
use Snr\AccessDescriptor\Plugin\AccessDescriptorHandler\HandlerPluginInterface;
use Snr\Plugin\Manager\ByPluginClassTrait;
use Snr\Plugin\Manager\DefaultPluginManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class HandlerPluginManager extends DefaultPluginManager implements HandlerPluginManagerInterface
{
  use ByPluginClassTrait;

  /**
   * @var EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $namespaces, EventDispatcherInterface $event_dispatcher)
  {
    $this->eventDispatcher = $event_dispatcher;
    parent::__construct("Plugin\AccessDescriptorHandler", $namespaces, HandlerPluginInterface::class, AccessDescriptorHandler::class);
    $this->factory = new HandlerFactory($this, HandlerPluginInterface::class);
  }

  /**
   * {@inheritdoc}
   */
  public function getEventDispatcher()
  {
    return $this->eventDispatcher;
  }

  /**
   * {@inheritdoc}
   */
  public function getHandlerBySubjects(array $subjects, bool $exception_on_invalid = true)
  {
    $handler = null;
    try
    {
      $handler = new HandlerGroup($this, $subjects);
      if (count($handlers = $handler->getHandlers()) == 1)
        return current($handlers);
    }
    catch (\Exception $ex)
    {
      if ($exception_on_invalid)
        throw $ex;
    }

    return $handler;
  }
}