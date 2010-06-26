<?php


class aTextSlotTable extends PluginaTextSlotTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aTextSlot');
    }
}