<?php

class apostropheBlogMigratePageSlugsTask extends sfBaseTask
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
    $this->name             = 'migrate-page-slugs';
    $this->briefDescription = 'You don\'t want this.';
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();

    $blogItems = Doctrine::getTable('aBlogItem')->createQuery()
      ->execute();
    foreach($blogItems as $blogItem)
    {
      if ($blogItem->type === 'post')
      {
        $blogItem->Page['slug'] = '@a_blog_search_redirect?id=' . $blogItem->id;
      }
      else
      {
        $blogItem->Page['slug'] = '@a_event_search_redirect?id=' . $blogItem->id;
      }
      $blogItem->save();
    }
    echo("Slugs successfully migrated.\n");

  }

}

?>
