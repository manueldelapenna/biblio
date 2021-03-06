<?php

/**
 * CobranzaAutomaticaTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class CobranzaAutomaticaTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object CobranzaAutomaticaTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('CobranzaAutomatica');
    }
    
    public static function getSinGenerar()
    {
        
        return Doctrine_Query::create()
                ->select('*')
                ->from('CobranzaAutomatica ca')
                ->where('ca.generada =?',false)
                ->orderBy('ca.anio')
                ->addOrderBy('ca.mes');
    }
}