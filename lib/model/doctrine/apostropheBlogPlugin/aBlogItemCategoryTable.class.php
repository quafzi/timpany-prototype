<?php


class aBlogItemCategoryTable extends PluginaBlogItemCategoryTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aBlogItemCategory');
    }
}