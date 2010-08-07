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
    }
    return self::$_instance;
  }
  protected function __construct() {}
  private function __clone() {}
  
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
  /*
  public function getProducts()
  {
    $product_ids = array_keys($this->getContent());
    return timpanyProductTable::getInstance()->findById($product_ids);
  }*/
  
  public function getCountOfProduct(timpanyProductInterface $product)
  {
    $content = $this->getContent();
    return $content[$product->getSlug()];
  }
  
  public function getItems()
  {
    $items = array();
    foreach ($this->getContent() as $product_slug=>$count)
    {
      $product = timpanyProductTable::getInstance()->findOneBySlug($product_slug);
      $items[] = array(
        'count'     => $count,
        'product'   => $product,
        'net_sum'   => $product->getNetPrice(),
        'gross_sum' => $product->getGrossPrice(0)
      );
    }
    return $items;
  }
  
  public function getNetSum()
  {
    $net_sum = 0;
    foreach ($this->getItems() as $item) {
      $net_sum += $item['net_sum'];
    }
    return $net_sum;
  }
  
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
  
  public function getItemCount()
  {
    return count($this->getContent());
  }
}
