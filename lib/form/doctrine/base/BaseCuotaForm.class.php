<?php

/**
 * Cuota form base class.
 *
 * @method Cuota getObject() Returns the current form's model object
 *
 * @package    subsecretaria
 * @subpackage form
 * @author     Manuel De la Penna
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCuotaForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'       => new sfWidgetFormInputHidden(),
      'socio_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Socio'), 'add_empty' => false)),
      'mes'      => new sfWidgetFormInputText(),
      'anio'     => new sfWidgetFormInputText(),
      'monto'    => new sfWidgetFormInputText(),
      'pagada'   => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'       => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'socio_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Socio'))),
      'mes'      => new sfValidatorInteger(),
      'anio'     => new sfValidatorInteger(),
      'monto'    => new sfValidatorInteger(),
      'pagada'   => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('cuota[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Cuota';
  }

}
