<?php

/**
 * Cobranza form base class.
 *
 * @method Cobranza getObject() Returns the current form's model object
 *
 * @package    subsecretaria
 * @subpackage form
 * @author     Manuel De la Penna
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCobranzaForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'fecha'          => new sfWidgetFormDate(),
      'cobrador_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Cobrador'), 'add_empty' => false)),
      'lugar_cobro_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('LugarCobro'), 'add_empty' => false)),
      'cuota_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Cuota'), 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'fecha'          => new sfValidatorDate(),
      'cobrador_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Cobrador'))),
      'lugar_cobro_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('LugarCobro'))),
      'cuota_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Cuota'))),
    ));

    $this->widgetSchema->setNameFormat('cobranza[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Cobranza';
  }

}
