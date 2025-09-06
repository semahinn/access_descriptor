<?php

namespace Snr\AccessDescriptor;

interface HandlerGroupInterface extends HandlerInterface
{
  /**
   * @return HandlerInterface[]
   */
  public function getHandlers();
}
