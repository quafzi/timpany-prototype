<?php


class aImageSlotTable extends PluginaImageSlotTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aImageSlot');
    }
}