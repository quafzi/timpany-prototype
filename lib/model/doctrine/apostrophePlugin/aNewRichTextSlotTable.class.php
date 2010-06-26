<?php


class aNewRichTextSlotTable extends PluginaNewRichTextSlotTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aNewRichTextSlot');
    }
}