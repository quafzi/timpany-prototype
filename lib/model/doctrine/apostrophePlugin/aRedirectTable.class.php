<?php


class aRedirectTable extends PluginaRedirectTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aRedirect');
    }
}