<?php

/**
 * BaseLugarCobro
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $nombre
 * @property Doctrine_Collection $Cobranzas
 * 
 * @method string              getNombre()    Returns the current record's "nombre" value
 * @method Doctrine_Collection getCobranzas() Returns the current record's "Cobranzas" collection
 * @method LugarCobro          setNombre()    Sets the current record's "nombre" value
 * @method LugarCobro          setCobranzas() Sets the current record's "Cobranzas" collection
 * 
 * @package    subsecretaria
 * @subpackage model
 * @author     Manuel De la Penna
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseLugarCobro extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('lugar_cobro');
        $this->hasColumn('nombre', 'string', 200, array(
             'type' => 'string',
             'length' => 200,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Cobranza as Cobranzas', array(
             'local' => 'id',
             'foreign' => 'lugar_cobro_id'));
    }
}