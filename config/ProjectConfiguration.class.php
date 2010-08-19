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

    $this->enablePlugins('sfDoctrinePlugin');
    $this->enablePlugins('sfJqueryReloadedPlugin');
    $this->enablePlugins('sfDoctrineGuardPlugin');
    $this->enablePlugins('sfDoctrineActAsTaggablePlugin');
    $this->enablePlugins('sfWebBrowserPlugin');
    $this->enablePlugins('sfFeed2Plugin');
    $this->enablePlugins('sfSyncContentPlugin');
    $this->enablePlugins('jmsPaymentPlugin');
    $this->enablePlugins('timpanyPlugin');
  }
}
