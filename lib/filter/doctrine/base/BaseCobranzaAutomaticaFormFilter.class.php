<?php

/**
 * CobranzaAutomatica filter form base class.
 *
 * @package    subsecretaria
 * @subpackage filter
 * @author     Manuel De la Penna
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCobranzaAutomaticaFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'mes'                   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'anio'                  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'valor_cuota'           => new sfWidgetFormFilterInput(),
      'valor_cuota_descuento' => new sfWidgetFormFilterInput(),
      'generada'              => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'mes'                   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'anio'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'valor_cuota'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'valor_cuota_descuento' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'generada'              => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('cobranza_automatica_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CobranzaAutomatica';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'mes'                   => 'Number',
      'anio'                  => 'Number',
      'valor_cuota'           => 'Number',
      'valor_cuota_descuento' => 'Number',
      'generada'              => 'Boolean',
    );
  }
}
