<?php

/**
 * Base Components for the apostropheBlogPlugin aEvent module.
 * 
 * @package     apostropheBlogPlugin
 * @subpackage  aEvent
 * @author      Dan Ordille
 */
abstract class BaseaEventComponents extends sfComponents
{
  protected $modelClass = 'aEvent';

  public function setup()
  {
    parent::setup();
    if(sfConfig::get('app_aBlog_use_bundled_assets', true))
    {
      $this->getResponse()->addStylesheet('/apostropheBlogPlugin/css/aBlog.css');
      $this->getResponse()->addJavascript('/apostropheBlogPlugin/js/aBlog.js');
    }
  }
  
  public function executeSidebar()
  {
    if ($this->getRequestParameter('tag'))
    {
      $this->tag = TagTable::findOrCreateByTagname($this->getRequestParameter('tag'));
    }
    
    if(!count($this->categories) || is_null($this->categories))
    {
      $this->categories = Doctrine::getTable('aBlogCategory')
        ->createQuery('c')
        ->orderBy('c.name')
        ->where('c.events = ?', true)
        ->execute();
    }

    $categoryIds = array();
    foreach($this->categories as $category)
    {
      $categoryIds[] = $category['id'];
    }

    $this->popular = Doctrine::getTable('aBlogCategory')->getTagsForCategories($categoryIds, 'aEvent', true, 10);
    $this->tags = Doctrine::getTable('aBlogCategory')->getTagsForCategories($categoryIds, 'aEvent');

    if($this->reset == true)
    {
      $this->params['cat'] = array();
      $this->params['tag'] = array();
    }
  }

}
