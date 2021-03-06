<?php

interface timpanyCartInterface
{
  /**
   * add a product to the cart
   * 
   * @param timpanyProductInterface $product
   * @param int                     $count
   */
  public function addProduct(timpanyProductInterface $product, $count=1);
  
  /**
   * clear cart
   */
  public function clear();
  
  /**
   * set cart items
   * @param array $items
   */
  public function setItems($items);
  
  /**
   * get count of a specific product
   * @param timpanyProductInterface $product
   * @return int Count of product
   */
  public function getCountOfProduct(timpanyProductInterface $product);

  /**
   * get cart items
   * @return array Array with following structure:
   *       'count'     => integer,
   *       'product'   => timpanyProductInterface,
   *       'net_sum'   => float
   *       'gross_sum' => float
   */
  public function getItems();

  /**
   * get net sum
   * @return float
   */
  public function getNetSum();
  
  /**
   * get gross sum
   * @return float
   */
  public function getGrossSum();
  
  /**
   * remove item from cart
   * @param string $key Key of item collection
   */
  public function removeItem($key);
  
  /**
   * get count of products
   * @return int
   */
  public function getProductCount();

  /**
   * get count of items
   * @return int
   */
  public function getItemCount();
  
  /**
   * turn cart into an array
   * @param boolean $deep
   * @return array
   */
  public function toArray($deep=true);
}
