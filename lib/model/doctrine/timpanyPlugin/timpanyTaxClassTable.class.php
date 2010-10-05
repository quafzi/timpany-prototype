<?php


class timpanyTaxClassTable extends PlugintimpanyTaxClassTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('timpanyTaxClass');
    }
}