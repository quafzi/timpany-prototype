<?php

class BaseaPDFSlotComponents extends BaseaSlotComponents
{
  public function executeEditView()
  {
    // Just a stub, we don't really utilize this for this slot type,
    // we have an external editor instead
    $this->setup();
  }
  public function executeNormalView()
  {
    $this->setup();
    $this->constraints = $this->getOption('constraints', array());
    $this->width = $this->getOption('width', 100);
    $this->height = $this->getOption('height', 220);
    $this->resizeType = $this->getOption('resizeType', 's');
    $this->flexHeight = $this->getOption('flexHeight', true);
    $this->defaultImage = $this->getOption('defaultImage');     
    $this->title = $this->getOption('title', true);
    $this->pdfPreview = $this->getOption('pdfPreview', false);
    $this->description = $this->getOption('description', true);
    
    // Behave well if it's not set yet!
    if (!count($this->slot->MediaItems))
    {
      $this->item = false;
      $this->itemId = false;
    }
    else
    {
      $this->item = $this->slot->MediaItems[0];
      $this->itemId = $this->item->id;
      $this->dimensions = aDimensions::constrain(
        $this->item->width, 
        $this->item->height,
        $this->item->format, 
        array("width" => $this->width,
          "height" => $this->flexHeight ? false : $this->height,
          "resizeType" => $this->resizeType));
      $this->embed = $this->item->getEmbedCode('_WIDTH_', '_HEIGHT_', '_c-OR-s_', '_FORMAT_', false);
    }
  }
}

