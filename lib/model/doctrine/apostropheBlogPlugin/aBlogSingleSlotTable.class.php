<?php


class aBlogSingleSlotTable extends PluginaBlogSingleSlotTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aBlogSingleSlot');
    }
}