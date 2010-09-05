<?php

interface timpanyOrderInterface extends timpanyCartInterface
{
  /**
   * get order state
   * @return timpanyOrderState
   */
  public function getState();
}
