<?php


class aBlogSlotTable extends PluginaBlogSlotTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aBlogSlot');
    }
}