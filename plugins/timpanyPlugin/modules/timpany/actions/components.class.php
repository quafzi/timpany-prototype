<?php
class timpanyComponents extends sfComponents
{
  public function executeCartInfo()
  {
    $this->product_count = timpanyCart::getInstance($this->getUser())->getProductCount();
  }
  
  public function executeUserInfo()
  {
  }
}
