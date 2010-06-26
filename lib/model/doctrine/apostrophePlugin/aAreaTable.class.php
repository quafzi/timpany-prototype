<?php


class aAreaTable extends PluginaAreaTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aArea');
    }
}