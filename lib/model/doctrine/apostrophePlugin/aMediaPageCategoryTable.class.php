<?php


class aMediaPageCategoryTable extends PluginaMediaPageCategoryTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aMediaPageCategory');
    }
}