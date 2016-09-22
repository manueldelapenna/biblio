<?php

/**
 * Socio filter form base class.
 *
 * @package    subsecretaria
 * @subpackage filter
 * @author     Manuel De la Penna
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseSocioFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'numero_socio'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'nombre'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'apellido'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'dni'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'domicilio'          => new sfWidgetFormFilterInput(),
      'localidad'          => new sfWidgetFormFilterInput(),
      'codigo_postal'      => new sfWidgetFormFilterInput(),
      'telefono'           => new sfWidgetFormFilterInput(),
      'celular'            => new sfWidgetFormFilterInput(),
      'mail'               => new sfWidgetFormFilterInput(),
      'descuento_familiar' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'numero_socio'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'nombre'             => new sfValidatorPass(array('required' => false)),
      'apellido'           => new sfValidatorPass(array('required' => false)),
      'dni'                => new sfValidatorPass(array('required' => false)),
      'domicilio'          => new sfValidatorPass(array('required' => false)),
      'localidad'          => new sfValidatorPass(array('required' => false)),
      'codigo_postal'      => new sfValidatorPass(array('required' => false)),
      'telefono'           => new sfValidatorPass(array('required' => false)),
      'celular'            => new sfValidatorPass(array('required' => false)),
      'mail'               => new sfValidatorPass(array('required' => false)),
      'descuento_familiar' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('socio_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Socio';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'numero_socio'       => 'Number',
      'nombre'             => 'Text',
      'apellido'           => 'Text',
      'dni'                => 'Text',
      'domicilio'          => 'Text',
      'localidad'          => 'Text',
      'codigo_postal'      => 'Text',
      'telefono'           => 'Text',
      'celular'            => 'Text',
      'mail'               => 'Text',
      'descuento_familiar' => 'Boolean',
    );
  }
}
