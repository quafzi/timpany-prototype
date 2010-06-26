<?php


class aSlotMediaItemTable extends PluginaSlotMediaItemTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aSlotMediaItem');
    }
}