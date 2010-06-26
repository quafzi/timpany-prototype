<?php


class aAreaVersionTable extends PluginaAreaVersionTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aAreaVersion');
    }
}