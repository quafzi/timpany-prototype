<?php


class aBlogCategoryUserTable extends PluginaBlogCategoryUserTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aBlogCategoryUser');
    }
}