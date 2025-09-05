<?php

namespace Snr\AccessDescriptor\Tests\Container;

use Psr\Container\ContainerInterface as PsrContainerInterface;

/**
 * Только те сервисы, которые нужны логике рабочих процессов
 */
interface ContainerInterface extends PsrContainerInterface {

}