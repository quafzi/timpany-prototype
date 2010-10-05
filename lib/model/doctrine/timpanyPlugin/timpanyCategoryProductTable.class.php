<?php


class timpanyCategoryProductTable extends PlugintimpanyCategoryProductTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('timpanyCategoryProduct');
    }
}