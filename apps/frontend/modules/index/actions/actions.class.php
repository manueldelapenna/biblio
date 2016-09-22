<?php
/**
 * index actions.
 *
 * @package    subsecretaria
 * @subpackage index
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class indexActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  { 
      $mesActual = date('m');
      $anioActual = date('Y');
      
      $q = Doctrine_Query::create()
                ->select('*')
                ->from('CobranzaAutomatica ca')
                ->orderBy('ca.anio')
                ->addOrderBy('ca.mes');
       
      $cobranzaAutomatica = $q->execute();
      $cant = count($cobranzaAutomatica);
           
      if($cant>0){
          $ultimaCobranza=$cobranzaAutomatica[$cant-1];
          $anioUltima=$ultimaCobranza->getAnio();
          $mesUltima=$ultimaCobranza->getMes();
          while($anioUltima<$anioActual){
              $nuevoAnio=$anioUltima;
              while($mesUltima<12){
                  $nuevoMes=$mesUltima+1;
                  $cobranzaAutomatica = new CobranzaAutomatica();
                  $cobranzaAutomatica->setMes($nuevoMes);
                  $cobranzaAutomatica->setAnio($nuevoAnio);
                  $cobranzaAutomatica->setGenerada(false);
                  $cobranzaAutomatica->save();
                  $mesUltima=$nuevoMes;
              }
              $mesUltima=0;
              $anioUltima++;
          }

          while($anioUltima==$anioActual){
              $nuevoAnio=$anioUltima;
              while($mesUltima<$mesActual){
                  $nuevoMes=$mesUltima+1;
                  $cobranzaAutomatica = new CobranzaAutomatica();
                  $cobranzaAutomatica->setMes($nuevoMes);
                  $cobranzaAutomatica->setAnio($nuevoAnio);
                  $cobranzaAutomatica->setGenerada(false);
                  $cobranzaAutomatica->save();
                  $mesUltima=$nuevoMes;
              }
              $anioUltima++;
          }
      }else{
          $cobranzaAutomatica = new CobranzaAutomatica();
          $cobranzaAutomatica->setMes($mesActual);
          $cobranzaAutomatica->setAnio($anioActual);
          $cobranzaAutomatica->setGenerada(false);
          $cobranzaAutomatica->save();
          
      }
      
      $q = Doctrine_Query::create()
                ->select('*')
                ->from('CobranzaAutomatica ca')
                ->where('ca.generada =?',false);
       
      $cobranzasSinGenerar = $q->execute();
      $this->cantCobranzasPorGenerar = count($cobranzasSinGenerar);
  
  }
}
