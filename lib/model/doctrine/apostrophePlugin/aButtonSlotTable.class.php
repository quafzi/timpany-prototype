<?php


class aButtonSlotTable extends PluginaButtonSlotTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aButtonSlot');
    }
}