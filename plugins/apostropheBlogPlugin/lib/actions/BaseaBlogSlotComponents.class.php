<?php
abstract class BaseaBlogSlotComponents extends BaseaSlotComponents
{
  protected $modelClass = 'aBlogPost';
  protected $formClass = 'aBlogSlotForm';

  public function setup()
  {
    parent::setup();
    if(sfConfig::get('app_aBlog_use_bundled_assets', true))
    {
      $this->getResponse()->addStylesheet('/apostropheBlogPlugin/css/aBlog.css');
      $this->getResponse()->addJavascript('/apostropheBlogPlugin/js/aBlog.js');
    }
  }

  public function executeEditView()
  {
    // Must be at the start of both view components
    $this->setup();
    
    // Careful, don't clobber a form object provided to us with validation errors
    // from an earlier pass
    if (!isset($this->form))
    {
      $this->form = new $this->formClass($this->id, $this->slot->getArrayValue());
    }
  }
  public function executeNormalView()
  {
    $this->setup();
    $this->values = $this->slot->getArrayValue();
    $q = Doctrine::getTable($this->modelClass)->createQuery()
      ->leftJoin($this->modelClass.'.Author a')
      ->leftJoin($this->modelClass.'.Categories c');
    Doctrine::getTable($this->modelClass)->addPublished($q);
    if (isset($this->values['categories_list']) && count($this->values['categories_list']) > 0)
    {
      $q->andWhereIn('c.id', $this->values['categories_list']);
    }
    if (isset($this->values['tags_list']) && strlen($this->values['tags_list']) > 0)
    {
      PluginTagTable::getObjectTaggedWithQuery($q->getRootAlias(), $this->values['tags_list'], $q, array('nb_common_tags' => 1));
    }
    if (isset($this->values['count']))
    {
      $q->limit($this->values['count']);
    }
    $q->orderBy('published_at desc');
    
		$this->options['slideshowOptions']['width']	= ((isset($this->options['slideshowOptions']['width']))? $this->options['slideshowOptions']['width']:100);
		$this->options['slideshowOptions']['height'] = ((isset($this->options['slideshowOptions']['height']))? $this->options['slideshowOptions']['height']:100);
		$this->options['slideshowOptions']['resizeType'] = ((isset($this->options['slideshowOptions']['resizeType']))? $this->options['slideshowOptions']['resizeType']:'c');
		
    $this->options['excerptLength'] = $this->getOption('excerptLength', 100);
    $this->options['maxImages'] = $this->getOption('maxImages', 1);

    $this->aBlogPosts = $q->execute();
    aBlogItemTable::populatePages($this->aBlogPosts);
  }
}
