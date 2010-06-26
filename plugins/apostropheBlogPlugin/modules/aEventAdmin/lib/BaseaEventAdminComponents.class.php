<?php

/**
 * Base actions for the aEventPlugin aEventAdmin module.
 * 
 * @package     apostropheBlogPlugin
 * @subpackage  aEventAdmin
 * @author      Dan Ordille <dan@punkave.com
 */
abstract class BaseaEventAdminComponents extends sfComponents
{
  public function executeTagList()
  {
    $this->tags = TagTable::getAllTagNameWithCount(null, array('model' => 'aEvent', 'sort_by_popularity' => true, 'limit' => 10));
  }
}