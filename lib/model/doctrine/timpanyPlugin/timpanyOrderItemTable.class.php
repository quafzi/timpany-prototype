<?php


class timpanyOrderItemTable extends PlugintimpanyOrderItemTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('timpanyOrderItem');
    }
}