<?php

class timpanyCart
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
   * get singleton instance
   * @param sfUser $sfUser
   * @return timpanyCart
   */
  public static function getInstance(sfUser $sfUser)
  {
    if (is_null(self::$_instance)) {
      self::$_instance = new timpanyCart();
      self::$_instance->_user = $sfUser;
	    if (self::$_instance->_user->isAuthenticated()) {
	    	self::$_instance->loadExistingCart();
	    }
    }
    return self::$_instance;
  }
  protected function __construct() {}
  private function __clone() {}

  /**
   * load existing cart of the user from the database
   */
  public function loadExistingCart()
  {
    $cartItems = $this->_user->getGuardUser()->getCartItems();
    $cartItemRelation   = $cartItems->getRelation('timpanyCartItem');
    $cartItemCollection = $cartItemRelation['table']->findBySfGuardUserId(
                            $this->_user->getGuardUser()->getId()
                          );
    $items = $this->getContent();
    foreach ($cartItemCollection as $cartItem) {
      $items[$cartItem->getProduct()->getSlug()] = $cartItem->getCount();
    }
    $this->setItems($items);
  }
  
  /**
   * add a product to the cart
   * 
   * @param timpanyProductInterface $product
   * @param int                     $count
   */
  public function addProduct(timpanyProductInterface $product, $count=1)
  {
    $items = $this->getContent();
    if (false == array_key_exists($product->getSlug(), $items)) {
      $items[$product->getSlug()] = $count;
    } else {
      $items[$product->getSlug()] += $count;
    }
    $this->setItems($items);
    if (self::$_instance->_user->isAuthenticated()) {
	    $cartItems = self::$_instance->_user->getGuardUser()->getCartItems();
	    $cartItemRelation   = $cartItems->getRelation('timpanyCartItem');
      $cartItemCollection = $cartItemRelation['table']->findBySfGuardUserId(
											        self::$_instance->_user->getGuardUser()->getId()
											      );
	    $is_new_item = true;
			foreach ($cartItemCollection as $cartItem) {
				if ($product->getId() === $cartItem->getProductId()) {
					$is_new_item = false;
					break;
				}
			}
	    if ($is_new_item) {
		    $cartItem = new timpanyCartItem();
		    $cartItem->setProduct($product);
		    $cartItem->setUser(self::$_instance->_user->getGuardUser());
	    }
	    $cartItem->setCount($items[$product->getSlug()]);
	    $cartItem->save();
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
    $this->_user->setAttribute(
      'cart_items',
      $items,
      timpanyCart::SESSION_NS
    );
    if (self::$_instance->_user->isAuthenticated()) {
      $cartItems = self::$_instance->_user->getGuardUser()->getCartItems();
      $cartItemRelation   = $cartItems->getRelation('timpanyCartItem');
      $cartItemCollection = $cartItemRelation['table']->findBySfGuardUserId(
                              self::$_instance->_user->getGuardUser()->getId()
                            );
      $remaining_items = $items;
      foreach ($cartItemCollection as $key=>$cartItem) {
      	if (false == isset($items[$cartItem->getProduct()->getSlug()]))
      	{
      		$cartItemCollection->remove($key);
      	} else {
          $cartItem->setCount($items[$cartItem->getProduct()->getSlug()]);
        }
        unset($remaining_items[$cartItem->getProduct()->getSlug()]);
      }
      foreach ($remaining_items as $product_slug=>$count) {
        $product = timpanyProductTable::getInstance()->findOneBySlug($product_slug);
        if (!$product) {
        	var_dump('Kein Produkt gefunden für Slug', $product_slug);exit;
        	continue;
        }
        $cartItem = new timpanyCartItem();
        $cartItem->setProductId($product->getId());
        $cartItem->setUser(self::$_instance->_user->getGuardUser());
	      $cartItem->setCount($items[$product_slug]);
	      $cartItemCollection->add($cartItem);
      }
      $cartItemCollection->save();
    }
  }
  
  /**
   * retrieve contained products
   * @return array (product_slug => count)
   */
  public function getContent()
  {
    if ($this->_user->hasAttribute('cart_items', timpanyCart::SESSION_NS)) {
      return $this->_user->getAttribute(
        'cart_items',
        null,
        timpanyCart::SESSION_NS
      );
    }
    return array();
  }
  
  /**
   * get count of a specific product
   * @param timpanyProductInterface $product
   * @return int Count of product
   */
  public function getCountOfProduct(timpanyProductInterface $product)
  {
    $content = $this->getContent();
    return $content[$product->getSlug()];
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
    $items = array();
    foreach ($this->getContent() as $product_slug=>$count)
    {
      $product = timpanyProductTable::getInstance()->findOneBySlug($product_slug);
      if ($product instanceof timpanyProduct)
      {
        $items[] = array(
          'count'     => $count,
          'product'   => $product,
          'net_sum'   => $count * $product->getNetPrice(),
          'gross_sum' => $count * $product->getGrossPrice(0)
        );
      } else {
        $this->removeItemBySlug($product_slug);
      }
    }
    return $items;
  }
  
  /**
   * get net sum
   * @return float
   */
  public function getNetSum()
  {
    $net_sum = 0;
    foreach ($this->getItems() as $item) {
      $net_sum += $item['net_sum'];
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
      $gross_sum += $item['gross_sum'];
    }
    return $gross_sum;
  }
  
  /**
   * remove item from cart
   * @param string $product_slug
   */
  public function removeItemBySlug($product_slug)
  {
    $items = $this->getContent();
    unset($items[$product_slug]);
    $this->setItems($items);
  }
  
  /**
   * get count of products
   * @return int
   */
  public function getProductCount()
  {
    $count = 0;
  	foreach ($this->getContent() as $item_count) {
  	  $count += $item_count;
  	}
  	return $count;
  }
  
  /**
   * get count of items
   * @return int
   */
  public function getItemCount()
  {
    return count($this->getContent());
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
