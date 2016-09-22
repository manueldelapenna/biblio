<?php

require_once dirname(__FILE__).'/../lib/cobranzaGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/cobranzaGeneratorHelper.class.php';

/**
 * cobranza actions.
 *
 * @package    subsecretaria
 * @subpackage cobranza
 * @author     Manuel De la Penna
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class cobranzaActions extends autoCobranzaActions
{
  public function executeShow(sfWebRequest $request) {

        $cuotaId = $request->getParameter('cuotaId');
        
        $q = Doctrine_Query::create()
                ->select('*')
                ->from('Cobranza c')
                ->where('c.cuota_id = ?',$cuotaId);
        
        $resultados = $q->execute();
        $this->cobranza = $resultados[0];
        
        $q = Doctrine_Query::create()
                ->select('*')
                ->from('Cuota c')
                ->where('c.id = ?',$cuotaId);
        
        $resultados = $q->execute();
        $this->cuota = $resultados[0];
        
        
        
  }
    
    
  public function executeNew(sfWebRequest $request)
  {
    $q = Doctrine_Query::create()
                ->select('*')
                ->from('Cuota')
                ->where('id =?', $request->getParameter('cuotaId'));
        
    $cuota = $q->execute();
    $cuota = $cuota[0];

    $cobranza = new Cobranza();
    $cobranza->setCuotaId($request->getParameter('cuotaId'));
	$cobranza->setFecha(date('Y-m-d'));
    
    $this->cobranza = $cobranza;
    $this->form = new CobranzaForm($cobranza);
    $cuotaId = $request->getParameter('cuotaId');
    $_SESSION['cuotaId'] = $cuotaId;
  }
    
  
  public function executeCreate(sfWebRequest $request)
  {
    $this->form = $this->configuration->getForm();
    $this->cobranza = $this->form->getObject();

    $this->processForm($request, $this->form);
    
    $this->cobranza->setCuotaId($_SESSION['cuotaId']);
    
    $this->setTemplate('new');
  }
  
  
  public function executeListCancelar(sfWebRequest $request)
  {
    $cuotaId = $_SESSION['cuotaId'];
    $q = Doctrine_Query::create()
                ->select('*')
                ->from('Cuota c')
                ->where('c.id =?',$cuotaId);
    
    $cuota = $q->execute();
    $cuota = $cuota[0];
    $socioId = $cuota->getSocioId();
    
    $this->redirect("socio/show?id=$socioId");
  }
  
  public function executeListIrACobranza(sfWebRequest $request)
  {
    $cobranzaId = $this->getRoute()->getObject()->getId();
    $this->redirect("cobranza/show?id=$cobranzaId");
  }
  
  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
  	    $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';
      
		$cobranza = $form->save();
		$this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $cobranza)));
		$fecha = $form->getObject()->getFecha();
		$cobradorId = $form->getObject()->getCobradorId();
		$lugarCobroId = $form->getObject()->getLugarCobroId();
		$cuota = $form->getObject()->getCuota();
		$cuota->setPagada(true);
		$cuota->save();
		$socioId = $cuota->getSocioId();
		$cantidadAPagar = $form->getValue('cantidad_cuotas')-1;
		
		if($cantidadAPagar != 0){
			$q = Doctrine_Query::create()
				->select('*')
				->from('Cuota c')
				->innerJoin('c.Socio s')
				->where('s.id = ?',$socioId)
				->andWhere('c.pagada = ?',false)
				->orderBy('c.anio')
				->addOrderBy('c.mes');
			$cuotasImpagas = $q->execute();
			$cantImpagas = count($cuotasImpagas);
			
			//si la cantidad de cuotas a pagar es menor o igual a la cantidad de cuotas pendientes
			if($cantidadAPagar<=$cantImpagas){
				//se itera $cantidadAPagar veces las cuotas impagas y se pagan
				for($i=0;$i<$cantidadAPagar;$i++){
					$cuota = $cuotasImpagas[$i];
					$cuota->setPagada(true);
					$cuota->save();
					$cobranza = new Cobranza();
					$cobranza->setFecha($fecha);
					$cobranza->setCobradorId($cobradorId);
					$cobranza->setLugarCobroId($lugarCobroId);
					$cobranza->setCuotaId($cuota->getId());
					$cobranza->save();
				}
			//hay mas cuotas para pagar que las impagas
			}else{
				//se itera $cantImpagas veces las cuotas impagas y se pagan
				for($i=0;$i<$cantImpagas;$i++){
					$cuota = $cuotasImpagas[$i];
					$cuota->setPagada(true);
					$cuota->save();
					$cobranza = new Cobranza();
					$cobranza->setFecha($fecha);
					$cobranza->setCobradorId($cobradorId);
					$cobranza->setLugarCobroId($lugarCobroId);
					$cobranza->setCuotaId($cuota->getId());
					$cobranza->save();
				}
				
				//se calculan las adelantadas y se generan los pagos
				$cantRestantes = $cantidadAPagar - $cantImpagas;
				
				$request->setParameter('socioId', $socioId);
                $request->setParameter('cantidad', $cantRestantes);
                $request->setParameter('cobrador', $cobradorId);
                $request->setParameter('lugarCobro', $lugarCobroId);
                $request->setParameter('fecha', $fecha);
                
                $this->forward('cobranza','adelantarCuotasListo');

			}

		}
      
      $this->redirect("socio/show?id=$socioId");
     
    }
    else
    {
      $this->getUser()->setFlash('error', 'The item has not been saved due to some errors.', false);
    }
  }
  
  public function executeAdelantarCuotas(sfWebRequest $request) {
        $this->socioId = $request->getParameter('socioId');
        
        $q = Doctrine_Query::create()
                ->select('*')
                ->from('LugarCobro');
        $this->lugaresCobro = $q->execute();
        
        $q = Doctrine_Query::create()
                ->select('*')
                ->from('Cobrador');
        
        $this->cobradores = $q->execute();
        
		$this->form = new AdelantarCuotasForm();
               
          if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter('adelantarCuotas'));
            if ($this->form->isValid()) {
                $valores = $this->form->getValues();
                
                $request->setParameter('socioId', $request->getParameter('socioId'));
                $request->setParameter('cantidad', $valores['cantidad']);
                $request->setParameter('cobrador', $valores['cobrador']);
                $request->setParameter('lugarCobro', $valores['lugar_cobro']);
                $request->setParameter('fecha', $valores['fecha']);
                
                $this->forward('cobranza','adelantarCuotasListo');
            }
        }
        
        
      
        
  }
  
  public function executeAdelantarCuotasListo(sfWebRequest $request) {
        $socioId = $request->getParameter('socioId');
        $cantidad = $request->getParameter('cantidad');
        $cobradorId = $request->getParameter('cobrador');
        $lugarCobroId = $request->getParameter('lugarCobro');
        $fecha = $request->getParameter('fecha');
        
        $q = Doctrine_Query::create()
                ->select('*')
                ->from('Cuota c')
                ->where('c.socio_id =?', $socioId)
                ->andWhere('c.pagada =?', true)
                ->orderBy('c.anio')
                ->addOrderBy('c.mes');
        
        $cuotasPagas = $q->execute();
        $ultimaCuotaPaga = $cuotasPagas[count($cuotasPagas)-1];
        
        $ultimoMes = $ultimaCuotaPaga->getMes();
        $ultimoAnio = $ultimaCuotaPaga->getAnio();
        
        $q = Doctrine_Query::create()
                ->select('*')
                ->from('CobranzaAutomatica ca');
        
        $ultimaCobranzaAutomatica = $q->execute();
        $ultimaCobranzaAutomatica = $ultimaCobranzaAutomatica[count($ultimaCobranzaAutomatica)-1];
        
        if ($ultimaCuotaPaga->getSocio()->getDescuentoFamiliar()){
            $monto = $ultimaCobranzaAutomatica->getValorCuotaDescuento();
        }else{
            $monto = $ultimaCobranzaAutomatica->getValorCuota();
        }
        
        for ($i=1;$i <= $cantidad;$i++){
            $cuota = new Cuota();
            if ($ultimoMes + 1 == 13){
                $ultimoMes = 1;
                $ultimoAnio++;
            }else{
                $ultimoMes++;
            }
            $cuota->setMes($ultimoMes);
            $cuota->setAnio($ultimoAnio);
            $cuota->setMonto($monto);
            $cuota->setPagada(true);
            $cuota->setSocioId($socioId);
            $cuota->save();
            
            $cobranza = new Cobranza();
            $cobranza->setCobradorId($cobradorId);
            $cobranza->setCuotaId($cuota->getId());
            $cobranza->setLugarCobroId($lugarCobroId);
            $cobranza->setFecha($fecha);
            $cobranza->save();
        }

        $this->redirect("socio/show?id=$socioId");
        
        
  }
}
