<?php

/**
 * PlugintimpanyTaxClass
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class PlugintimpanyTaxClass extends BasetimpanyTaxClass
{
	/**
	 * get tax rate for region
	 * 
	 * @param string $region
	 * 
	 * @return float Tax rate
	 */
  public function getTaxRate($region='de')
  {
  	return $this->getTaxPercent($region) / 100.0;
  }
  
  /**
   * get tax percent for region
   * 
   * @param string $region
   * 
   * @return float Tax
   */
  public function getTaxPercent($region='de')
  {
  	return timpanyTaxTable::getInstance()
  	  ->findByDql('tax_class_id = ' . $this->getId() . ' AND region = "' . $region . '"')
  	  ->getFirst()
  	  ->getTaxPercent();
  }
}