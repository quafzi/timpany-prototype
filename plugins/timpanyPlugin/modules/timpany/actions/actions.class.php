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
    $this->form = new timpanyProductToCartForm();
  }
  
  public function executeAddToCart(sfWebRequest $request)
  {
    $product = timpanyProductTable::getInstance()->findOneBySlug($request->getParameter('product'));
    $count = $request->getPostParameter('timpany_add_to_cart[count]', 1);
    $this->cart = timpanyCart::getInstance($this->getUser());
    $this->cart->addProduct($product, $count);
    $this->cart->save();
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
    $this->cart->removeItem($request->getParameter('product'));
    $this->cart->save();
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
      /* payment requires a persistant order */
      $this->order->save();
      $this->order->createPayment();
      $cart->clear();
    } else {
      $this->redirect('@timpany_cart');
    }
  }
}
