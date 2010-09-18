<?php

class myUser extends aSecurityUser
{
  public function signOut()
  {
    parent::signOut();
    timpanyCart::getInstance($this)->clear();
  }
}
