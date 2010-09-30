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
    if (0 == $cart->getItemCount()) {
    	$this->redirect('@timpany_cart');
    }
    $this->order = timpanyOrderTable::getInstance()->createOrder($cart);
    /* payment requires a persistant order */
    $this->order->save();
    $payment = $this->order->createPayment($this);
      
    try
    {
      if ($payment->hasOpenTransaction())
      {
        $transaction = $payment->getOpenTransaction();
        if (!$transaction instanceof FinancialApproveTransaction)
          throw new LogicException('This payment has another pending transaction.');
          
        $payment->performTransaction($transaction);
      }
      else
      {
        $payment->approve();
      }
    }
    catch (jmsPaymentException $e)
    {
      // for now there is only one action, so we do not need additional
      // processing here
      if ($e instanceof jmsPaymentUserActionRequiredException
          && $e->getAction() instanceof jmsPaymentUserActionVisitURL)
      {
        $this->amount = $payment->getOpenTransaction()->requested_amount;
        $this->currency = $payment->currency;
        $this->url = $e->getAction()->getUrl();
        
        $this->redirect($this->url);
      }
      
      $this->error = $e->getMessage();
      
      return 'Error';
    }
    
    $this->getUser()->setFlash('notice', 'The payment was approved successfully.');
  }
  
  public function executeCheckoutFinished(sfWebRequest $request)
  {
    timpanyCart::getInstance($this->getUser())->clear();
  	$this->order = Doctrine::getTable('timpanyOrder')->findOneById(
  	  $this->getUser()->getFlash('timpany_last_order_id')
    );
  }
}
