<?php

namespace Snr\AccessDescriptor;

class Services implements ServicesInterface {

  /**
   * @var array
   */
  protected $services = [];

  public function __construct(array $services = []) {
    $this->services = $services;
  }

  /**
   * {@inheritdoc}
   */
  public function getService($id) {
    if (isset($this->services[$id])) {
      return $this->services[$id];
    }
    return null;
  }

}