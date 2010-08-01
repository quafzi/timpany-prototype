<?php
class timpanyActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->products = timpanyProductTable::getInstance()->findAll();
  }
}
