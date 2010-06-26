<?php

class apostropheBlogFixUntitledPostsTask extends sfBaseTask
{

  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      new sfCommandOption('force', false, sfCommandOption::PARAMETER_NONE, 'No prompts'),
      // add your own options here
    ));

    $this->namespace        = 'apostrophe-blog';
    $this->name             = 'fix-untitled-posts';
    $this->briefDescription = 'You don\'t want this.';
  }

  protected function execute($arguments = array(), $options = array())
  {
    // Memory usage is a bit high here because we look at every page, and the Rackspace Cloud
    // environment has a very low default memory limit for their ersatz "cron jobs."
    // TODO: prioritize a low-memory solution for rebuild-search-index, which will be
    // necessary for large sites anyway
    
    ini_set('memory_limit', '256M');
    
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();

    $blogItems = Doctrine::getTable('aBlogItem')->createQuery()
      ->execute();
    
    foreach ($blogItems as $blogItem)
    {
      $title = $blogItem->Page->createSlot('aText');
      $title->value = htmlentities($blogItem['title'], ENT_COMPAT, 'UTF-8');
      $title->save();
      $blogItem->Page->newAreaVersion('title', 'update',
        array(
          'permid' => 1,
          'slot' => $title));
    }
  }
}
