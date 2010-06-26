<?php


class aEventSingleSlotTable extends PluginaEventSingleSlotTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aEventSingleSlot');
    }
}