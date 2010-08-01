<?php

class timpanyProduct implements timpanyProductInterface
{
  /**
   * get the name of the product
   *
   * @return string
   */
  public function getName()
  {
    return parent::getName();
  }
  
  /**
   * get the net price of the product
   *
   * @return float
   */
  public function getNetPrice()
  {
    return parent::getNetPrice();
  }
  
  /**
   * get the description of the product
   *
   * @return string
   */
  public function getDescription()
  {
    return parent::getDescription();
  }
  
  /**
   * get article number
   *
   * @return string
   */
  public function getArticleNumber()
  {
    return parent::getArticleNumber();
  }
  
  /**
   * get product properties
   * 
   * @return array
   */
  public function getProperties()
  {
    return array(
      'demo' => true
    );
  }
  
  /**
   * get the url key of the product
   *
   * @return string
   */
  public function getSlug()
  {
    return parent::getSlug();
  }
  
  /**
   * get inventory of the product
   *
   * @return float
   */
  public function getInventory()
  {
    return parent::getInventory();
  }
  
  /**
   * get tax class
   * 
   * @todo NOT YET IMPLEMENTED
   * 
   * @return timpanyTaxClass
   */
  public function getTaxClass()
  {
    return null;
    
    return parent::getTaxClass();
  }
  
  /**
   * get tax rate
   * 
   * @todo NOT YET IMPLEMENTED
   * 
   * @param string $region
   * 
   * @return float
   */
  public function getTaxRate($region)
  {
    return 0.19;
  }
  
  /**
   * get tax amount based on net price
   * 
   * @param string $region
   * 
   * @return float
   */
  public function getTaxAmount($region)
  {
    return $this->getNetPrice() * $this->getTaxRate($region);
  }
  
  /**
   * get the gross price of the product
   *
   * @return float
   */
  public function getGrossPrice($region)
  {
    return $this->getNetPrice() + $this->getTaxAmount($region);
  }
}
