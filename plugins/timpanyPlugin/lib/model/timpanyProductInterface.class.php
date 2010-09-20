<?php
interface timpanyProductInterface
{
	/**
	 * get unique product identifier
	 * 
	 * @return string
	 */
	public function getIdentifier();
	
  /**
   * get the name of the product
   *
   * @return string
   */
  public function getName();
  
  /**
   * get the net price of the product
   *
   * @return float
   */
  public function getNetPrice();
  
  /**
   * get the description of the product
   *
   * @return string
   */
  public function getDescription();
  
  /**
   * get article number
   *
   * @return string
   */
  public function getArticleNumber();
  
  /**
   * get product properties
   * 
   * @return array
   */
  public function getProperties();
  
  /**
   * get the url key of the product
   *
   * @return string
   */
  public function getSlug();
  
  /**
   * get inventory of the product
   *
   * @return float
   */
  public function getInventory();
  
  /**
   * get tax class
   * 
   * @return timpanyTaxClass
   */
  public function getTaxClass();
  
  /**
   * get tax rate
   * 
   * @param string $region
   * 
   * @return float
   */
  public function getTaxRate($region);
  
  /**
   * get tax amount based on net price
   * 
   * @param string $region
   * 
   * @return float
   */
  public function getTaxAmount($region);
  
  /**
   * get the gross price of the product
   * 
   * @param string $region
   *
   * @return float
   */
  public function getGrossPrice($region);
  
  /**
   * prepare required values for cart
   * 
   * @return array
   */
  public function toCartItem();
}
