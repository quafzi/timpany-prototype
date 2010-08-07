<?php
class timpanyComponents extends sfComponents
{
  public function executeCartInfo()
  {
    $this->item_count = timpanyCart::getInstance($this->getUser())->getItemCount();
  }
}
