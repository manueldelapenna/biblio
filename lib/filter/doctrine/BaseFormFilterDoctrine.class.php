<?php

/**
 * Project filter form base class.
 *
 * @package    subsecretaria
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterBaseTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class BaseFormFilterDoctrine extends sfFormFilterDoctrine
{
  public function setup()
  {
  }
  
   public function __construct($defaults = array(), $options = array(), $CSRFSecret = null)  
  {  
    parent::__construct($defaults, $options, $CSRFSecret);  
    $this->fixI18N();  
  }  
  
  protected function fixI18N()  
  {  
    foreach ($this->widgetSchema->getFields() as $field) {  
      if ($field instanceof sfWidgetFormFilterDate) {  
        if (class_exists('sfWidgetFormJQueryDate')) {  
          $field->setOption('from_date', new sfWidgetFormJQueryDate(array(  
            'culture' => sfContext::getInstance()->getUser()->getCulture(),
            'config' => "{showOn: 'button', changeYear: true}",  
            'date_widget' => new sfWidgetFormDate(array('format' => '%day% - %month% - %year%')),
          )));  
          $field->setOption('to_date', new sfWidgetFormJQueryDate(array(  
            'culture' => sfContext::getInstance()->getUser()->getCulture(),  
            'config' => "{showOn: 'button', changeYear: true}",
            'date_widget' => new sfWidgetFormDate(array('format' => '%day% - %month% - %year%'))
          )));  
        } else {  
          $field->setOption('from_date', new sfWidgetFormI18nDate(array(  
            'culture' => sfContext::getInstance()->getUser()->getCulture(),  
          )));  
          $field->setOption('to_date', new sfWidgetFormI18nDate(array(  
            'culture' => sfContext::getInstance()->getUser()->getCulture(),  
          )));  
        }  
      }  
    }  
  }  
}
