<?php

<<<<<<< HEAD

class sfGuardPermissionTable extends PluginsfGuardPermissionTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('sfGuardPermission');
    }
}
=======
class sfGuardPermissionTable extends PluginsfGuardPermissionTable
{
  public static function getInstance()
  {
    return Doctrine_Core::getTable('sfGuardPermission');
  }
}
>>>>>>> b82443d... Cleaning up the project (V): code formatting and whitespace cleanup
