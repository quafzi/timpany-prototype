<?php
/**
 */
class PluginaBlogPostTable extends aBlogItemTable
{
  protected $categoryColumn = 'posts';

  public static function getInstance()
  {
    return Doctrine_Core::getTable('aBlogPost');
  }

}