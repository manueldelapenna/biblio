<?php

/**
 * CobranzaAutomatica form base class.
 *
 * @method CobranzaAutomatica getObject() Returns the current form's model object
 *
 * @package    subsecretaria
 * @subpackage form
 * @author     Manuel De la Penna
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCobranzaAutomaticaForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'mes'                   => new sfWidgetFormInputText(),
      'anio'                  => new sfWidgetFormInputText(),
      'valor_cuota'           => new sfWidgetFormInputText(),
      'valor_cuota_descuento' => new sfWidgetFormInputText(),
      'generada'              => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'mes'                   => new sfValidatorInteger(),
      'anio'                  => new sfValidatorInteger(),
      'valor_cuota'           => new sfValidatorInteger(array('required' => false)),
      'valor_cuota_descuento' => new sfValidatorInteger(array('required' => false)),
      'generada'              => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('cobranza_automatica[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CobranzaAutomatica';
  }

}
