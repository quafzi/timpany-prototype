<?php

/**
 * PluginaBlogCategory form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginaBlogCategoryForm extends BaseaBlogCategoryForm
{
  public function setup()
  {
    parent::setup();
    
    $this->useFields(array('name', 'users_list', 'posts', 'events'));
  }

}
