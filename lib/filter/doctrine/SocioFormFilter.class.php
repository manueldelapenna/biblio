<?php

/**
 * Socio filter form.
 *
 * @package    subsecretaria
 * @subpackage filter
 * @author     Manuel De la Penna
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class SocioFormFilter extends BaseSocioFormFilter
{
  public function configure()
  {
     parent::configure();
      
     $this->widgetSchema['deuda_menor_a'] = new sfWidgetFormInputText();
     $this->validatorSchema['deuda_menor_a'] = new sfValidatorInteger(array('required' => false));
     
     $this->widgetSchema['deuda_mayor_a'] = new sfWidgetFormInputText();
     $this->validatorSchema['deuda_mayor_a'] = new sfValidatorInteger(array('required' => false));
     
     $this->widgetSchema['tiene_deuda'] = new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no')));
     $this->validatorSchema['tiene_deuda'] = new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0)));
             
  }
  
  public function getFields()
  {
    return parent::getFields() + array('deuda_menor_a' => 'text', 'deuda_mayor_a' => 'text','tiene_deuda' => 'text');
  }
  
 public function addDeudaMenorAColumnQuery(Doctrine_Query $query, $field, $value)
  {
  //Se comprueba que no sea nulo el valor del campo del filtro
         
      if ($value != '') {
		$a = $query->getRootAlias();
        $query->addHaving('sum('.$a.'.Cuotas.monto) <='. $value);
       
      }
  }
  
  
  public function addDeudaMayorAColumnQuery(Doctrine_Query $query, $field, $value)
  {
  //Se comprueba que no sea nulo el valor del campo del filtro
         
      if ($value != '') {
		$a = $query->getRootAlias();
        $query->addHaving('sum('.$a.'.Cuotas.monto) >='. $value);
       
      }
       
  }
  
  public function addTieneDeudaColumnQuery(Doctrine_Query $query, $field, $value)
  {
  //Se comprueba que no sea nulo el valor del campo del filtro
         
      if ($value != '') {
        $a = $query->getRootAlias();
        if ($value){// tiene deuda
        $query->innerJoin($a.'.Cuotas')
              ->addWhere($a.'.Cuotas.pagada = ?', !$value);
        }else{//no tiene deuda
            $con = Doctrine_Manager::getInstance()->getCurrentConnection();
            $subquery = "select c.socio_id
                         from cuota c
                         where (c.pagada = 0)";
            $st = $con->execute($subquery);
            $socios = $st->fetchAll();
            
            $i=0;
            foreach ($socios as $socio){
                $sociosId[$i] = $socio['socio_id'];
                $i++;
            }
            $query->whereNotIn($a.".id",$sociosId);

        
        }
       
      }
       
  }
}