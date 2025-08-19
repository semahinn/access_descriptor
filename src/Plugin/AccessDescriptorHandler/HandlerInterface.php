<?php

namespace Snr\AccessDescriptor\Plugin\AccessDescriptorHandler;

use Snr\AccessDescriptor\HandlerInterface as BaseHandlerInterface;
use Snr\Plugin\Plugin\PluginableInstanceInterface;

interface HandlerInterface extends BaseHandlerInterface, PluginableInstanceInterface
{
  /**
   * @param array $options
   *
   * @return mixed
   */
  public static function create(array $options);

  /**
   * Возвращает определение плагина этого этапа
   *
   * @return array
   */
  public function getPluginDefinition();

  /**
   * Возвращает идентификатор плагина этого этапа.
   *
   * @return string
   */
  public function getPluginId();

}
