<?php

/**
 * timpanyProductToCart form class.
 *
 * @method timpanyProduct getObject() Returns the current form's model object
 *
 * @package    timpany
 * @subpackage form
 * @author     Thomas Kappel <quafzi@netextreme.de>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
class timpanyProductToCartForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'product_id'       => new sfWidgetFormInputHidden(),
      'count'            => new sfWidgetFormInputText(array(), array('class' => 'input_product_count', 'value' => 1)),
    ));

    $this->setValidators(array(
      'product_id'       => new sfValidatorDoctrineChoice(array('model' => 'timpanyProduct', 'required' => true)),
      'count'            => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('timpany_add_to_cart[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'timpanyCartItem';
  }

}
