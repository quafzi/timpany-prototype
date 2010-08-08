<?php

/*
 * This file is part of the timpany package.
 */

/**
 * TimpanyHelper.
 *
 * @package    timpany
 * @subpackage helper
 * @author     Thomas Kappel <quafzi@netextreme.de>
 */

function add_to_cart(timpanyProduct $product, $name=null)
{
  if (is_null($name)) {
    $name = $product->getName();
  }
  return link_to($name, '@timpany_cart_add?product=' . $product->getSlug());
}

function load_timpany_assets()
{
  $response = sfContext::getInstance()->getResponse();

  sfContext::getInstance()->getConfiguration()->loadHelpers(
    array("Number", "I18N"));

  $response->addStylesheet('/timpanyPlugin/css/timpany.css');
  $response->addStylesheet('/timpanyPlugin/css/timpanyCart.css');
  $response->addStylesheet('/timpanyPlugin/css/timpanyProduct.css');
  $response->addStylesheet('/timpanyPlugin/css/timpanyCheckout.css');
}

load_timpany_assets();
