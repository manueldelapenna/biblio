<?php

/**
 * backup actions.
 *
 * @package    subsecretaria
 * @subpackage backup
 * @author     Manuel De la Penna
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class backupActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function prepareDownload($outFilename)
  { 
    $this->getResponse()->clearHttpHeaders();
    $this->getResponse()->addCacheControlHttpHeader('Cache-control','must-revalidate, post-check=0, pre-check=0');
    $this->getResponse()->setContentType('application/octet-stream',TRUE);    
    $this->getResponse()->setHttpHeader('Content-Transfer-Encoding', 'binary', TRUE);
    $this->getResponse()->setHttpHeader('Content-Disposition','attachment; filename='.$outFilename, TRUE);
    $this->getResponse()->sendHttpHeaders();  
  }

  public function executeDownload($url, $filename)
  {
    $this->prepareDownload($filename);
    readfile($url);
    return sfView::NONE;

  }
    
    
    
  public function executeIndex(sfWebRequest $request)
  {

  }
  
  public function executeGenerarBackup(sfWebRequest $request)
  {
    $fecha = time();
    $anio = date('Y',$fecha);
    $mes = date('m',$fecha);
    $dia = date('d',$fecha);
    $nombre = sfConfig::get('sf_upload_dir').'/backup_base_datos_'.$anio.'_'. $mes .'_'. $dia.'.sql';
    $nombreSinPath = 'backup_base_datos_'.$anio.'_'. $mes .'_'. $dia.'.sql';
    $command = 'd:\xampp\mysql\bin\mysqldump --default-character-set=latin1 -uroot -pmanuel31042332 biblio > '.$nombre;
    $this->output = system($command, $resultado);
    if ($resultado != ''){// hay error
        $this->nombre = $nombre;
        if (file_exists ($nombre)){ //se borra el archivo si se generó mal
           unlink($nombre);
        }
        return sfView::ERROR;
    }else{// no hay error
        $this->executeDownload($nombre, $nombreSinPath);
        $this->setLayout(false);
        return sfView::NONE;
        unlink($nombre);
    }

  }
  
  public function executeRestaurarDatos(sfWebRequest $request)
  {
    $this->formulario = new RestaurarDatosForm();
    
    if ($request->isMethod('post'))
    {
      $this->formulario->bind($request->getParameter('restaurarDatos'), $request->getFiles('restaurarDatos'));
      if ($this->formulario->isValid()){

          $archivo =  $this->formulario->getValue('datos_respaldados');
          $nombreArchivo = $archivo->getOriginalName();
          $archivo->save(sfConfig::get('sf_upload_dir').'/'.$nombreArchivo);
          /*
          $command = 'd:\xampp\mysql\bin\mysql -uroot -pmanuel31042332';
          system($command);
          $command = 'drop database subsecretaria';
          system($command);
          */
          $command = 'd:\xampp\mysql\bin\mysql -uroot -pmanuel31042332 biblio < '. sfConfig::get('sf_upload_dir').'/'.$nombreArchivo;

          system($command, $resultado);
          if ($resultado != '0' ){// hay error
              return sfView::ERROR;
          }else{
              unlink(sfConfig::get('sf_upload_dir').'/'.$nombreArchivo);
              $this->redirect('backup/restauracionLista');
          }
      }
    }
  }
    
  public function executeRestauracionLista(sfWebRequest $request)
  {
      
  }
      
  
}
