<?php


class timpanyCategoryTable extends PlugintimpanyCategoryTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('timpanyCategory');
    }
}