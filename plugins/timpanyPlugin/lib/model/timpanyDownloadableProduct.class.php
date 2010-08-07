<?php

class timpanyDownloadableProduct extends timpanyProduct
{
  /**
   * get net shipping price, returns zero because no shipping required
   * 
   * @return float
   */
  public function getNetShippingPrice()
  {
    return 0.0;
  }
  
  /**
   * endless availability
   * 
   * @return int
   */
  public function getInventory()
  {
    return 1000000;
  }
}
