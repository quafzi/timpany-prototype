<?php


class aAreaVersionSlotTable extends PluginaAreaVersionSlotTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aAreaVersionSlot');
    }
}