<?php

class timpanyCart implements timpanyCartInterface
{
  const SESSION_NS = 'timpanyCart';
  
  /**
   * singleton instance
   * @var timpanyCart
   */
  protected static $_instance=null;
  
  /**
   * owning user
   * @var sfUser
   */
  protected $_user;
  
  /**
   * cart items
   * @var array
   */
  protected $_items=array();
  
  /**
   * order state
   */
  protected $_orderState;
  
  /**
   * @var timpanyOrder $_order
   */
  protected $_order;
  
  /**
   * get singleton instance
   * @param sfUser $sfUser
   * @return timpanyCart
   */
  public static function getInstance(sfUser $sfUser)
  {
    if (is_null(self::$_instance)) {
	    if ($sfUser->isAuthenticated()) {
        self::$_instance = timpanyUserCartTable::getInstance()->findOneBySfGuardUserId($sfUser->getGuardUser()->getId());
        if (false == self::$_instance) {
        	self::$_instance = new timpanyUserCart();
        	self::$_instance->setSfGuardUserId($sfUser->getGuardUser()->getId());
        }
	    } else {
	      self::$_instance = new timpanyCart();
	      self::$_instance->_user = $sfUser;
		    self::$_instance->_items = $sfUser->getAttribute(
		      'cart_items',
		      array(),
		      timpanyCart::SESSION_NS
		    );
	    }
    }
    return self::$_instance;
  }
  protected function __construct() {}
  private function __clone() {}
  
  /**
   * save cart to session
   */
  public function save()
  {
    $this->_user->setAttribute(
      'cart_items',
      $this->_items,
      timpanyCart::SESSION_NS
    );
  }
  
  /**
   * add a product to the cart
   * 
   * @param timpanyProductInterface $product
   * @param int                     $count
   */
  public function addProduct(timpanyProductInterface $product, $count=1)
  {
    if (false == array_key_exists($product->getIdentifier(), $this->_items)) {
    	$this->_items[$product->getIdentifier()] = new timpanyCartItem();
    	$this->_items[$product->getIdentifier()]
    	  ->setCount($count)
        ->setProductData($product->toCartItem());
    } else {
      $this->_items[$product->getIdentifier()]->increaseCount($count);
    }
  }
  
  /**
   * clear cart
   */
  public function clear()
  {
    $this->setItems(array());
  }
  
  /**
   * set cart items
   * @param array $items
   */
  public function setItems($items)
  {
  	$this->_items = $items;
  }
  
  /**
   * get count of a specific product
   * @param timpanyProductInterface $product
   * @return int Count of product
   */
  public function getCountOfProduct(timpanyProductInterface $product)
  {
    return $this->_items[$product->getIdentifier()]['count'];
  }
  
  /**
   * get cart items
   * @return array Array with following structure:
   *       'count'     => integer,
   *       'product'   => timpanyProductInterface,
   *       'net_sum'   => float
   *       'gross_sum' => float
   */
  public function getItems()
  {
  	return $this->_items;
  }
  
  /**
   * get net sum
   * @return float
   */
  public function getNetSum()
  {
    $net_sum = 0;
    foreach ($this->getItems() as $item) {
      $net_sum += $item['count']*$item['product_data']['net_price'];
    }
    return $net_sum;
  }
  
  /**
   * get gross sum
   * @return float
   */
  public function getGrossSum()
  {
    $gross_sum = 0;
    foreach ($this->getItems() as $item) {
      $gross_sum += $item->getGrossSum();
    }
    return $gross_sum;
  }
  
  /**
   * remove item from cart
   * @param timpanyProductInterface $product
   */
  public function removeItem($product_key)
  {
    $items = $this->getItems();
    unset($items[$product_key]);
    $this->setItems($items);
  }
  
  /**
   * get count of products
   * @return int
   */
  public function getProductCount()
  {
    $count = 0;
  	foreach ($this->getItems() as $item) {
  	  $count += $item['count'];
  	}
  	return $count;
  }
  
  /**
   * get count of items
   * @return int
   */
  public function getItemCount()
  {
    return count($this->_items);
  }
  
  /**
   * turn cart into an array
   * @param boolean $deep
   * @return array
   */
  public function toArray($deep=true)
  {
    return $this->getContent();
  }
  
  /**
   * if cart is empty
   * @return boolean
   */
  public function isEmpty()
  {
  	return 0 == $this->getItemCount();
  }
}
