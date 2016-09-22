<?php

require_once dirname(__FILE__).'/../lib/socioConDeudaGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/socioConDeudaGeneratorHelper.class.php';
include (sfConfig::get('sf_root_dir').'/lib/pdfClassesAndFonts/class.ezpdf.php');

/**
 * socioConDeuda actions.
 *
 * @package    subsecretaria
 * @subpackage socioConDeuda
 * @author     Manuel De la Penna
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class socioConDeudaActions extends autoSocioConDeudaActions
{
  protected function isValidSortColumn($column)
  {
    return in_array($column, array('numero_socio', 'apellido', 'nombre', 'dni', 'total'));
  }
    
    public function executeListGenerarRecibosListado(sfWebRequest $request) {
 
        $query = $this->buildQuery();
        $socios = $query->execute();
        
        $pdf =new Cezpdf('RA4');
        $pdf->selectFont('./fonts/Courier.afm');
        
        $cantidadSociosDeudores = count($socios);
        $x = 50;
        $y = 825;
        $cant = 0;
        foreach ($socios as $socio){
            $cant++;
            $cantidadSociosDeudores--;
            $q = Doctrine_Query::create()
                    ->select('*, count(c.socio_id) as cantidad, sum(c.monto) as total')
                    ->from('Cuota c')
                    ->where('c.socio_id = ?',$socio->getId())
                    ->andWhere('c.pagada = ?',false)
                    ->groupBy('c.socio_id')
                    ->orderBy('c.anio')
                    ->addOrderBy('c.mes');
            $cuotasAtrasadas = $q->execute();
            $cuotaAtrasadaUnSocio = $cuotasAtrasadas[0];
            
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
            
            $pdf->addText($x-20,$y,10, "Socio: ".$cuotaAtrasadaUnSocio->getSocio()->getApellido().', '.$cuotaAtrasadaUnSocio->getSocio()->getNombre());
            $pdf->addText($x-20+280,$y,10, "Socio: ".$cuotaAtrasadaUnSocio->getSocio()->getApellido().', '.$cuotaAtrasadaUnSocio->getSocio()->getNombre());
            $y = $y - 12;

            $pdf->addText($x-20,$y,10, "Nro Socio: ". $cuotaAtrasadaUnSocio->getSocio()->getNumeroSocio());
            $pdf->addText($x-20+280,$y,10, "Nro Socio: ". $cuotaAtrasadaUnSocio->getSocio()->getNumeroSocio());
            $y = $y - 12;
            
            $pdf->addText($x-20,$y,10, "Domicilio: ". $cuotaAtrasadaUnSocio->getSocio()->getDomicilio());
            $pdf->addText($x-20+280,$y,10, "Domicilio: ". $cuotaAtrasadaUnSocio->getSocio()->getDomicilio());
            $y = $y - 25;            
            
            $fecha = new DateTime();
            $pdf->addText($x-20,$y,10, "Cuota actual: ". date_format($fecha,'m/Y'));
            $pdf->addText($x-20+280,$y,10, "Cuota actual: ". date_format($fecha,'m/Y'));
            $y = $y - 12;
            
            $pdf->addText($x-20,$y,10, "Cantidad de cuotas adeudadas: ". $cuotaAtrasadaUnSocio->getCantidad());
            $pdf->addText($x-20+280,$y,10, "Cantidad de cuotas adeudadas: ". $cuotaAtrasadaUnSocio->getCantidad());
            $y = $y - 12;
            
            $pdf->addText($x-20,$y,10, "Monto total adeudado: $". $cuotaAtrasadaUnSocio->getTotal().'.00');
            $pdf->addText($x-20+280,$y,10, "Monto total adeudado: $". $cuotaAtrasadaUnSocio->getTotal().'.00');
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
            
            $pdf->addText($x-20,$y,10, "Fecha: ..../..../....");
            $pdf->addText($x-20+280,$y,10, "Fecha: ..../..../....");
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
  
  public function executeShow(sfWebRequest $request) {
      
      $socioId = $this->getRoute()->getObject()->getId();
      $this->redirect("socio/show?id=$socioId");
    }
    
    
}
