<?php


class aSlotTable extends PluginaSlotTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aSlot');
    }
}