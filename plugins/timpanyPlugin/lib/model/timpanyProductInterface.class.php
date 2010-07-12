<?php
interface timpanyProductInterface
{
  /**
   * get the name of the product
   *
   * @returns string
   */
  public function getName();
  
  /**
   * get the net price of the product
   *
   * @returns float
   */
  public function getNetPrice();
}
