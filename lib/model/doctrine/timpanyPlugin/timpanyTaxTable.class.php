<?php


class timpanyTaxTable extends PlugintimpanyTaxTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('timpanyTax');
    }
}