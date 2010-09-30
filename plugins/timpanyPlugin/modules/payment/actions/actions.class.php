<?php
class paymentActions extends sfActions
{
  public function executeDeposit(sfWebRequest $request)
  {
    $payment = $this->getPaymentFromRequest($request);
    $this->forward404Unless($payment);
    
    try
    {
      if ($payment->hasOpenTransaction())
      {
        $transaction = $payment->getOpenTransaction();
        if (!$transaction instanceof FinancialDepositTransaction)
          throw new LogicException('This payment has another pending transaction.');
          
        $payment->performTransaction($transaction);
      }
      else
        $payment->deposit();
    }
    catch (jmsPaymentException $e)
    {
      $this->error = $e->getMessage();
      
      return 'Error';
    }
    
    $this->getUser()->setFlash('notice', 'The payment was deposited successfully.');
    $this->redirect('paymentDemo/index');    
  }
  
  public function executeApprove(sfWebRequest $request)
  {
    $payment = $this->getPaymentFromRequest($request);            
    $this->forward404Unless($payment);
    
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
          
        return 'Redirect';
      }
      
      $this->error = $e->getMessage();
      
      return 'Error';
    }
    
    $this->getUser()->setFlash('notice', 'The payment was approved successfully.');
    $this->getUser()->setFlash('timpany_last_order_id', $payment->getOrder()->getId());
    $this->redirect('@timpany_checkout_finished');
  }
  
  protected function getPaymentFromRequest(sfWebRequest $request)
  {
    if ($request->hasParameter('token'))
      return Doctrine_Core::getTable('Payment')->createQuery('p')
              ->innerJoin('p.DataContainer d WITH d.express_token = ?', $request->getParameter('token'))
              ->leftJoin('p.Transactions t')
              ->fetchOne(); 
    
    else
      return Doctrine_Core::getTable('Payment')->createQuery('p')
              ->leftJoin('p.DataContainer d')
              ->leftJoin('p.Transactions t')
              ->where('p.id = ?', $request->getParameter('id'))
              ->fetchOne();
    
    return false;
  }
}
