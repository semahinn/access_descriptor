<?php

namespace Snr\AccessDescriptor;

class ServicesFactory
{
  /**
   * @var ServicesInterface
   */
  protected static $instance = null;

  public static function getInstance(array $services = []) {
    if (!static::$instance) {
      static::$instance = new Services($services);
    }
    return static::$instance;
  }

}