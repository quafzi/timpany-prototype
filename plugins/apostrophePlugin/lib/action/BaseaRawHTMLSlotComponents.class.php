<?php

class BaseaRawHTMLSlotComponents extends BaseaSlotComponents
{
  
  // Very raw, very unfiltered, that's the point. Don't use this
  // slot in designs where you can avoid it. But sometimes clients
  // need to paste foreign HTML for Constant Contact forms
  // and the like. 
  
  // For foreign media embeds, consider apostrophePlugin and
  // apostrophePlugin instead, in particular the optional
  // embed feature which allows carefully filtered embed codes
  // for foreign Flash players etc. It doesn't work everywhere
  // but it's safer than this slot.
  
  // If safemode=1 is in the query string this slot does not render.
  // A good failsafe if the client pastes bad markup/bad styles that
  // break the rendering of the page to the point where you can't
  // easily edit it.
  
  public function executeEditView()
  {
    $this->setup();
    // Careful, don't clobber a form object provided to us with validation errors
    // from an earlier pass
    if (!isset($this->form))
    {
      $this->form = new aRawHTMLForm($this->id);
      $this->form->setDefault('value', $this->slot->value);
    }
  }
  public function executeNormalView()
  {
    $this->setup();
  }
}
