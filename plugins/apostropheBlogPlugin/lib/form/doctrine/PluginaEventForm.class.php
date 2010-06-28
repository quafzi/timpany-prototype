<?php

/**
 * PluginaEvent form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginaEventForm extends BaseaEventForm
{

  protected $engine = 'aEvent';
  protected $categoryColumn = 'events';

  public function setup()
  {
    parent::setup();

    $this->setWidget('start_date', new sfWidgetFormJQueryDateTime(
			array('date' => array('image' => '/apostrophePlugin/images/a-icon-datepicker.png'))
		));
    
    $this->setValidator('start_date', new sfValidatorDateTime(
      array(
        'required' => true,
      )));

    $this->setWidget('end_date', new sfWidgetFormJQueryDateTime(
			array('date' => array('image' => '/apostrophePlugin/images/a-icon-datepicker.png'))
		));

    $this->setValidator('end_date', new sfValidatorDateTime(
      array(
        'required' => true,
      )));

    if($this->getObject()->getStartDate() == $this->getObject()->getEndDate())
    {
      $this->getWidget('start_date')->addOption('with_time', false);
      $this->getWidget('end_date')->addOption('with_time', false);
    }

    $this->getWidgetSchema()->setDefault('start_date', date('Y/m/d'));
    $this->getWidgetSchema()->setDefault('end_date', date('Y/m/d'));

    $this->widgetSchema->setNameFormat('a_blog_item[%s]');
  }
}
