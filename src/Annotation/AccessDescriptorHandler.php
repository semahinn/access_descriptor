<?php

namespace Snr\AccessDescriptor\Annotation;

use Snr\Plugin\Plugin;

/**
 * @Annotation
 */
class AccessDescriptorHandler extends Plugin {

  /**
   * @var string
   */
  public $id;

  /**
   * @var string
   */
  public $label;

  /**
   * @var string
   */
  public $description;

  /**
   * @var array
   */
  public $subjects = [];

}