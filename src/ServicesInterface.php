<?php

namespace Snr\AccessDescriptor;

/**
 * Только те сервисы, которые нужны логике рабочих процессов
 */
interface ServicesInterface {

  /**
   * @param string $id
   *
   * @return mixed
   */
  public function getService(string $id);

}