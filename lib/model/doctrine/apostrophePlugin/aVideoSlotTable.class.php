<?php


class aVideoSlotTable extends PluginaVideoSlotTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aVideoSlot');
    }
}