<?php

<<<<<<< HEAD

class sfGuardUserTable extends PluginsfGuardUserTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('sfGuardUser');
    }
}
=======
class sfGuardUserTable extends PluginsfGuardUserTable
{
  public static function getInstance()
  {
    return Doctrine_Core::getTable('sfGuardUser');
  }
}
>>>>>>> b82443d... Cleaning up the project (V): code formatting and whitespace cleanup
