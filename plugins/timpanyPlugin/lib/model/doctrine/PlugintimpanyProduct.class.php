<?php

/**
 * PlugintimpanyProduct
 * 
 * @package    timpany
 * @subpackage model
 * @author     Thomas Kappel <quafzi@netextreme.de>
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
   * @return timpanyTaxClass
   */
  public function getTaxClass()
  {
    return parent::_get('TaxClass');
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
  public function getTaxRate($region='de')
  {
    return $this->getTaxClass()->getTaxRate();
  }
  
  /**
   * get tax in percent
   * 
   * @param string $region
   * 
   * @return float
   */
  public function getTaxPercent($region='de')
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
  public function getTaxAmount($region='de')
  {
    return $this->getNetPrice() * $this->getTaxRate($region);
  }
  
  /**
   * get the gross price of the product
   *
   * @return float
   */
  public function getGrossPrice($region='de')
  {
    return $this->getNetPrice() + $this->getTaxAmount($region);
  }
  
  /**
   * prepare product for checkout
   * @return timpanyCartItem
   */
  public function toOrderItem()
  {
    return new timpanyOrderItem($this->getData());
  }
  
  public function toJson($region='de')
  {
    return json_encode(array(
        'name'           => $this->getName(),
        'article_number' => $this->getArticleNumber(),
        'description'    => $this->getDescription(),
        'properties'     => $this->getProperties(),
        'net_price'      => $this->getNetPrice(),
        'gross_price'    => $this->getGrossPrice($region),
        'tax_rate'       => $this->getTaxRate($region)
    ));
  }
}