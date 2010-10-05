<?php


class timpanyUserCartTable extends PlugintimpanyUserCartTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('timpanyUserCart');
    }
}