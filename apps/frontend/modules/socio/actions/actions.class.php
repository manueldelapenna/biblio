<?php

require_once dirname(__FILE__).'/../lib/socioGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/socioGeneratorHelper.class.php';
include (sfConfig::get('sf_root_dir').'/lib/pdfClassesAndFonts/class.ezpdf.php');

/**
 * socio actions.
 *
 * @package    subsecretaria
 * @subpackage socio
 * @author     Manuel De la Penna
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class socioActions extends autoSocioActions
{
//    public function executeIndex(sfWebRequest $request) {
//        $this->importarSocios();
//    }

    
    public function leerArchivo($nombreArchivo) {
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load('d:\Users\Manuel\Desktop\\' . $nombreArchivo);
        return $objPHPExcel;
    }

    public function importarSocios() {
        $objPHPExcel = $this->leerArchivo('socios.xlsx');
        $mesActual = date('m');
        $anioActual = date('Y');
        
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {

            foreach ($worksheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
                $columns = 'A';
                foreach ($cellIterator as $cell) {
                    switch ($columns) {
                        case 'A':
                            $socio = new Socio();
                            $socio->setNumeroSocio($cell->getCalculatedValue());
                            break;
                        case 'B':
                            if (!is_null($cell)) {
                                $socio->setDni($cell->getCalculatedValue());
                            }
                            break;
                        case 'C':
                            if (!is_null($cell)) {
                                $socio->setApellido($cell->getCalculatedValue());
                            }
                            break;
                        case 'D':
                            if (!is_null($cell)) {
                                $socio->setNombre($cell->getCalculatedValue());
                            }
                            break;
                        case 'E':
                            if (!is_null($cell)) {
                                $socio->setDomicilio($cell->getCalculatedValue());
                            }
                            break;
                        case 'F':
                            if (!is_null($cell)) {
                                $socio->setDomicilio($socio->getDomicilio().' '.$cell->getCalculatedValue());
                            }
                            break;
                        case 'G':
                            if (!is_null($cell)) {
                                $socio->setLocalidad($cell->getCalculatedValue());
                            }
                            break;
                        case 'H':
                            if (!is_null($cell)) {
                                $socio->setCodigoPostal($cell->getCalculatedValue());
                            }
                            break;
                        case 'I':
                            if (!is_null($cell)) {
                                $socio->setMail($cell->getCalculatedValue());
                            }
                            break;
                        case 'J':
                            if (!is_null($cell)) {
                                $socio->setTelefono($cell->getCalculatedValue());
                            }
                            break;
                        case 'K':
                            if (!is_null($cell)) {
                                $socio->setCelular($cell->getCalculatedValue());
                            }
                            break;
                        case 'M':
                            if ($cell->getCalculatedValue()== '10') {
                                $socio->setDescuentoFamiliar(true);
                            }
                            break;
                        case 'N':
                            if (!is_null($cell)) {
                                $socio->save();
                                
                                //cargar cuotas pagas con sus cobranzas
                                $ultimoPago = $cell->getCalculatedValue();
                                $fechaSinBarra = explode('/', $ultimoPago);
                                $ultimoMesPago = $fechaSinBarra[0];
                                $ultimoAnioPago = $fechaSinBarra[1];
                                
                                //generar cuotas hasta la actualidad
                                for ($i=2011;$i<2012;$i++){
                                    for ($j=1;$j<=12;$j++){
                                        $cuota = new Cuota();
                                        $cuota->setSocioId($socio->getId());
                                        $cuota->setAnio($i);
                                        $cuota->setMes($j);
                                        
                                        // preguntar por descuento
                                        if ($socio->getDescuentoFamiliar()){
                                            $cuota->setMonto(10);
                                        }else{
                                            $cuota->setMonto(15);
                                        }
                                        
                                        if (($ultimoAnioPago > $i) || 
                                           ($ultimoAnioPago == $i && $ultimoMesPago >= $j )){//cuota paga
                                            $cuota->setPagada(true);
                                            $cuota->save();
                                            // generar cobranza
                                            
                                            $cobranza = new Cobranza();
                                            $mes = $cuota->getMes();
                                            $anio = $cuota->getAnio();
                                            $fecha = $anio.'-'.$mes.'-1';
                                            $cobranza->setFecha($fecha);
                                            $cobranza->setCobradorId(1);
                                            $cobranza->setLugarCobroId(1);
                                            $cobranza->setCuotaId($cuota->getId());
                                            $cobranza->save();
                                            
                                        }else{//cuota no paga
                                            $cuota->setPagada(false);
                                            $cuota->save();
                                        }
                                    }
                                }
                                
                                for ($j=1;$j<=$mesActual;$j++){
                                        $cuota = new Cuota();
                                        $cuota->setSocioId($socio->getId());
                                        $cuota->setAnio($i);
                                        $cuota->setMes($j);
                                        
                                        // preguntar por descuento
                                        if ($socio->getDescuentoFamiliar()){
                                            $cuota->setMonto(10);
                                        }else{
                                            $cuota->setMonto(15);
                                        }
                                        
                                        if (($ultimoAnioPago > 2012) || 
                                           ($ultimoAnioPago == 2012 && $ultimoMesPago >= $j )){//cuota paga
                                            $cuota->setPagada(true);
                                            $cuota->save();
                                            //generar cobranza
                                            $cobranza = new Cobranza();
                                            $mes = $cuota->getMes();
                                            $anio = $cuota->getAnio();
                                            $fecha = $anio.'-'.$mes.'-1';
                                            $cobranza->setFecha($fecha);
                                            $cobranza->setCobradorId(1);
                                            $cobranza->setLugarCobroId(1);
                                            $cobranza->setCuotaId($cuota->getId());
                                            $cobranza->save();
                                        }else{//cuota no paga
                                            $cuota->setPagada(false);
                                            $cuota->save();
                                        }
                                }
                                $ultimoMesPago = $fechaSinBarra[0];
                                $ultimoAnioPago = $fechaSinBarra[1];
                                
                                if($ultimoAnioPago == 2012 && $ultimoMesPago > $mesActual){
                                    for ($j=($mesActual+1);$j<=$ultimoMesPago;$j++){
                                        $cuota = new Cuota();
                                        $cuota->setSocioId($socio->getId());
                                        $cuota->setAnio(2012);
                                        $cuota->setMes($j);
                                        
                                        // preguntar por descuento
                                        if ($socio->getDescuentoFamiliar()){
                                            $cuota->setMonto(10);
                                        }else{
                                            $cuota->setMonto(15);
                                        }
                                        $cuota->setPagada(true);
                                        $cuota->save();
                                        //generar cobranza
                                        $cobranza = new Cobranza();
                                        $mes = $cuota->getMes();
                                        $anio = $cuota->getAnio();
                                        $fecha = $anio.'-'.$mes.'-1';
                                        $cobranza->setFecha($fecha);
                                        $cobranza->setCobradorId(1);
                                        $cobranza->setLugarCobroId(1);
                                        $cobranza->setCuotaId($cuota->getId());
                                        $cobranza->save();
                                    }
                                    
                                }
                            }
                            
                            break;
                        
                    }
                    $columns++;
                }
            }
        }
    
    }
    
    public function executeShow(sfWebRequest $request) {
        $mesActual = date('m');
        $anioActual = date('Y');
        
        $this->socio = $this->getRoute()->getObject();
        $q = Doctrine_Query::create()
                ->select('*')
                ->from('Cuota c')
                ->where('c.socio_id = ?',$this->socio->getId())
                ->andWhere("(c.mes <= $mesActual and c.anio <= $anioActual)
                        or (c.mes > $mesActual and c.anio < $anioActual)")
                ->andWhere('c.pagada = ?',false)
                ->orderBy('c.anio')
                ->addOrderBy('c.mes');
        $this->cuotasAtrasadas = $q->execute();
        
        $q = Doctrine_Query::create()
                ->select('*')
                ->from('Cuota c')
                ->where('c.socio_id = ?',$this->socio->getId())
                ->andWhere('c.pagada = ?',true)
                ->orderBy('c.anio asc')
                ->addOrderBy('c.mes asc');
        $this->cuotasPagas = $q->execute();
		
		$this->cantAdelantadas = $this->socio->getCantidadCuotasAdelantadas();
		
    }
    
    public function executeBorrarCuota(sfWebRequest $request) {
        $mesActual = date('m');
        $anioActual = date('Y');
        
        $cuotaId = $request->getParameter('cuotaId');
        $socioId = $request->getParameter('socioId');
        
        $q = Doctrine_Query::create()
                ->select('*')
                ->from('Cuota c')
                ->where('c.id = ?',$cuotaId);
        $cuota = $q->execute();
        $cuota = $cuota[0];

        // si la cuota es adelantada se borra
        if(($cuota->getMes()> $mesActual && $cuota->getAnio() >= $anioActual)|| 
           ($cuota->getMes() <= $mesActual && $cuota->getAnio() > $anioActual)) {
            $cuota->delete();
        }else{// sino se marca como no paga
            $cuota->setPagada(false);
            $cuota->save();
        }
		//se borra la cobranza
		$q = Doctrine_Query::create()
                ->select('*')
                ->from('Cobranza c')
                ->where('c.cuota_id = ?',$cuotaId);
            $cobranza = $q->execute();
            $cobranza = $cobranza[0];
            $cobranza->delete();
        
        $this->redirect("socio/show?id=$socioId");
        
        
    }
    
    protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';

      if ($form->getObject()->isNew() == 1){
          $socio = $form->save();
          $auxSocio = $form->getObject();
      
      
          $mesActual = date('m');
          $anioActual = date('Y');
          $cuota = new Cuota();
          $cuota->setSocioId($auxSocio->getId());
          $cuota->setMes($mesActual);
          $cuota->setAnio($anioActual);

          $q = Doctrine_Query::create()
                    ->select('*')
                    ->from('CobranzaAutomatica ca');

            $ultimaCobranzaAutomatica = $q->execute();
            $ultimaCobranzaAutomatica = $ultimaCobranzaAutomatica[count($ultimaCobranzaAutomatica)-1];

            if ($auxSocio->getDescuentoFamiliar()){
                $monto = $ultimaCobranzaAutomatica->getValorCuotaDescuento();
            }else{
                $monto = $ultimaCobranzaAutomatica->getValorCuota();
            }

            $cuota->setMonto($monto);
            $cuota->setPagada(false);
            $cuota->save();
      }else{
          $socio = $form->save();
      }
        

      $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $socio)));

      if ($request->hasParameter('_save_and_add'))
      {
        $this->getUser()->setFlash('notice', $notice.' You can add another one below.');

        $this->redirect('@socio_new');
      }
      else
      {
        $this->getUser()->setFlash('notice', $notice);

        $this->redirect(array('sf_route' => 'socio_edit', 'sf_subject' => $socio));
      }
    }
    else
    {
      $this->getUser()->setFlash('error', 'The item has not been saved due to some errors.', false);
    }
  }
  
  public function executeListExportarAExcel(sfWebRequest $request) {

        // Create new PHPExcel object
        $objPHPExcel = new sfPhpExcel();

        // Expedients to export
        $query = $this->buildQuery();

        $socios = $query->execute();

        $row = 1;
        $col = 0;

        // Add some data
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Nro. Socio');
        $col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Apellido');
        $col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Nombre');
        $col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'DNI');
        $col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Domicilio');
        $col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Localidad');
        $col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Cod. Postal');
        $col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Tel');
        $col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Cel');
        $col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Mail');
        $col++;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Dto. Flia.');
        $col++;

        foreach ($socios as $socio) {
            $col = 0;
            $row++;

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $socio->getNumeroSocio());
            $col++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $socio->getApellido());
            $col++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $socio->getNombre());
            $col++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $socio->getDni());
            $col++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $socio->getDomicilio());
            $col++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $socio->getLocalidad());
            $col++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $socio->getCodigoPostal());
            $col++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $socio->getTelefono());
            $col++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $socio->getCelular());
            $col++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $socio->getMail());
            $col++;
            if ($socio->getDescuentoFamiliar() == 1){
                $valor = 'Sí';
            }else{
                $valor = 'No';
            }
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $valor);
            $col++;
        }

        // Rename sheet
        $objPHPExcel->getActiveSheet()->setTitle('Simple');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Save Excel 2007 file
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

		// Redirect output to a client&#65533;s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="socios.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
    }
	
	public function executeReciboAdelanto(sfWebRequest $request) {
	
		$socioId = $request->getParameter('socioId');
        
		$socio = Doctrine_Core::getTable('Socio')->find($socioId);
        
        $pdf =new Cezpdf('RA4');
        $pdf->selectFont('./fonts/Courier.afm');
        
        
        $x = 50;
        $y = 825;
        $cant = 0;
        
            
		$pdf->addText($x,$y,10,"BIBLIOTECA POPULAR Y CENTRO CULTURAL");
		$pdf->addText($x+280,$y,10,"BIBLIOTECA POPULAR Y CENTRO CULTURAL");
		$y = $y - 12;

		$pdf->addText($x+50,$y,10,"\"JOSE INGENIEROS\"");
		$pdf->addText($x+50+280,$y,10,"\"JOSE INGENIEROS\"");
		$y = $y - 12;
		
		$pdf->addText($x+28,$y,10, "Bolívar 132 - (7500) Tres Arroyos");
		$pdf->addText($x+28+280,$y,10, "Bolívar 132 - (7500) Tres Arroyos");
		$y = $y - 12;
		
		$pdf->addText($x+12,$y,10, "TE: 02983-431791 CUIT: 30-66744617-6");
		$pdf->addText($x+12+280,$y,10, "TE: 02983-431791 CUIT: 30-66744617-6");
		$y = $y - 25;
		
		$pdf->addText($x-20,$y,10, "Socio: ".$socio->getApellido().', '.$socio->getNombre());
		$pdf->addText($x-20+280,$y,10, "Socio: ".$socio->getApellido().', '.$socio->getNombre());
		$y = $y - 12;

		$pdf->addText($x-20,$y,10, "Nro Socio: ". $socio->getNumeroSocio());
		$pdf->addText($x-20+280,$y,10, "Nro Socio: ". $socio->getNumeroSocio());
		$y = $y - 12;
		
		$pdf->addText($x-20,$y,10, "Domicilio: ". $socio->getDomicilio());
		$pdf->addText($x-20+280,$y,10, "Domicilio: ". $socio->getDomicilio());
		$y = $y - 25;            
		
		$fecha = new DateTime();
		//$pdf->addText($x-20,$y,10, "Cuota actual: ". date_format($fecha,'m/Y'));
		//$pdf->addText($x-20+280,$y,10, "Cuota actual: ". date_format($fecha,'m/Y'));
		$y = $y - 12;
		
		$pdf->addText($x-20,$y,10, "Cantidad de cuotas adelantadas: .......");//. $cuotaAtrasadaUnSocio->getCantidad());
		$pdf->addText($x-20+280,$y,10, "Cantidad de cuotas adelantadas: .......");//. $cuotaAtrasadaUnSocio->getCantidad());
		$y = $y - 12;
		
		//$pdf->addText($x-20,$y,10, "Monto total adeudado: $". $cuotaAtrasadaUnSocio->getTotal().'.00');
		//$pdf->addText($x-20+280,$y,10, "Monto total adeudado: $". $cuotaAtrasadaUnSocio->getTotal().'.00');
		$y = $y - 25;
		
		$pdf->addText($x-20,$y,10, "Recibí PESOS ........................... en concepto de");
		$pdf->addText($x-20+280,$y,10, "Recibí PESOS ........................... en concepto de");
		$y = $y - 12;
		
		$pdf->addText($x-20,$y,10, "....... cuotas.");
		$pdf->addText($x-20+280,$y,10, "....... cuotas.");
		$y = $y - 12;
		
		$pdf->addText($x-20,$y,10, "Son: $ .........");
		$pdf->addText($x-20+280,$y,10, "Son: $ .........");
		$y = $y - 12;
		
		$pdf->addText($x-20,$y,10, "Fecha: " . date('d/m/Y'));
		$pdf->addText($x-20+280,$y,10, "Fecha: " . date('d/m/Y'));
		$y = $y - 12;
		
		$pdf->addText($x+100,$y,10, "Firma:...........................");
		$pdf->addText($x+100+280,$y,10, "Firma:...........................");
		$y = $y - 17;
		
		$pdf->addText($x+100,$y,10, "Cobrador:.....................");
		$pdf->addText($x+100+280,$y,10, "Cobrador:.....................");
		$y = $y - 65;
		
		if ($cant == 3){
			$cant = 0;
			$y = 825;
			if ($cantidadSociosDeudores != 0){
				$pdf->newPage();
			}
		}

        header("Cache-Control: cache, must-revalidate");    
		header("Pragma: public");
		header("Content-Type: application/force-download");
		header("Content-Disposition: attachment; filename=\"report.pdf\"");
		echo $pdf->output();
		exit; 
        
    }
    
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
    
}
