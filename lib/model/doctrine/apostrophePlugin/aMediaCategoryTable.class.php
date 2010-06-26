<?php


class aMediaCategoryTable extends PluginaMediaCategoryTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aMediaCategory');
    }
}