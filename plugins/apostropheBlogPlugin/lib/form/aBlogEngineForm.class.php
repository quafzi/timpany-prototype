<?php

class aBlogEngineForm extends aPageForm
{
  public function configure()
  {
    $this->useFields();
    $q = Doctrine::getTable('aBlogPost')->addCategories();
    $this->setWidget('blog_categories_list', new sfWidgetFormDoctrineChoice(array('multiple' => true, 'query' => $q, 'model' => 'aBlogCategory')));
    $this->widgetSchema->setLabel('blog_categories_list', 'blog Categories');
    $this->widgetSchema->setHelp('blog_categories_list','(Defaults to All Cateogories)');
    $this->setValidator('blog_categories_list', new sfValidatorDoctrineChoice(array('multiple' => true, 'query' => $q, 'model' => 'aBlogCategory', 'required' => false)));
    $this->widgetSchema->setNameFormat('enginesettings[%s]');
    $this->widgetSchema->setFormFormatterName('aAdmin');
  }
}