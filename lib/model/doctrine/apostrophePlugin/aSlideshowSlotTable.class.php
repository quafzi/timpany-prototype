<?php


class aSlideshowSlotTable extends PluginaSlideshowSlotTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aSlideshowSlot');
    }
}