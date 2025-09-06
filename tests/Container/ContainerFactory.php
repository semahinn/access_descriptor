<?php

namespace Snr\AccessDescriptor\Tests\Container;

use Psr\Container\ContainerInterface as PsrContainerInterface;

class ContainerFactory
{
  /**
   * @var ContainerInterface
   */
  protected static $container = null;

  public static function getContainer(PsrContainerInterface $container = null)  {
    if (!static::$container) {
      static::$container = new Container($container);
    }
    return static::$container;
  }

}