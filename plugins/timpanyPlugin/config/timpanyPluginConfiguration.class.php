<?php

/**
 * timpanyPlugin configuration.
 * 
 * @package     timpanyPlugin
 * @subpackage  config
 */
class timpanyPluginConfiguration extends sfPluginConfiguration
{
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    // These were merged in from the separate plugins. TODO: clean up a little.
    
    if (sfConfig::get('app_timpany_routes_register', true) && in_array('timpany', sfConfig::get('sf_enabled_modules', array())))
    {
      $this->dispatcher->connect('routing.load_configuration', array('timpanyRouting', 'listenToRoutingLoadConfigurationEvent'));
    }

    if (sfConfig::get('app_timpany_admin_routes_register', true))
    {
      $this->dispatcher->connect('routing.load_configuration', array('timpanyRouting', 'listenToRoutingAdminLoadConfigurationEvent'));
    }
  }
}
