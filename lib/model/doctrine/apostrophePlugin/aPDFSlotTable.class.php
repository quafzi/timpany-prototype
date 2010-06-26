<?php


class aPDFSlotTable extends PluginaPDFSlotTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aPDFSlot');
    }
}