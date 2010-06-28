<?php
/**
 */
class PluginaBlogItemTable extends Doctrine_Table
{
  protected $categoryColumn = 'posts';

  public static function getInstance()
  {
    return Doctrine_Core::getTable('aBlogItem');
  }
  
  public function filterByYMD(Doctrine_Query $q, sfWebRequest $request)
  {
    $rootAlias = $q->getRootAlias();
    
    $sYear = $request->getParameter('year', 0);
    $sMonth = $request->getParameter('month', 0);
    $sDay = $request->getParameter('day', 0);
    $startDate = "$sYear-$sMonth-$sDay 00:00:00";
    
    $eYear = $request->getParameter('year', 3000);
    $eMonth = $request->getParameter('month', 12);
    $eDay = $request->getParameter('day', 31);
    $endDate = "$eYear-$eMonth-$eDay 23:59:59";
    
    $q->addWhere($rootAlias.'.published_at BETWEEN ? AND ?', array($startDate, $endDate));
  }
  
  public function filterByCategory(Doctrine_Query $q, sfWebRequest $request)
  {
    $rootAlias = $q->getRootAlias();
    $q->addWhere('c.name = ?', $request->getParameter('cat'));
  }
  
  public function filterByTag(Doctrine_Query $q, sfWebRequest $request)
  {
    PluginTagTable::getObjectTaggedWithQuery($q->getRootAlias(), $request->getParameter('tag'), $q, array('nb_common_tag' => 1));
  }

  public function filterByEditable(Doctrine_Query $q, $user_id = null)
  {
    if(is_null($user_id))
    {
      $user_id = sfContext::getInstance()->getUser()->getGuardUser()->getId();
      if(sfContext::getInstance()->getUser()->hasCredential('admin'))
      {
        return ;
      }
    }

    $rootAlias = $q->getRootAlias();
    $q->leftJoin($rootAlias.'.Categories c');
    $q->leftJoin('c.Users u');
    $q->leftJoin($rootAlias.'.Editors e');
    $q->addWhere('author_id = ? OR u.id = ? OR e.id = ?', array($user_id, $user_id, $user_id));
  }

  public function addPublished(Doctrine_Query $q)
  {
    $rootAlias = $q->getRootAlias();
    $q->addWhere($rootAlias.'.status = ? AND '. $rootAlias.'.published_at <= NOW()', 'published');
  }
  
  public function addCategoriesForUser(sfGuardUser $user, $admin = false)
  {
    $q = $this->addCategories();  
    return Doctrine::getTable('aBlogCategory')->addCategoriesForUser($user, $admin, $q);
  }


  public function addCategories(Doctrine_Query $q=null)
  {
    if(is_null($q))
      $q = Doctrine::getTable('aBlogCategory')->createQuery();
      
    $q->andwhere('aBlogCategory.'.$this->categoryColumn .'= ?', true);
    return $q;
  }

  /**
   * Given an array of blogItems this function will populate its virtual page
   * areas with the current slot versions.
   * @param aBlogItem $blogItems
   */
  public static function populatePages($blogItems)
  {    
    $pageIds = array();
    foreach($blogItems as $aBlogItem)
    {
      $pageIds[] = $aBlogItem['page_id'];
    }
    if(count($pageIds))
    {
      $q = aPageTable::queryWithSlots();
      $q->whereIn('id', $pageIds);
      $pages = $q->execute();
      aTools::cacheVirtualPages($pages);
    }
  }

  public static function findOne($params)
  {
    return self::getInstance()->findOneBy('id', $params['id']);
  }

  public function findOneEditable($id, $user_id)
  {
    $q = $this->createQuery()
      ->addWhere('id = ?', $id);
    $this->filterByEditable($q, $user_id);
    return $q->fetchOne();
  }

  // Search for a substring in all event or blog titles. Slug prefix can be
  // @a_event_search_redirect or @a_blog_search_redirect
  
  static public function titleSearch($search, $slugPrefix)
  {
    $q = aPageTable::queryWithTitles();
    $q->addWhere('p.slug LIKE ?', array("$slugPrefix%"));
    $q->addWhere('s.value LIKE ?', array('%'.$search.'%'));
    $q->addWhere('p.archived IS FALSE');
    $virtualPages = $q->execute(array(), Doctrine::HYDRATE_ARRAY);
    $ids = array();
    foreach ($virtualPages as $page)
    {
      if (preg_match("/^$slugPrefix\?id=(\d+)$/", $page['slug'], $matches))
      {
        $ids[] = $matches[1];
      }
    }
    if (!count($ids))
    {
      return array();
    }
    else
    {
      return Doctrine::getTable('aBlogItem')->createQuery('e')->whereIn('e.id', $ids)->execute(array(), Doctrine::HYDRATE_ARRAY);
    }
  }

}