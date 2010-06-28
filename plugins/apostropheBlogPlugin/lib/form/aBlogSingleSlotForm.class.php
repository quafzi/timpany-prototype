<?php    
class aBlogSingleSlotForm extends BaseForm
{
  protected $id;
  protected $model = 'aBlogPost';
  public function __construct($id, $defaults = array(), $options = array(), $CSRFSecret = null)
  {
    $this->id = $id;
    parent::__construct($defaults, $options, $CSRFSecret);
  }
  
  public function configure()
  {
    // ADD YOUR FIELDS HERE
    
    // A simple example: a slot with a single 'text' field with a maximum length of 100 characters
    $this->widgetSchema['search'] = new sfWidgetFormInput(array(), array('autocomplete' => 'off'));
    $this->validatorSchema['search'] = new sfValidatorString();
    
    $this->widgetSchema['blog_item'] = new sfWidgetFormInputHidden();
    $this->validatorSchema['blog_item'] = new sfValidatorDoctrineChoice(array('model' => $this->model, 'multiple' => false));
    // Ensures unique IDs throughout the page
    $this->widgetSchema->setNameFormat('slot-form-' . $this->id . '[%s]');
    
    // You don't have to use our form formatter, but it makes things nice
    $this->widgetSchema->setFormFormatterName('aAdmin');
  }
}
