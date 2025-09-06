<?php

namespace Snr\AccessDescriptor\Plugin\AccessDescriptorHandler;

use Snr\AccessDescriptor\HandlerInterface;
use Snr\Plugin\Plugin\PluginableInstanceInterface;

interface HandlerPluginInterface extends HandlerInterface, PluginableInstanceInterface
{
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
