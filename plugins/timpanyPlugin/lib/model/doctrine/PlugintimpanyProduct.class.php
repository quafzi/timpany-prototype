<?php

/**
 * PlugintimpanyProduct
 * 
 * @package    timpany
 * @subpackage model
 * @author     Thomas Kappel <quafzi@netextreme.de>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class PlugintimpanyProduct extends BasetimpanyProduct implements timpanyProductInterface
{
  /**
   * set product record for extending product types
   * @param timpanyProduct $basic_product
   */  
  public function setDoctrineRecord(timpanyProduct $basic_product)
  {
    $this->_id = $basic_product->getId();
    $this->load();
  }
  
  /**
   * get the name of the product
   *
   * @return string
   */
  public function getName()
  {
    return parent::_get('name');
  }
  
  /**
   * get the net price of the product
   *
   * @return float
   */
  public function getNetPrice()
  {
    return parent::_get('net_price');
  }
  
  /**
   * get the description of the product
   *
   * @return string
   */
  public function getDescription()
  {
    return parent::_get('description');
  }
  
  /**
   * get article number
   *
   * @return string
   */
  public function getArticleNumber()
  {
    return parent::_get('article_number');
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
    return parent::_get('slug');
  }
  
  /**
   * get inventory of the product
   *
   * @return float
   */
  public function getInventory()
  {
    return parent::_get('inventory');
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
    
    return parent::_get('tax_class');
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
   * get tax in percent
   * 
   * @param string $region
   * 
   * @return float
   */
  public function getTaxPercent($region)
  {
    return 100.0 * $this->getTaxRate($region);
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