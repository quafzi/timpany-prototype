<?php

require_once dirname(__FILE__) . '/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    // We do this here because we chose to put Zend in lib/vendor/Zend.
    // If it is installed system-wide then this isn't necessary to
    // enable Zend Search
    set_include_path(sfConfig::get('sf_lib_dir') . '/vendor' . PATH_SEPARATOR . get_include_path());
    // for compatibility / remove and enable only the plugins you want

    $this->enablePlugins(array(
      'sfDoctrinePlugin',
      'apostrophePlugin',
      'apostropheBlogPlugin',
      'sfJqueryReloadedPlugin',
      'sfSyncContentPlugin',
      'sfDoctrineActAsTaggablePlugin',
      'sfTaskExtraPlugin',
      'sfDoctrineGuardPlugin',
      'sfWebBrowserPlugin',
      'sfFeed2Plugin',
      'timpanyPlugin',
      'jmsPaymentPlugin'
    ));
  }
  
  public function setupPlugins()
  {
    $this->pluginConfigurations['timpanyPlugin']->connectTests();
  }
}
