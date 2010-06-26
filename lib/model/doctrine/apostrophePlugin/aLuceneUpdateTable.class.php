<?php


class aLuceneUpdateTable extends PluginaLuceneUpdateTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aLuceneUpdate');
    }
}