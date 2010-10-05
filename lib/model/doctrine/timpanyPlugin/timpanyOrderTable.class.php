<?php


class timpanyOrderTable extends PlugintimpanyOrderTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('timpanyOrder');
    }
}