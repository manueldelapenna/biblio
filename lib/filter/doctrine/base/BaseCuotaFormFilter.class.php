<?php

/**
 * Cuota filter form base class.
 *
 * @package    subsecretaria
 * @subpackage filter
 * @author     Manuel De la Penna
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCuotaFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'socio_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Socio'), 'add_empty' => true)),
      'mes'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'anio'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'monto'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'pagada'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'socio_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Socio'), 'column' => 'id')),
      'mes'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'anio'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'monto'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'pagada'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('cuota_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Cuota';
  }

  public function getFields()
  {
    return array(
      'id'       => 'Number',
      'socio_id' => 'ForeignKey',
      'mes'      => 'Number',
      'anio'     => 'Number',
      'monto'    => 'Number',
      'pagada'   => 'Boolean',
    );
  }
}
