<?php

// lib/form/CambiarPasswordForm.class.php
class CambiarPasswordForm extends sfForm {

    public function configure() {
        
        $this->setWidgets(array(
            'password_actual' => new sfWidgetFormInputPassword(),
            'password_nuevo' => new sfWidgetFormInputPassword(),
            'confirmar_password_nuevo' => new sfWidgetFormInputPassword(),
        ));
        $this->widgetSchema->setNameFormat('cambiarPassword[%s]');

        $this->setValidators(array(
            'password_actual' => new sfValidatorString(array('required' => true)),
            'password_nuevo' => new sfValidatorString(array('required' => true)),
            'confirmar_password_nuevo' => new sfValidatorString(array('required' => true)),
        ));
       
    }

}

?>