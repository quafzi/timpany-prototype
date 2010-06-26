<?php

class sfSyncContentTools
{
  static public function getDatabaseParams($configuration, $dbname = false)
  {
    $dbManager = new sfDatabaseManager($configuration);
    
    $names = $dbManager->getNames();
    $db = $dbManager->getDatabase($dbname ? $dbname : $names[0]);
    if (!$db)
    {
      throw new sfException("No database connection called $db is defined");
    }
    $username = $db->getParameter('username');  //root
    $dsn = $db->getParameter('dsn');  //mysql:dbname=mydbtest;host=localhost because it's test config
    $password = $db->getParameter('password');  //password
    if (!preg_match('/^mysql:(.*)\s*$/', $dsn, $matches))
    {
      throw new sfException("I don't understand the DSN $dsn, sorry");
    }
    $pairs = explode(';', $matches[1]);
    $data = array();
    foreach ($pairs as $pair)
    {
      list($key, $val) = explode('=', $pair);
      $data[$key] = $val;
    }
    $data['username'] = $username;
    $data['password'] = $password;
    return $data;
  }
  
  static public function shellDatabaseParams($params)
  {
    return '-u ' . escapeshellarg($params['username']) . ' -p' . escapeshellarg($params['password']) . ' -h ' . escapeshellarg($params['host']) . ' ' . escapeshellarg($params['dbname']);
  }
  
}
