<?php


class aRawHTMLSlotTable extends PluginaRawHTMLSlotTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aRawHTMLSlot');
    }
}