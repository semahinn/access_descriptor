<?php

namespace Snr\AccessDescriptor\Tests\Container;

use Psr\Container\ContainerInterface as PsrContainerInterface;

class Container implements ContainerInterface {

  /**
   * @var PsrContainerInterface
   */
  protected $container = null;

 public function __construct(PsrContainerInterface $container)
 {
   return $this->container = $container;
 }

  public function get(string $id)
  {
    return $this->container->get($id);
  }

  public function has(string $id): bool
  {
    return $this->container->has($id);
  }

}