<?php


class aEventTable extends PluginaEventTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aEvent');
    }
}