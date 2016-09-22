<?php
// lib/form/ActualizarExcelForm.class.php
class RestaurarDatosForm extends sfForm
{
  
  public function configure()
  {

    $this->setWidgets(array(
      'datos_respaldados' => new sfWidgetFormInputFile(),
    ));
    
      $this->widgetSchema->setNameFormat('restaurarDatos[%s]');
    
      $this->setValidators(array(
      'datos_respaldados' => new sfValidatorFile(array('required' => true,'mime_types' => array('text/plain'))),
      ));

  }
}
