<?php


class aMediaItemTable extends PluginaMediaItemTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aMediaItem');
    }
}