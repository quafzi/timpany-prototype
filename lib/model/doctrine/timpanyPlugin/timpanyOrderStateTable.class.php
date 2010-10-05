<?php


class timpanyOrderStateTable extends PlugintimpanyOrderStateTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('timpanyOrderState');
    }
}