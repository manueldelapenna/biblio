<?php

/**
 * Socio form base class.
 *
 * @method Socio getObject() Returns the current form's model object
 *
 * @package    subsecretaria
 * @subpackage form
 * @author     Manuel De la Penna
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseSocioForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'numero_socio'       => new sfWidgetFormInputText(),
      'nombre'             => new sfWidgetFormInputText(),
      'apellido'           => new sfWidgetFormInputText(),
      'dni'                => new sfWidgetFormInputText(),
      'domicilio'          => new sfWidgetFormInputText(),
      'localidad'          => new sfWidgetFormInputText(),
      'codigo_postal'      => new sfWidgetFormInputText(),
      'telefono'           => new sfWidgetFormInputText(),
      'celular'            => new sfWidgetFormInputText(),
      'mail'               => new sfWidgetFormInputText(),
      'descuento_familiar' => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'numero_socio'       => new sfValidatorInteger(),
      'nombre'             => new sfValidatorString(array('max_length' => 200)),
      'apellido'           => new sfValidatorString(array('max_length' => 200)),
      'dni'                => new sfValidatorString(array('max_length' => 100)),
      'domicilio'          => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'localidad'          => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'codigo_postal'      => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'telefono'           => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'celular'            => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'mail'               => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'descuento_familiar' => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Socio', 'column' => array('numero_socio')))
    );

    $this->widgetSchema->setNameFormat('socio[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Socio';
  }

}
