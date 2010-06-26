<?php


class aAccessTable extends PluginaAccessTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aAccess');
    }
}