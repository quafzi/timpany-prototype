<?php


class aMediaItemCategoryTable extends PluginaMediaItemCategoryTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aMediaItemCategory');
    }
}