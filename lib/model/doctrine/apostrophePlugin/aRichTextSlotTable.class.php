<?php


class aRichTextSlotTable extends PluginaRichTextSlotTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aRichTextSlot');
    }
}