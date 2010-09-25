<?php

/**
 * timpanyCart tests.
 */
include dirname(__FILE__).'/../../../../../test/bootstrap/unit.php';

$t = new lime_test(24);

$configuration = ProjectConfiguration::getApplicationConfiguration( 'frontend', 'test', true);
new sfDatabaseManager($configuration);

Doctrine_Core::loadData(dirname(__FILE__).'/../../fixtures');

$_SERVER['session_id'] = 'test_guest';
$guestDispatcher = new sfEventDispatcher();
$guestSessionPath = sys_get_temp_dir().DIRECTORY_SEPARATOR.'symfony_tests_'.rand(1, 999);
$guestStorage = new sfSessionTestStorage(array('session_path' => $guestSessionPath));
$guestUser = new myUser($guestDispatcher, $guestStorage);

$product_1 = timpanyProductTable::getInstance()->findOneById(1);
$product_2 = timpanyProductTable::getInstance()->findOneById(2);

$guestCart = timpanyCart::getInstance($guestUser);
$t->is($guestCart->isEmpty(), true, 'cart is empty');

$t->comment('Put one item into cart.');
$guestCart->addProduct($product_1);
$t->is($guestCart->isEmpty(), false, 'cart is not empty');
$t->is($guestCart->getItemCount(), 1, '1 item in cart');
$t->is($guestCart->getProductCount(), 1, '1 product in cart');
$t->is($guestCart->getNetSum(), 0.84, 'cart net sum is correct');
$t->is($guestCart->getGrossSum('de'), 0.9, 'cart gross sum is correct');

$t->comment('Put second item into cart.');
$guestCart->addProduct($product_2);
$t->is($guestCart->isEmpty(), false, 'cart is not empty');
$t->is($guestCart->getItemCount(), 2, '2 items in cart');
$t->is($guestCart->getProductCount(), 2, '2 products in cart');
$t->is($guestCart->getNetSum(), 0.84, 'cart net sum is correct');
$t->is($guestCart->getGrossSum('de'), 0.9, 'cart gross sum is correct');

$t->comment('Increase count of first cart item by 9.');
$guestCart->addProduct($product_1, 9);
$t->is($guestCart->isEmpty(), false, 'cart is not empty');
$t->is($guestCart->getItemCount(), 2, '2 items in cart');
$t->is($guestCart->getProductCount(), 11, '11 products in cart');
$t->is($guestCart->getNetSum(), 8.4, 'cart net sum is correct');
$t->is($guestCart->getGrossSum('de'), 9, 'cart gross sum is correct');

$t->comment('Remove first cart item from cart.');
$guestCart->removeItem($product_1->getIdentifier());
$t->is($guestCart->isEmpty(), false, 'cart is not empty');
$t->is($guestCart->getItemCount(), 1, '1 item in cart');
$t->is($guestCart->getProductCount(), 1, '1 product in cart');
$t->is($guestCart->getNetSum(), 0, 'cart net sum is correct');

$t->comment('Remove second cart item from cart.');
$guestCart->removeItem($product_2->getIdentifier());
$t->is($guestCart->isEmpty(), true, 'cart is empty again');
$t->is($guestCart->getItemCount(), 0, 'no item in cart');
$t->is($guestCart->getProductCount(), 0, 'no product in cart');
$t->is($guestCart->getNetSum(), 0, 'cart net sum is correct');

$t->comment('Now let\'s have a look for logged in users!.');
$_SERVER['session_id'] = 'test_user';
$dispatcher = new sfEventDispatcher();
$sessionPath = sys_get_temp_dir().DIRECTORY_SEPARATOR.'symfony_tests_'.rand(1, 999);
$storage = new sfSessionTestStorage(array('session_path' => $sessionPath));

$logged_in_user = new myUser($dispatcher, $storage);
$sfGuardUser = sfGuardUserTable::getInstance()->findOneById(1);
$logged_in_user->signIn($sfGuardUser);
