<?php

/**
 * 4/28/08 
 *
 * Start processes in various ways.
 *
 * @author Tom Boutell <tom@punkave.com>
 */

class aProcesses
{
 /*
  *
  * Like system(), but the command runs in the background
  * and is detached correctly from standard input and output.
  *
  */

  static public function background($cmd)
  {
    $result = system("($cmd &) < /dev/null > /dev/null");
    return $result;
  }

 /*
  *
  * Like system(), but stdout is discarded, and we return
  * the result code (like C or Perl system()), not the first line of output.
  *
  */

  static public function quiet($cmd)
  {
    $result = false;
    system("($cmd) > /dev/null", $result);
    return $result;
  }

 /*
  *
  * Like system(), but stdout AND stderr are discarded, and we return
  * the result code (like C or Perl system()), not the first line of output.
  *
  */

  static public function veryQuiet($cmd)
  {
    $result = false;
    system("($cmd) >> /dev/null 2>&1", $result);
    return $result;
  }
  
  /*
   *
   * Handy if you're reinvoking based on $argv[]. 
   * First argument is executable
   *
   */
   
  static public function systemArray($args, &$result = null)
  {
    $eargs = array();
    foreach ($args as $arg)
    {
      $eargs[] = escapeshellarg($arg);
    }
    $cmd = implode(' ', $args);
    return system($cmd, $result);
  }
}

