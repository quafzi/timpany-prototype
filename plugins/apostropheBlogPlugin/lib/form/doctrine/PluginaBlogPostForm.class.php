<?php

/**
 * PluginaBlogPost form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginaBlogPostForm extends BaseaBlogPostForm
{
  protected $engine = 'aBlog';
  protected $categoryColumn = 'posts';

  public function setup()
  {
    parent::setup();
    
    $this->widgetSchema->setNameFormat('a_blog_item[%s]');
  }
}
