<?php

<<<<<<< HEAD

class sfGuardUserPermissionTable extends PluginsfGuardUserPermissionTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('sfGuardUserPermission');
    }
}
=======
class sfGuardUserPermissionTable extends PluginsfGuardUserPermissionTable
{
  public static function getInstance()
  {
    return Doctrine_Core::getTable('sfGuardUserPermission');
  }
}
>>>>>>> b82443d... Cleaning up the project (V): code formatting and whitespace cleanup
