<?php
abstract class BaseaBlogSlotActions extends BaseaSlotActions
{
  protected $modelClass = 'aBlogPost';
  protected $formClass = 'aBlogSlotForm';

  public function preExecute()
  {
    parent::preExecute();
    if(sfConfig::get('app_aBlog_use_bundled_assets', true))
    {
      $this->getResponse()->addStylesheet('/apostropheBlogPlugin/css/aBlog.css');
      $this->getResponse()->addJavascript('/apostropheBlogPlugin/js/aBlog.js');
    }
  }

  public function executeEdit(sfRequest $request)
  {
    $this->editSetup();

    $value = $this->getRequestParameter('slot-form-' . $this->id);
    $this->form = new $this->formClass($this->id, array());
    $this->form->bind($value);
    if ($this->form->isValid())
    {
      // Serializes all of the values returned by the form into the 'value' column of the slot.
      // This is only one of many ways to save data in a slot. You can use custom columns,
      // including foreign key relationships (see schema.yml), or save a single text value
      // directly in 'value'. serialize() and unserialize() are very useful here and much
      // faster than extra columns

      $this->slot->setArrayValue($this->form->getValues());
      return $this->editSave();
    }
    else
    {
      // Makes $this->form available to the next iteration of the
      // edit view so that validation errors can be seen, if any
      return $this->editRetry();
    }
  }
}
