<?php

// A helper class containing methods to be called from subclasses of sfRoute that are
// intended for use with apostrophe engines. Keeping this code here minimizes duplication
// and avoids the need for frequent changes to multiple classes when this code is modified.
// This is poor man's multiple inheritance. See aRoute and aDoctrineRoute

class aRouteTools
{
  /**
   * Returns the portion of the URL after the engine page slug, or false if there
   * is no engine page matching the URL. As a special case, if the URL exactly matches the slug,
   * / is returned.
   *
   * @param  string  $url     The URL
   *
   * @return string The remainder of the URL
   */
  static public function removePageFromUrl(sfRoute $route, $url)
  {
    $remainder = false;
    // Modifies $remainder if it returns a matching page
    $page = aPageTable::getMatchingEnginePage($url, $remainder);
    if (!$page)
    {
      return false;
    }
    // Engine pages can't have subpages, so if the longest matching path for any engine page
    // has the wrong engine type for this route, this route definitely doesn't match
    $defaults = $route->getDefaults();
    if ($page->engine !== $defaults['module'])
    {
      return false;
    }
    // Allows aRoute URLs to be written like ordinary URLs rather than
    // specifying an empty URL, which seems prone to lead to incompatibilities
    
    // Remainder comes back as false, not '', for an exact match
    if (!strlen($remainder))
    {
      $remainder = '/';
    }
    return $remainder;
  }
  
  protected static $targetEnginePages = array();

  /**
   *
   * THIS METHOD WILL NOT WORK RELIABLY UNLESS THE ROUTING CACHE IS TURNED **OFF**.
   *
   * The routing cache defaults to off in new Symfony 1.3 and 1.4 projects because
   * it has found to hurt performance in most cases, sometimes quite severely. We do 
   * not currently enable it on any of our projects.
   * 
   * The routing cache does not take the desired engine page into account, so it will
   * return URLs targeting the wrong page. If you must use the routing cache,
   * design your projects to avoid the use of multiple engine pages for the
   * same engine module.
   *
   * This method sets a specific target engine page for any url_for, link_to, etc. 
   * calls invoking an engine route. If you have only one instance of a given engine 
   * in your site, you don't need to call this method. A link generated within that 
   * engine page will target the same engine page, and a link generated from anywhere 
   * else will target the first engine page for that engine module name found in 
   * the database. If you have more than one engine page for the same engine module 
   * name, and you care which one the link points to, call this method to specify 
   * that page. 
   *
   * A stack of target engine pages is maintained for each engine module name.
   * This allows you to push a new engine page at the top of a partial or component
   * that potentially targets a different engine page than the template that
   * invoked it, and then pop that engine page at the end to ensure that any links
   * generated later in the calling template still target the original engine page.
   *
   * You can pass a page object or, for convenience, a page slug. The latter is useful
   * when targeting an engine page that is guaranteed to exist, such as /admin/media
   *
   * @param  aPage $page|string $page The target engine page for engine routes, or a page slug
   *
   */
  
  static public function pushTargetEnginePage($page)
  {
    if (!(is_object($page) && ($page instanceof aPage)))
    {
      $page = aPageTable::retrieveBySlug($page);
    }
    self::$targetEnginePages[$page->engine][] = $page;
  }

  /**
   * Pops the most recent target engine page for the specified engine name.
   * See aRouteTools::pushTargetEnginePage for more information.
   *
   * @param  string $engine The engine name in question
   *
   */

  static public function popTargetEnginePage($engine)
  {
    array_pop(self::$targetEnginePages[$engine]);
  }
  
  /**
   * Prepends the current CMS page to the URL.
   *
   * @param  string $url The URL so far obtained from parent::generate
   * @param  Boolean $absolute  Whether to generate an absolute URL
   *
   * @return string The generated URL
   */
  
  static public function addPageToUrl(sfRoute $route, $url, $absolute)
  {
    $defaults = $route->getDefaults();
    $currentPage = aTools::getCurrentPage();
    $engine = $defaults['module'];
    if (isset(self::$targetEnginePages[$engine]) && count(self::$targetEnginePages[$engine]))
    {
      $page = end(self::$targetEnginePages[$engine]);
    }
    elseif ((!$currentPage) || ($currentPage->engine !== $defaults['module']))
    {
      $page = aPageTable::getFirstEnginePage($defaults['module']);
    }
    else
    {
      $page = $currentPage;
    }
    if (!$page)
    {
      throw new sfException('Attempt to generate aRoute URL for module ' . $defaults['module'] . ' with no matching engine page on the site');
    }
    // A route URL of / for an engine route maps to the page itself, without a trailing /
    if ($url === '/')
    {
      $url = '';
    }
    // Ditto for / followed by a query string (missed this before)
    if (substr($url, 0, 2) === '/?')
    {
      $url = substr($url, 1);
    }
    $pageUrl = $page->getUrl($absolute);
    // Strip controller off so it doesn't duplicate the controller in the 
    // URL we just generated. We could use the slug directly, but that would
    // break if the CMS were not mounted at the root on a particular site.
    // Take care to function properly in the presence of an absolute URL
    if (preg_match("/^(https?:\/\/[^\/]+)?\/[^\/]+\.php(.*)$/", $pageUrl, $matches))
    {
      $pageUrl = $matches[2];
    }
    return $pageUrl . $url;
  }
}
