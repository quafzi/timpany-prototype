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
    $product = timpanyProductTable::getInstance()->findOneBySlug($request->getParameter('product'));
    $this->cart = timpanyCart::getInstance($this->getUser());
    $this->cart->addProduct($product);
    $this->getUser()->setFlash('last_added_product', $product->getSlug());
    $this->redirect('@timpany_cart');
  }
  
  public function executeCart(sfWebRequest $request)
  {
    if ($this->getUser()->hasFlash('last_added_product')) {
      $this->product = timpanyProductTable::getInstance()->findOneBySlug($this->getUser()->getFlash('last_added_product'));
    }
    $this->cart = timpanyCart::getInstance($this->getUser());
  }
  
  public function executeRemoveCartItem(sfWebRequest $request)
  {
    $this->cart = timpanyCart::getInstance($this->getUser());
    $this->cart->removeItemBySlug($request->getParameter('product'));
    $this->redirect('@timpany_cart');
  }
  
  public function executeCheckout(sfWebRequest $request)
  {
    $this->cart = timpanyCart::getInstance($this->getUser());
  }
  
  public function executeFinishCheckout(sfWebRequest $request)
  {
    $cart = timpanyCart::getInstance($this->getUser());
    if (0 < $cart->getItemCount()) {
      $this->order = timpanyOrderTable::getInstance()->createOrder($cart);
      $this->order->save();
      $cart->clear();
    } else {
      $this->redirect('@timpany_cart');
    }
  }
}
