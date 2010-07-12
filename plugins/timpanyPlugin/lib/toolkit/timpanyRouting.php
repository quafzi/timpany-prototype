<?php

class timpanyRouting extends sfPatternRouting
{
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    // Removed a redundant check here: the plugin configuration class checks first before registering this handler
    $r = $event->getSubject();
    $r->prependRoute('timpany_product', 
      new sfRoute('/shop/:slug', 
        array('module' => 'timpany', 'action' => 'show'),
        array('slug' => '.*')));
  }
  
  static public function listenToRoutingAdminLoadConfigurationEvent(sfEvent $event)
  {
    $r = $event->getSubject();
    $enabledModules = array_flip(sfConfig::get('sf_enabled_modules', array()));
  }
}
