<?php


class aBlogCategoryTable extends PluginaBlogCategoryTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aBlogCategory');
    }
}