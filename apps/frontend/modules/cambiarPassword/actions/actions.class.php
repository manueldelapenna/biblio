<?php

/**
 * cambiarPassword actions.
 *
 * @package    subsecretaria
 * @subpackage cambiarPassword
 * @author     Manuel De la Penna
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class cambiarPasswordActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
   $this->form = new CambiarPasswordForm();

    if ($request->isMethod('post')) {
        $this->form->bind($request->getParameter('cambiarPassword'));
        if ($this->form->isValid()) {
            
            $valores = $this->form->getValues();
            
            $passActualEncriptado = sfContext::getInstance()->getUser()->getGuardUser()->getPassword();
            
            $passActualIngresado = $valores['password_actual'];
            $passNuevoIngresado = $valores['password_nuevo'];
            $passNuevoIngresadoConfirmado = $valores['confirmar_password_nuevo'];
            
            $passActualIngresadoEncriptado = sha1(sfContext::getInstance()->getUser()->getGuardUser()->getSalt().$passActualIngresado);
            
            $request->setParameter('passActualEncriptado', $passActualEncriptado);
            $request->setParameter('passActualIngresadoEncriptado', $passActualIngresadoEncriptado);
            $request->setParameter('passNuevoIngresado', $passNuevoIngresado);
            $request->setParameter('passNuevoIngresadoConfirmado', $passNuevoIngresadoConfirmado);
            
            $this->forward('cambiarPassword','listo');
        }
    }
  }
  
  public function executeListo(sfWebRequest $request)
  {
      $passActualEncriptado = $request->getParameter('passActualEncriptado');
      $passActualIngresadoEncriptado = $request->getParameter('passActualIngresadoEncriptado');
      
      if ($passActualEncriptado == $passActualIngresadoEncriptado){
          $passNuevoIngresado = $request->getParameter('passNuevoIngresado');
          $passNuevoIngresadoConfirmado = $request->getParameter('passNuevoIngresadoConfirmado');
            
          if ($passNuevoIngresado == $passNuevoIngresadoConfirmado){
              $passNuevoIngresadoEncriptado = sha1(sfContext::getInstance()->getUser()->getGuardUser()->getSalt().$passNuevoIngresado);
              if ($passNuevoIngresadoEncriptado != $passActualIngresadoEncriptado){
                  $idUsuario = sfContext::getInstance()->getUser()->getGuardUser()->getId();
                  
                  $this->getUser()->getGuardUser()->setPassword($passNuevoIngresado);
                  $this->getUser()->getGuardUser()->save();
                  $mensaje = 'La contraseña fue modificada correctamente';
              }else{
                  $mensaje = 'La contraseña nueva es igual a la actual';
              }
          }else{
              $mensaje = 'La contraseña nueva y la confirmada no son iguales';
          }
      }else{
          $mensaje = 'La contraseña actual es incorrecta';          
      }
      $this->mensaje = $mensaje;
  }
 
    
}
