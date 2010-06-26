<?php

/*
 * This file is part of the sfSyncContentPlugin package
 * (c) 2009 P'unk Avenue LLC, www.punkave.com
 */

/**
 * @package    sfSyncContentPlugin
 * @subpackage Tasks
 * @author     Tom Boutell <tom@punkave.com>
 */

class sfSyncContentTask extends sfBaseTask
{
  protected $sshPort = 22;
  
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('application', 
        sfCommandArgument::REQUIRED, 
        'The application name ("frontend")'),
      new sfCommandArgument('env', 
        sfCommandArgument::REQUIRED, 
        'The local environment ("dev")'),
      new sfCommandArgument('direction', 
        sfCommandArgument::REQUIRED, 
        'Either "from" or "to"; when you specify "from" content is copied FROM the remote site, when you specify "to" content is copied TO the remote site'),
      new sfCommandArgument('remoteenv',
        sfCommandArgument::REQUIRED, 
        'The remote environment and site. The site name must be defined in properties.ini')));

    $this->namespace        = 'project';
    $this->name             = 'sync-content';
    $this->briefDescription = 'Synchronize content (not code) between Symfony instances';
    $this->detailedDescription = <<<EOF
You must specify the application ("frontend"), local environment ("dev"), "to" or "from", and the remote environment and site ("dev@staging", "prod@production", etc). Currently only the default database connection is copied. This may change in a future release. In addition to the database, data folders listed at app_sfSyncContentPlugin_content will also be synced.',

EOF;
  }

  protected function execute($args = array(), $options = array())
  {

    /**
    * syncs your content (not code) to or from the production or staging server.
    * That means syncing two things: the database and any data folders that
    * have been configured via app.yml.
    */

    if (count($args) != 5)
    {
      throw new sfException('You must specify the application ("frontend"), the local environment ("dev"), "to" or "from", and the remote environment and site ("dev@staging", "prod@production", etc). Currently only the first database is copied. Later versions may copy all databases.');
    }
    
    $settings = parse_ini_file("config/properties.ini", true);
    if ($settings === false)
    {
      throw new sfException("You must be in a symfony project directory");
    }
    $application = $args['application'];
    $this->checkAppExists($application);
    $direction = $args['direction'];
    if (($direction != 'to') && ($direction != 'from'))
    {
      throw new sfException("The third argument must be either 'to' or 'from'");
    }  

    if (!preg_match('/^(.*)\@(.*)?$/', $args['remoteenv'], $matches))
    {
      throw new sfException("Fourth argument must be of the form environment@site, example: dev@staging or prod@production; the site must be defined in properties.ini");
    }
    $envRemote = $matches[1];
    $site = $matches[2];
    
    $found = false;
    foreach ($settings as $section => $data)
    {
      if ($site == $section)
      {
        $found = true;
        break;   
      }
    }

    if (!$found)
    {
      throw new sfException("Fourth argument must be of the form environment@site example: dev@staging the site must be defined in properties.ini");
    }

    if (!preg_match('/^(\w+)(:(\w+))?$/', $args['env'], $matches))
    {
      throw new sfException("Second argument must be an environment name. Example #1: dev Example #2: prod");
    }
    $env = $matches[1];
    $pathLocal = '.';
    $pathRemote = $data['user'] . '@' . $data['host'] . ':' . $data['dir'];
    if (isset($data['port']))
    {
      // Needed in multiple places least hairy this way
      $this->sshPort = $data['port'] + 0;
    }
    
    $binary = $_SERVER['SCRIPT_FILENAME'];
    // A further simplification: use the subsidiary tasks locally too. This resolves issues with the
    // correct environment not being loaded and removes duplicate code
    if ($direction == 'to')
    {
      $cmd = "$binary project:mysql-dump --application=$application --env=$env | " . $this->_content_sync_build_remote_cmd($pathRemote, "./symfony project:mysql-load --application=$application --env=$envRemote");
      $this->_content_sync_system($cmd);
    }
    else
    {
      $cmd = $this->_content_sync_build_remote_cmd($pathRemote, "./symfony project:mysql-dump --application=$application --env=$envRemote") . " | $binary project:mysql-load --application=$application --env=$env";
      $this->_content_sync_system($cmd);
    }
    
    $asData = sfConfig::get('app_sfSyncContent_content',
      array());
    foreach ($asData as $path)
    {
      if ($direction == 'to')
      {
        $from = "$pathLocal/$path";
        $to = "$pathRemote/$path";
      }
      else
      {
        $to = "$pathLocal/$path";
        $from = "$pathRemote/$path";
      }
      $to = dirname($to);
      $this->_content_sync_rsync($from, $to);
    }
  }

  function _content_sync_rsync($path1, $path2)
  {
    // The additional options used here after -azC enhance compatibility with 
    // setgid environments. TODO: make this configurable.
    
    $port = $this->sshPort;
    $this->_content_sync_system("rsync -e 'ssh -p $port' -azC --no-o --no-t --no-p --force --delete --progress " . escapeshellarg($path1) . " " . escapeshellarg($path2));
  }

  function _content_sync_file_get_contents($path, $file)
  {
    if (!preg_match("/^(\S+\@\S+)\:(.*)$/", $path, $args))
    {
      // Local, easy-peasy
      if (file_exists("$path/$file"))
      {
        return file_get_contents("$path/$file");
      }
      throw new sfException("File $path/$file not found");
    }
    // Not too hard either
    $cmd = $this->_content_sync_build_remote_cmd($path, "cat " . escapeshellarg($file));
    echo("cmd is $cmd\n");
    $in = popen($cmd, "r");
    $data = stream_get_contents($in);
    // Note that this doesn't really mean the file doesn't exist;
    // it means the whole remote operation failed
    if ($data === false)
    {
      throw new sfException("Read from remote command $cmd failed");
    }    
    pclose($in);
    return $data;
  }

  function _content_sync_build_remote_cmd($pathRemote, $cmd)
  {
    if (preg_match("/^(.*?\@.*?)\:(.*)$/", $pathRemote, $args))
    {
      $auth = $args[1];
      $path = $args[2];
      $port = $this->sshPort;
      $cmd = "ssh -p $port $auth " . escapeshellarg("(cd " . escapeshellarg($path) . 
        "; " . $cmd . ")");
      return $cmd;
    }
    else
    {
      echo("we received $pathRemote $cmd\n");
      exit(0);
    }
  }

  function _content_sync_remote_system($pathRemote, $cmd)
  {
    return $this->_content_sync_system($this->_content_sync_build_remote_cmd($pathRemote, $cmd));
  }

  function _content_sync_system($cmd)
  {
    echo("Executing $cmd\n");
    system($cmd, $result);
    if ($result != 0)
    {
      throw new sfException("Command $cmd failed, halting");
    }    
  }
}
