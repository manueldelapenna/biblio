<?php
  
require_once dirname(__FILE__).'/../lib/cobranzaAutomaticaGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/cobranzaAutomaticaGeneratorHelper.class.php';

/**
 * cobranzaAutomatica actions.
 *
 * @package    subsecretaria
 * @subpackage cobranzaAutomatica
 * @author     Manuel De la Penna
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class cobranzaAutomaticaActions extends autoCobranzaAutomaticaActions
{
  public function executeListGenerar(sfWebRequest $request)
  {
    
    $this->cobranzaAutomatica=$this->getRoute()->getObject();
    
    
  }
  public function executeGenerarListo(sfWebRequest $request)
  {
    $cobranzaAutomticaId = $request->getParameter('cobranzaAutomaticaId');
    $valor = $request->getParameter('valor');
    $valorDescuento = $request->getParameter('valorDescuento');
  
    
    $q = Doctrine_Query::create()
                ->select('*')
                ->from('CobranzaAutomatica ca')
                ->where('ca.id =?',$cobranzaAutomticaId);
    
    $cobranzaAutomatica = $q->execute();
    $cobranzaAutomatica = $cobranzaAutomatica[0];
    $cobranzaAutomatica->setValorCuota($valor);
    $cobranzaAutomatica->setValorCuotaDescuento($valorDescuento);
    
    //crear cuotas a todos los usuarios q no pagaron adelantado
    
    $mes = $cobranzaAutomatica->getMes();
    $anio = $cobranzaAutomatica->getAnio();
    
    //buscar socios que no tengan la cuota del mes generado paga
        $query = "select soc.id, soc.descuento_familiar
                  from socio soc
                  where soc.id not in(
                        select s.id
                        from (socio s inner join cuota c on s.id = c.socio_id) 
                        where (c.anio = '$anio') 
                            and (c.mes = '$mes'))";

        $con = Doctrine_Manager::getInstance()->getCurrentConnection();
        $st = $con->execute($query);
        
        $socios = $st->fetchAll();
        
        foreach($socios as $socio){
            $cuota = new Cuota();
            $cuota->setMes($mes);
            $cuota->setAnio($anio);
            $cuota->setPagada(false);
            if ($socio['descuento_familiar']){
                $cuota->setMonto($valorDescuento);
            }else{
                $cuota->setMonto($valor);
            }
            $cuota->setSocioId($socio['id']);
            $cuota->save();
        }

    $cobranzaAutomatica->setGenerada(true);
    $cobranzaAutomatica->save();
    
  }
  
  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';

      $cobranza = $form->save();
      
      $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $cobranza)));

      $cobranza = $form->getObject();
      $idSocio = $cobranza->getCuota()->getSocioId();
      
      $this->redirect("socio/show?id=$socioId");
      
    }
    else
    {
      $this->getUser()->setFlash('error', 'The item has not been saved due to some errors.', false);
    }
  }
}
