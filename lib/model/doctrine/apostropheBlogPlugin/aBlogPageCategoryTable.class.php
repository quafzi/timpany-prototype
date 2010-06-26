<?php


class aBlogPageCategoryTable extends PluginaBlogPageCategoryTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aBlogPageCategory');
    }
}