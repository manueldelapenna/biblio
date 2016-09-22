<?php

// lib/form/AdelantarCuotasForm.class.php
class AdelantarCuotasForm extends sfForm {

  public function configure()
  {
	$cant_cuotas = array('1','2','3','4','5','6','7','8','9','10','11','12');
    
	$this->setWidgets(array(
      'cantidad'                       => new sfWidgetFormChoice(array('choices' => array_combine($cant_cuotas, $cant_cuotas))),
      'cobrador'                       => new sfWidgetFormDoctrineChoice(array('model' => 'Cobrador', 'add_empty' => true)),
      'lugar_cobro'                    => new sfWidgetFormDoctrineChoice(array('model' => 'LugarCobro', 'add_empty' => true)),
      'fecha'                          => new sfWidgetFormJQueryDate(array('culture' => 'es', 'config' => "{showOn: 'button', changeYear: true}",'date_widget' => new sfWidgetFormDate(array('format' => '%day% - %month% - %year%')))),
    ));

    $this->setValidators(array(
      'cantidad'                       => new sfValidatorChoice(array('choices' => array_combine($cant_cuotas, $cant_cuotas),)),
      'cobrador'                       => new sfValidatorDoctrineChoice(array('model' => 'Cobrador', 'required' => true)),
      'lugar_cobro'                    => new sfValidatorDoctrineChoice(array('model' => 'LugarCobro', 'required' => true)),
      'fecha'                          => new sfValidatorDate(array('required' => true)),
    ));

	$this->setDefaults(array(
    	'fecha' => date('Y-m-d'),
    ));
    $this->widgetSchema->setNameFormat('adelantarCuotas[%s]');

  }

}

?>