<?php

/**
 * aWidgetFormStaticText presents static text in a widget. This should always be paired with an sfValidatorPass validator.
 *
 * These widgets are used to provide detailed information interleaved with widgets without the need to template out the entire form.
 *
 * Sample usage:
 *
 * $housingDetails = $this->event->getHousingDetails();
 * if ($housingDetails)
 * {
 *   $this->setWidget('housing_details', new aWidgetFormStaticText($housingDetails));
 *   $this->setValidator('housing_details', new sfValidatorPass());
 *   $this->widgetSchema->moveField('housing_details', sfWidgetFormSchema::BEFORE, 'housing');
 * }
 *
 */
class aWidgetFormStaticText extends sfWidgetFormInput
{
  protected $label;
  
  // The label is the only reason this widget exists, so don't bother using an option for it,
  // the constructor is more succinct
  public function __construct($label)
  {
    $this->label = $label;
    parent::__construct();
  }
  
  /**
   * @param  string $name        The element name
   * @param  string $value       The value displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes (the name attribute will be removed)
   * @param  array  $errors      An array of errors for the field (ignored here)
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    unset($attributes['name']);
    // Always ignore the passed value, which will vary if there are multiple validation passes.
    // We just want to output the label we were created with.
    return $this->renderContentTag('div', htmlspecialchars($this->label), $attributes);
  }
}
