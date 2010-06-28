<?php

class BaseaMediaUploadImagesForm extends BaseForm
{
  public function configure()
  {
    for ($i = 0; ($i < aMediaTools::getOption('batch_max')); $i++)
    {
      $uploadImageForm = new aMediaUploadImageForm();
      $this->embedForm("item-$i", $uploadImageForm);
    }
    $this->widgetSchema->setNameFormat('a_media_items[%s]');
    $atLeastOne = new sfValidatorCallback(array('callback' => array($this, 'atLeastOne')));
    $atLeastOne->addMessage('at_least_one_image', 'You must upload at least one image.');
    $this->validatorSchema->setPostValidator($atLeastOne);
    $this->widgetSchema->setFormFormatterName('aAdmin');
    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('apostrophe');
    
  }
  // Thanks yet again to http://thatsquality.com/articles/can-the-symfony-forms-framework-be-domesticated-a-simple-todo-list
  public function atLeastOne($validator, $values, $args)
  {
    for ($i = 0; ($i < aMediaTools::getOption('batch_max')); $i++)
    {
      if (!is_null($values["item-$i"]['file']))
      {
        return $values;
      }
    }
    throw new sfValidatorError($validator, 'at_least_one_image');
  }
  
  // We don't include the form class in the token because we intentionally
  // switch form classes in midstream. You can't learn the session ID from
  // the cookie on your local box, so this is sufficient
  public function getCSRFToken($secret = null)
  {
    if (null === $secret)
    {
      $secret = self::$CSRFSecret;
    }

    return md5($secret.session_id());
  }    
  
}
