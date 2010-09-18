<?php

<<<<<<< HEAD

class sfGuardGroupPermissionTable extends PluginsfGuardGroupPermissionTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('sfGuardGroupPermission');
    }
}
=======
class sfGuardGroupPermissionTable extends PluginsfGuardGroupPermissionTable
{
  public static function getInstance()
  {
    return Doctrine_Core::getTable('sfGuardGroupPermission');
  }
}
>>>>>>> b82443d... Cleaning up the project (V): code formatting and whitespace cleanup
