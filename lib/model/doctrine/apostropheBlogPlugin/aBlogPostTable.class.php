<?php


class aBlogPostTable extends PluginaBlogPostTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aBlogPost');
    }
}