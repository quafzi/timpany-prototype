<?php

/**
 * PlugintimpanyProduct tests.
 */
include dirname(__FILE__).'/../../../../../../test/bootstrap/unit.php';

$databaseManager = new sfDatabaseManager($configuration);
 
Doctrine_Core::loadData(sfConfig::get('sf_data_dir').'/fixtures');

$t = new lime_test(2);

$product_1 = timpanyProductTable::getInstance()->findOneById(1);
$t->is($product_1->getNetPrice(), 0.84, 'got expected net price');
$t->is(round($product_1->getGrossPrice('de'), 2), 0.9, 'gross price is correct');