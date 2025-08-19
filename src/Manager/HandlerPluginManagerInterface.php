<?php

namespace Snr\AccessDescriptor\Manager;

use Snr\Plugin\Manager\ByPluginClassInterface;
use Snr\Plugin\Manager\PluginManagerInterface;
use Snr\AccessDescriptor\HandlerInterface;

interface HandlerPluginManagerInterface extends PluginManagerInterface, ByPluginClassInterface {

  /**
   * Возвращает подходящий под субъекты доступа $subjects обработчик
   *
   * @param array $subjects
   *
   * @param bool $exception_on_invalid
   *
   * @return HandlerInterface
   *
   * @throws
   */
  public function getHandlerBySubjects(array $subjects, bool $exception_on_invalid = true);

}