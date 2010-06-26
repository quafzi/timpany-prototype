<?php
abstract class BaseaBlogSingleSlotComponents extends BaseaSlotComponents
{
  protected $modelClass = 'aBlogPost';
  protected $formClass = 'aBlogSingleSlotForm';

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
    if(isset($this->values['blog_item']))
    {
      $this->aBlogItem = Doctrine::getTable($this->modelClass)->findOneBy('id', $this->values['blog_item']);
      aBlogItemTable::populatePages(array($this->aBlogItem));
    }
    $this->options['word_count'] = $this->getOption('word_count', 100);

        if(!isset($this->options['slideshowOptions']))
		{ // If no slideshow options are set, use the defaults
	    $this->options['slideshowOptions'] = array('width' => 100, 'height' => 100, 'resizeType' => 'c');
		}
		else
		{ // If -some- slideshow options are set, make sure to include defaults where not specified
	    $this->options['slideshowOptions'] = array(
				'width' => ((isset($this->options['slideshowOptions']['width']))? $this->options['slideshowOptions']['width']:100),
				'height' => ((isset($this->options['slideshowOptions']['height']))? $this->options['slideshowOptions']['height']:100),
				'resizeType' => ((isset($this->options['slideshowOptions']['resizeType']))? $this->options['slideshowOptions']['resizeType']:'c'),
			);
		}

    $this->options['excerptLength'] = $this->getOption('excerptLength', 200);
    $this->options['maxImages'] = $this->getOption('maxImages', 1);
  } 
}