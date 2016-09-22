<?php

/**
 * Cobranza filter form base class.
 *
 * @package    subsecretaria
 * @subpackage filter
 * @author     Manuel De la Penna
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCobranzaFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'fecha'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'cobrador_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Cobrador'), 'add_empty' => true)),
      'lugar_cobro_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('LugarCobro'), 'add_empty' => true)),
      'cuota_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Cuota'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'fecha'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'cobrador_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Cobrador'), 'column' => 'id')),
      'lugar_cobro_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('LugarCobro'), 'column' => 'id')),
      'cuota_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Cuota'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('cobranza_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Cobranza';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'fecha'          => 'Date',
      'cobrador_id'    => 'ForeignKey',
      'lugar_cobro_id' => 'ForeignKey',
      'cuota_id'       => 'ForeignKey',
    );
  }
}
