<?php


class timpanyProductTable extends PlugintimpanyProductTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('timpanyProduct');
    }
}