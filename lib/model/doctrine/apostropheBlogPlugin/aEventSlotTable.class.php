<?php


class aEventSlotTable extends PluginaEventSlotTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aEventSlot');
    }
}