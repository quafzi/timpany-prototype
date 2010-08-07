<?php
class timpanyActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->products = timpanyProductTable::getInstance()->findAll();
  }
  
  public function executeShowCategory(sfWebRequest $request)
  {
  }
  
  public function executeShowProduct(sfWebRequest $request)
  {
    $this->product = timpanyProductTable::getInstance()->findOneBySlug($request->getParameter('product'));
  }
  
  public function executeAddToCart(sfWebRequest $request)
  {
    $this->product = timpanyProductTable::getInstance()->findOneBySlug($request->getParameter('product'));
    $this->cart    = timpanyCart::getInstance($this->getUser());
    $this->cart->addProduct($this->product);
    $this->forward('timpany', 'cart');
  }
  
  public function executeCart(sfWebRequest $request)
  {
    $this->cart = timpanyCart::getInstance($this->getUser());
  }
  
  public function executeRemoveCartItem(sfWebRequest $request)
  {
    $this->cart = timpanyCart::getInstance($this->getUser());
    $this->cart->removeItemBySlug($request->getParameter('product'));
    $this->forward('timpany', 'cart');
  }
  
}
