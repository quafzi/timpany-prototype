<?php

class frontendConfiguration extends sfApplicationConfiguration
{
  public function setup()
  {
    parent::setup();
    $this->enablePlugins('apostrophePlugin');
    $this->enablePlugins('apostropheBlogPlugin');
  }
}
