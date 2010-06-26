<?php

/**
 * Base actions for the aBlogPlugin aBlogAdmin module.
 * 
 * @package     apostropheBlogPlugin
 * @subpackage  aBlogAdmin
 * @author      Dan Ordille <dan@punkave.com>
 */
abstract class BaseaBlogAdminComponents extends sfComponents
{
	public function executeTagList()
  {
    $this->tags = TagTable::getAllTagNameWithCount(null, array('model' => 'aBlogPost', 'sort_by_popularity' => true, 'limit' => 10));
  }
}