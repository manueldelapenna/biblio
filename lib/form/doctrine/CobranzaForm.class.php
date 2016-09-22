<?php

/**
 * Cobranza form.
 *
 * @package    subsecretaria
 * @subpackage form
 * @author     Manuel De la Penna
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CobranzaForm extends BaseCobranzaForm
{
  public function configure()
  {
	$cant_cuotas = array('1','2','3','4','5','6','7','8','9','10','11','12');
      $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'fecha'          => new sfWidgetFormJQueryDate(array('culture' => 'es', 'config' => "{showOn: 'button', changeYear: true}",'date_widget' => new sfWidgetFormDate(array('format' => '%day% - %month% - %year%')))),
      'cobrador_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Cobrador'), 'add_empty' => true)),
      'lugar_cobro_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('LugarCobro'), 'add_empty' => true)),
      'cuota_id'       => new sfWidgetFormInputHidden(),
	  'cantidad_cuotas'=> new sfWidgetFormChoice(array('choices' => array_combine($cant_cuotas, $cant_cuotas),))

    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'fecha'          => new sfValidatorDate(),
      'cobrador_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Cobrador'))),
      'lugar_cobro_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('LugarCobro'))),
      'cuota_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Cuota'))),
 	  'cantidad_cuotas'=> new sfValidatorChoice(array('choices' => array_combine($cant_cuotas, $cant_cuotas),))

    ));
    
    $this->getWidget('cuota_id')->setAttribute('hidden', 'hidden');
    $this->widgetSchema->setNameFormat('cobranza[%s]');
    $this->validatorSchema->setOption('allow_extra_fields', true);
    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

  }
}
