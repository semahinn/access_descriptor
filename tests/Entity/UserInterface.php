<?php

namespace Snr\AccessDescriptor\Tests\Entity;

interface UserInterface {

  /**
   * @return int
   */
  public function id();

  /**
   * @return string
   */
  public function uuid();

  /**
   * @return string
   */
  public function getDisplayName();

  /**
   * @return string
   */
  public function getUsername();

  /**
   * @return array
   */
  public function getRoles();

}