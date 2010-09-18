<?php

<<<<<<< HEAD

class sfGuardRememberKeyTable extends PluginsfGuardRememberKeyTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('sfGuardRememberKey');
    }
}
=======
class sfGuardRememberKeyTable extends PluginsfGuardRememberKeyTable
{
  public static function getInstance()
  {
    return Doctrine_Core::getTable('sfGuardRememberKey');
  }
}
>>>>>>> b82443d... Cleaning up the project (V): code formatting and whitespace cleanup
