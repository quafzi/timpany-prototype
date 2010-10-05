<?php


class timpanyCartItemTable extends PlugintimpanyCartItemTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('timpanyCartItem');
    }
}