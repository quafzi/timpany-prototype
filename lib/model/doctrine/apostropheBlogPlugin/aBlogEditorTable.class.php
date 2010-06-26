<?php


class aBlogEditorTable extends PluginaBlogEditorTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aBlogEditor');
    }
}