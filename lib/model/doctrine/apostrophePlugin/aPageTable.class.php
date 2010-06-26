<?php


class aPageTable extends PluginaPageTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aPage');
    }
}