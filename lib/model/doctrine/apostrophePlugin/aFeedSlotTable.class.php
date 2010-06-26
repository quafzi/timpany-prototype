<?php


class aFeedSlotTable extends PluginaFeedSlotTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aFeedSlot');
    }
}