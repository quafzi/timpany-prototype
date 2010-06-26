<?php


class aMediaBrowserSlotTable extends PluginaMediaBrowserSlotTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aMediaBrowserSlot');
    }
}