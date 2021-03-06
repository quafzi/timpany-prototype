<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginaPageTable extends Doctrine_Table
{
	// We always join with all of the current slots for the proper culture in this simplest page-getter method. 
	// Otherwise we wreck the slot cache for slots on the page, etc., can't see titles or see the wrong versions 
	// and cultures of slots. This is inefficient in some situations, but the
	// right response to that is to recognize when you're about to fetch a page
	// that has already been fetched and just reuse it. I can't make that call
	//f or you at the model level

  static public function retrieveBySlug($slug, $culture = null)
  {
    return self::retrieveBySlugWithSlots($slug, $culture);
  }

	// CAREFUL: if you are not absolutely positive that you won't need other slots for this
	// page (ie it is NOT the current page), then don't use this. Use retrieveBySlugWithSlots
	
  // If culture is null you get the current user's culture,
  // or sf_default_culture if none is set or we're running in a task context
  static public function retrieveBySlugWithTitles($slug, $culture = null)
  {
    if (is_null($culture))
    {
      $culture = aTools::getUserCulture();
    }
    $query = self::queryWithTitles($culture);
    $page = $query->
      where('p.slug = ?', $slug)->
      fetchOne();
    // In case Doctrine is clever and returns the same page object
    if ($page)
    {
      $page->clearSlotCache();
      $page->setCulture($culture);
    }
    return $page;
  }
  
  // If culture is null you get the current user's culture,
  // or sf_default_culture if none is set or we're running in a task context
  static public function retrieveBySlugWithSlots($slug, $culture = null)
  {
    if (is_null($culture))
    {
      $culture = aTools::getUserCulture();
    }
    $query = self::queryWithSlots(false, $culture);
    $page = $query->
      where('p.slug = ?', $slug)->
      fetchOne();
    // In case Doctrine is clever and returns the same page object
    if ($page)
    {
      $page->clearSlotCache();
      $page->setCulture($culture);
    }
    return $page;
  }
  // If culture is null you get the current user's culture,
  // or sf_default_culture if none is set or we're running in a task context

  static public function queryWithTitles($culture = null)
  {
    return self::queryWithSlot('title', $culture);
  }
  
  // This is a slot name, like 'title'
  static public function queryWithSlot($slot, $culture = null)
  {
    if (is_null($culture))
    {
      $culture = aTools::getUserCulture();
    }
    return Doctrine_Query::Create()->
      select("p.*, a.*, v.*, avs.*, s.*")->
      from("aPage p")->
      leftJoin('p.Areas a WITH (a.name = ? AND a.culture = ?)', array($slot, $culture))->
      leftJoin('a.AreaVersions v WITH (a.latest_version = v.version)')->
      leftJoin('v.AreaVersionSlots avs')->
      leftJoin('avs.Slot s');
  }

  // This is a slot type, like 'aRichText'
  static public function queryWithSlotType($slotType, $culture = null)
  {
    if (is_null($culture))
    {
      $culture = aTools::getUserCulture();
    }
    return Doctrine_Query::Create()->
      select("p.*, a.*, v.*, avs.*, s.*")->
      from("aPage p")->
      leftJoin('p.Areas a WITH (a.culture = ?)', array($culture))->
      leftJoin('a.AreaVersions v WITH (a.latest_version = v.version)')->
      leftJoin('v.AreaVersionSlots avs')->
      leftJoin('avs.Slot s WITH (s.type = ?)', array($slotType));
  }
 
  // If culture is null you get the current user's culture,
  // or sf_default_culture if none is set or we're running in a task context

  static public function retrieveByIdWithSlots($id, $culture = null)
  {
    return self::retrieveByIdWithSlotsForVersion($id, false, $culture);
  }
  // If culture is null you get the current user's culture,
  // or sf_default_culture if none is set or we're running in a task context

  static public function retrieveByIdWithSlotsForVersion($id, $version, $culture = null)
  {
    if (is_null($culture))
    {
      $culture = aTools::getUserCulture();
    }
    $page = self::queryWithSlots($version, $culture)->
      where('p.id = ?', array($id))->
      fetchOne();
    // In case Doctrine is clever and returns the same page object
    if ($page)
    {
      $page->clearSlotCache();
      // Thanks to Quentin Dugauthier for spotting that there were
      // still instances of this not being inside the if
      $page->setCulture($culture);
    }
    return $page;
  }

  // If version is false you get the latest version of each slot.
  
  // If culture is null you get the current user's culture,
  // or sf_default_culture if none is set or we're running in a task context
  
  // If culture is 'all' you get all cultures. This option is only for use in low level
  // queries such as the implementation of the a:refresh task and will not 
  // work as expected for page rendering purposes. Normally you never fetch all culture slots
  // at once
  
  // Also brings in related media objects since the assumption is that you are actually
  // rendering a page. See queryWithTitles and, better yet, the getChildrenInfo() method
  // and its relatives for efficient ways to find out information about other pages quickly

  static public function queryWithSlots($version = false, $culture = null)
  {
    if (is_null($culture))
    {
      $culture = aTools::getUserCulture();
    }
    $query = Doctrine_Query::Create()->
      select("p.*, a.*, v.*, avs.*, s.*, m.*")->
      from("aPage p");
    if ($culture === 'all')
    {
      $query = $query->leftJoin('p.Areas a');
    }
    else
    {
      $query = $query->leftJoin('p.Areas a WITH a.culture = ?', array($culture));
    }
    if ($version === false)
    {
      $query = $query->
        leftJoin('a.AreaVersions v WITH (a.latest_version = v.version)');
    }
    else
    {
      $query = $query->
        leftJoin('a.AreaVersions v WITH (v.version = ?)', array($version));
    }
    return $query->leftJoin('v.AreaVersionSlots avs')->
      leftJoin('avs.Slot s')->
      leftJoin('s.MediaItems m')->
      orderBy('avs.rank asc');
  }
  
  
  
  static private $treeObject = null;
  
  static public function treeTitlesOn()
  {
    self::treeSlotOn('title');
  }
  
  static public function treeSlotOn($slot)
  {
    $query = aPageTable::queryWithSlot($slot);
    self::$treeObject = Doctrine::getTable('aPage')->getTree();
    // I'm not crazy about how I have to set the base query and then
    // reset it, instead of simply passing it to getChildren. A
    // Doctrine oddity
    self::$treeObject->setBaseQuery($query);
  }
  
  static public function treeTitlesOff()
  {
    self::treeSlotOff();
  }
  
  static public function treeSlotOff()
  {
    self::$treeObject->resetBaseQuery();
  } 
  
  public function getLuceneIndexFile()
  {
    return aZendSearch::getLuceneIndexFile($this);
  }

  public function getLuceneIndex()
  {
    return aZendSearch::getLuceneIndex($this);
  }

  // This does the entire thing at one go, which may be too memory intensive.
  // The apostrophe:rebuild-search-index task instead invokes apostrophe:update-search-index
  // for batches of 100 pages
  public function rebuildLuceneIndex()
  {
    aZendSearch::purgeLuceneIndex($this);
    $pages = $this->createQuery('p')->innerJoin('p.Areas a')->execute(array(), Doctrine::HYDRATE_ARRAY);
    foreach ($pages as $page)
    {
      $cultures = array();
      foreach ($page['Areas'] as $area)
      {
        $cultures[$area['culture']] = true; 
      }
      $cultures = array_keys($cultures);
      foreach ($cultures as $culture)
      {
        $cpage = self::retrieveBySlugWithSlots($page['slug'], $culture);
        $cpage->updateLuceneIndex();
      }
    }
  }
  
  public function addSearchQuery(Doctrine_Query $q = null, $luceneQuery)
  {
    // Page searches are always specific to this user's culture
    $culture = aTools::getUserCulture();
    $luceneQuery = "+(text:($luceneQuery))";
    return aZendSearch::addSearchQuery($this, $q, $luceneQuery, $culture);
  }
  
  public function addSearchQueryWithScores(Doctrine_Query $q = null, $luceneQuery, &$scores)
  {
    // Page searches are always specific to this user's culture
    $culture = aTools::getUserCulture();
    $luceneQuery = "+(text:($luceneQuery))";
    return aZendSearch::addSearchQueryWithScores($this, $q, $luceneQuery, $culture, $scores);
  }
  
  // Just a hook used by the above
  public function searchLucene($query, $culture)
  {
    return aZendSearch::searchLucene($this, $query, $culture);
  }
  
  // Just a hook used by the above
  public function searchLuceneWithScores($query, $culture)
  {
    return aZendSearch::searchLuceneWithScores($this, $query, $culture);
  }

  // Returns engine page with the longest matching path.
  // We use a cache so that we don't wind up making separate queries
  // for every engine route in the application
  
  protected static $engineCacheUrl = false;
  protected static $engineCachePage = false;
  protected static $engineCacheRemainder = false;
  protected static $engineCacheFirstEnginePages = array();
  protected static $engineCachePagePrefix = false;
  
  static public function getMatchingEnginePage($url, &$remainder)
  {
    // Engines won't work on sites where the CMS is not mounted at the root of the site
    // unless we examine the a_page route to determine a prefix. Generate the route properly
    // then lop off the controller name, if any
    
    if ($url === self::$engineCacheUrl)
    {
      $remainder = self::$engineCacheRemainder;
      return self::$engineCachePage;
    }
    
    if (self::$engineCachePagePrefix)
    {
      $prefix = self::$engineCachePagePrefix;
    }
    else
    {
      $prefix = '';
      $dummyUrl = sfContext::getInstance()->getRouting()->generate('a_page', array('slug' => 'dummy'), false);
    
      if (preg_match("/^(\/\w+\.php)?(.*)\/dummy$/", $dummyUrl, $matches))
      {
        $prefix = $matches[2];
      }
      self::$engineCachePagePrefix = $prefix;
    }
    $url = preg_replace('/^' . preg_quote($prefix, '/') . '/', '', $url);
    
    $urls = array();
    // Remove any query string
    $twig = preg_replace('/\?.*$/', '', $url);
    while (true)
    {
      if (($twig === '/') || (!strlen($twig)))
      {
        // Either we've been called for the home page, or we just
        // stripped the first slash and are now considering the home page
        $urls[] = '/';
        break;
      }
      $urls[] = $twig;
      if (!preg_match('/^(.*)\/[^\/]+$/', $twig, $matches))
      {
        break;
      }
      $twig = $matches[1];
    }
    $page = Doctrine_Query::create()->
      select('p.*, length(p.slug) as len')->
      from('aPage p')->
      whereIn('p.slug', $urls)->
      andWhere('p.engine IS NOT NULL')->
      orderBy('len desc')->
      limit(1)->
      fetchOne();
    self::$engineCachePage = $page;
    self::$engineCacheUrl = $url;
    self::$engineCacheRemainder = false;
    if ($page)
    {
      $remainder = substr($url, strlen($page->slug));
      self::$engineCacheRemainder = $remainder;
      return $page;
    }
    return false;
  }
  
  // Used when generating an engine link from a page other than the engine page itself.
  // Many engines are only placed in one location per site, so this is often reasonable.
  // Cache this for acceptable performance. Admin pages match first to ensure that the
  // Apostrophe menu always goes to the right place. If you have a public version of the same
  // engine and you want to link to it via link_to(), target it explicitly, see
  // aRouteTools::pushTargetEnginePage()
  
  static public function getFirstEnginePage($engine)
  {
    if (isset(self::$engineCacheFirstEnginePages[$engine]))
    {
      return self::$engineCacheFirstEnginePages[$engine];
    }
    $page = Doctrine_Query::create()->
     from('aPage p')->
     where('p.engine = ?', array($engine))->
     limit(1)->
     fetchOne();
    self::$engineCacheFirstEnginePages[$engine] = $page;
    return $page;
  }
  
  // Useful with queries aimed at finding a page; avoids the 
  // considerable expense of hydrating it
  static public function fetchOneSlug($query)
  {
    $query->limit(1);
    $data = $query->fetchArray();
    if (!count($data))
    {
      return false;
    }
    return $data[0]['slug'];
  }
  
  // Wnat to extend privilege checks? Override checkUserPrivilegeBody(). Read on for details
  
  // Check whether the user has sufficient privileges to access a page. This includes
  // checking explicit privileges in the case of pages that have them on sites where
  // there is a 'candidate group' for that privilege. $pageOrInfo can be a
  // Doctrine aPage object or an info structure like those returned by getAncestorsInfo() etc.
  
  // Sometimes you can't afford the overhead of an aPage object, thus this method.
  
  static $privilegesCache = array();
  
  // Static methods are tricky to override in PHP. Get an instance of the table and call a new
  // non-static version
  
  static public function checkPrivilege($privilege, $pageOrInfo, $user = false)
  {
    $table = Doctrine::getTable('aPage');
    return $table->checkUserPrivilege($privilege, $pageOrInfo, $user);
  }
  
  public function checkUserPrivilege($privilege, $pageOrInfo, $user)
  {
    if ($user === false)
    {
      $user = sfContext::getInstance()->getUser();
    }
    
    $username = false;
    if ($user->getGuardUser())
    {
      $username = $user->getGuardUser()->getUsername();
    }

    if (isset(self::$privilegesCache[$username][$privilege][$pageOrInfo['id']]))
    {
      return self::$privilegesCache[$username][$privilege][$pageOrInfo['id']];
    }

    // Archived pages can only be visited by users who are permitted to edit them.
    // This trumps the less draconian privileges for viewing pages, locked or otherwise
    if (($privilege === 'view') && $pageOrInfo['archived'])
    {
      $privilege = 'edit';
    }
    else
    {
      // Individual pages can be conveniently locked for 
      // viewing purposes on an otherwise public site. This is
      // implemented as a separate permission. 
      if (($privilege === 'view') && $pageOrInfo['view_is_secure'])
      {
        $privilege = 'view_locked';
      }
    }

    $result = $this->checkUserPrivilegeBody($privilege, $pageOrInfo, $user, $username);
    self::$privilegesCache[$username][$privilege][$pageOrInfo['id']] = $result;
    return $result;
  }
  
  // The privilege name has already been transformed if appropriate. The username has already been fetched
  // (false for a logged out user). The cache has already been checked. The return value of this call will
  // be cached by the checkUserPrivilege method. Override this method, calling the parent first and then
  // adding further checks as you deem necessary
  
  public function checkUserPrivilegeBody($privilege, $pageOrInfo, $user, $username)
  {
    $result = false;
    
    // Rule 1: admin can do anything
    // Work around a bug in some releases of sfDoctrineGuard: users sometimes
    // still have credentials even though they are not logged in
    if ($user->isAuthenticated() && $user->hasCredential('cms_admin'))
    {
      $result = true;
    }
    else
    {
      $privileges = explode("|", $privilege);
      foreach ($privileges as $privilege)
      {
    
        $sufficientCredentials = sfConfig::get(
            "app_a_$privilege" . "_sufficient_credentials", false);
        $sufficientGroup = sfConfig::get(
            "app_a_$privilege" . "_sufficient_group", false);
        $candidateGroup = sfConfig::get(
            "app_a_$privilege" . "_candidate_group", false);
        // By default users must log in to do anything, except for viewing an unlocked page
        $loginRequired = sfConfig::get(
            "app_a_$privilege" . "_login_required", 
            ($privilege === 'view' ? false : true));

        // Rule 2: if no login is required for the site as a whole for this
        // privilege, anyone can do it...
        if (!$loginRequired)
        {
          $result = true;
          break;
        }

        // Corollary of rule 2: if login IS required and you're not
        // logged in, bye-bye
        if (!$user->isAuthenticated())
        {
          continue;
        }

        // Rule 3: if there are no sufficient credentials and there is no
        // required or sufficient group, then login alone is sufficient. Common 
        // on sites with one admin
        if (($sufficientCredentials === false) && ($candidateGroup === false) && ($sufficientGroup === false))
        {
          // Logging in is the only requirement
          $result = true;
          break;
        }

        // Rule 4: if the user has sufficient credentials... that's sufficient!
        // Many sites will want to simply say 'editors can edit everything' etc
        if ($sufficientCredentials && 
          ($user->hasCredential($sufficientCredentials)))
        {
          $result = true;
          break;
        }
        if ($sufficientGroup && 
          ($user->hasGroup($sufficientGroup)))
        {
          $result = true;
          break;
        }

        // Rule 5: if there is a candidate group, make sure the user is a member
        // before checking for explicit privileges for that user
        if ($candidateGroup && 
          (!$user->hasGroup($candidateGroup)))
        {
          continue;
        }
    
        // The explicit case
    
        $user_id = $user->getGuardUser()->getId();
        
        $accesses = Doctrine_Query::create()->
          select('a.*')->from('aAccess a')->innerJoin('a.Page p')->
          where("(p.lft <= " . $pageOrInfo['lft'] . " AND p.rgt >= " . $pageOrInfo['rgt'] . ") AND " .
            "a.user_id = $user_id AND a.privilege = ?", array($privilege))->
          limit(1)->
          execute(array(), Doctrine::HYDRATE_ARRAY);
        if (count($accesses) > 0)
        {
          $result = true;
          break;
        }
      }
    }
    return $result;
  }
}
