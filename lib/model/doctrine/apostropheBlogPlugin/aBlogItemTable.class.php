<?php


class aBlogItemTable extends PluginaBlogItemTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aBlogItem');
    }
}