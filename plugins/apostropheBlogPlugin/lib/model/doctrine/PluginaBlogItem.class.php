<?php

/**
 * PluginaBlogItem
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class PluginaBlogItem extends BaseaBlogItem
{
  protected $update = true;
  public $engine = 'aBlog';

  /**
   * Doctrine_Record overrides
   */

  /**
   * Deletes a blog item after checking if the user has permission to perform
   * the delete.
   * @param Doctrine_Connection $conn
   * @return boolean
   */
  public function delete(Doctrine_Connection $conn = null)
  {
    $user = sfContext::getInstance()->getUser()->getGuardUser();
    if($this->userHasPrivilege('delete'))
    {
      return parent::delete($conn);
    }
    else
      return false;
  }

  public function postDelete($event)
  {
    $this->Page->delete();
  }

  /**
   * Listener to setup blog item and its virtual page
   * @param <type> $event
   */
  public function postInsert($event)
  {
    // Create a virtual page for this item
    $page = new aPage();
    $page['slug'] = $this->getVirtualPageSlug();
    // Search is good, let it happen
    $page['view_is_secure'] = false;
    // ... But not if we're unpublished
    $page->archived = !($this->status === 'published');
    $page->save();
    $this->Page = $page;

    // Create a slot for the title and add to the virtual page
    $title = $page->createSlot('aText');
    $title->value = 'Untitled';
    $title->save();
    $page->newAreaVersion('title', 'add',
      array(
        'permid' => 1,
        'slot' => $title));

    // Create a slot to index the tags and categories in search.
    $catTag = $page->createSlot('aText');
    $catTag->value = '';
    $catTag->save();
    $page->newAreaVersion('catTag', 'add',
      array(
        'permid' => 1,
        'slot' => $catTag));

    // Make default values for this item
    $this['slug'] = 'untitled-'.$this['id'];
    $this['title'] = 'untitled';
    $this['slug_saved'] = false;

    // This prevents post preupdate from running after the next save
    $this->update = false;
    $this->save();
  }

  /**
   * preUpdate function used to do some slugification.
   * @param <type> $event
   */
  public function preUpdate($event)
  {
    if($this->update)
    {
      // If the slug was altered by the user we no longer want to attempt to sluggify
      // the title to create the slug
      if(array_key_exists('slug', $this->getModified()))
      {
        $this['slug_saved'] = true;
      }

      if($this['slug_saved'] == false && array_key_exists('title', $this->getModified()))
      {
        // If the slug hasn't been altered slugify the title to create the slug
        $this['slug'] = aTools::slugify($this->_get('title'));
      }
      else
      {
        // Otherwise slugify the user entered value.
        $this['slug'] = aTools::slugify($this['slug']);
      }
    }

    $this->Page['view_is_secure'] = ($this['status'] == 'published')? false: true;

    // Check if a blog post or event already has this slug
    $i = 1;
    $slug = $this['slug'];
    while($this->findConflictingItem())
    {
      $this['slug'] = $slug.'-'.$i;
      $i++;
    }
  }

  public function findConflictingItem()
  {
    return Doctrine::getTable(get_class($this))->createQuery()
        ->addWhere('slug = ?', $this['slug'])
        ->addWhere('id != ?', $this['id'])
        ->fetchOne();
  }

  /**
   * Post update function to update the title slot that is saved for search indexing
   * and internationalization purposes.
   * @param <type> $event
   */
  public function postUpdate($event)
  {
    $title = $this->Page->createSlot('aText');
    $title->value = $this->_get('title');
    $title->save();
    $this->Page->newAreaVersion('title', 'update',
      array(
        'permid' => 1,
        'slot' => $title));

    $catTag = $this->Page->createSlot('aText');
    $s = '';
    foreach($this->Categories as $category)
    {
      $s.= $category['name'].' ';
    }
    foreach($this->getTags() as $tag)
    {
      $s.= $tag.' ';
    }
    $catTag->value = $s;
    $catTag->save();
    $this->Page->newAreaVersion('catTag', 'update',
      array(
        'permid' => 1,
        'slot' => $catTag));
    $this->Page->archived = !($this->status === 'published');
    // Search is good, let it happen
    $this->Page->view_is_secure = false;
    $this->Page->save();
  }

  /**
   * These date methods are use in the routing of the permalink
   */
  public function getYear()
  {
    return date('Y', strtotime($this->getPublishedAt()));
  }

  public function getMonth()
  {
    return date('m', strtotime($this->getPublishedAt()));
  }

  public function getDay()
  {
    return date('d', strtotime($this->getPublishedAt()));
  }
  
  public function getFeedSlug()
  {
    return $this['slug'];
  }

  public function getTitle()
  {
    $titleSlot = $this->Page->getSlot('title');
    if ($titleSlot)
    {
      $result = $titleSlot->value;
    }
    else
    {
      $result = $this['slug'];
    }
    $title = trim($result);
    if (!strlen($result))
    {
      // Don't break the UI, return something reasonable
      $slug = $this->slug;
      $title = substr(strrchr($slug, "/"), 1);
    }
    return $title;
  }

  public function setTitle($value)
  {
    $this->_set('title', htmlentities($value, ENT_COMPAT, 'UTF-8'));
  }

  /**
   * Slot content convenience methods
   */

  /**
   * Gets text that should show up in an rss feed
   * @return <type>
   */
  public function getFeedText()
  {
    /**
     * Due to the design of the feed plugin we have to render a partial here even though
     * we are technically in the model layer. RSS needs templating and customizing like everything else
     * we present to the end user
     */
    
    sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
    return get_partial($this->engine . '/' . $this->template . '_rss', array(get_class($this) => $this));
  }

  /**
   * Gets the text for the areas in this item
   * @param int $limit
   * @return string
   */
  public function getText($limit = null)
  {
    return $this->getTextForAreas($this->getAreas(), $limit);
  }

  /**
   *
   * @param string $area Name of an area
   * @param int $limit Number of characters to restrict retrieval to
   * @return string
   */
  public function getTextForArea($area, $limit = null)
  {
    return $this->getTextForAreas(array($area), $limit);
  }

  /**
   *
   * @param string $areas Array of areas to retrieve text for
   * @param int $limit Number of characters to restrict retrieval to
   * @return string
   */
  public function getTextForAreas($areas = array(), $limit = null)
  {
    $text = '';
    foreach($areas as $area)
    {
      foreach($this->Page->getArea($area) as $slot)
      {
        if(method_exists($slot, 'getText'))
        {
          $text .= $slot->getText();
        }
      }
    }
    if(!is_null($limit))
    {
      $text = aString::limitWords($text, $limit, array('append_ellipsis' => true));
    }

    return $text;
  }

  
  /**
   * Returns media for all areas for this items virtual page, this may produce
   * an erroneous result if templates are changed and media is attached to a no
   * longer used area.
   * @param string $type Kind of media to select from (image, video, pdf)
   * @param int $limit
   * @return Array aMediaItem
   */
  public function getMedia($type = 'image', $limit = 5)
  {
    return $this->getMediaForAreas($this->getAreas(), $type, $limit);
  }

  /**
   * Returns media for a given area attached to this items page.
   * @param string $area
   * @param string $type Kind of media to select from (image, video, pdf)
   * @param int $limit
   * @return Array aMediaItem
   */
  public function getMediaForArea($area, $type = 'image', $limit = 5)
  {
    return $this->getMediaForAreas(array($area), $type, $limit);
  }

  /**
   * Checks if this item hasMedia
   * @param string $type Kind of media to select from (image, video, pdf)
   * @return bool
   */
  public function hasMedia($type = 'image', $areas = array())
  {
    if(count($areas))
    {
      return count($this->getMediaForAreas($areas, $type, 1));
    }
    else
    {
      return count($this->getMedia($type, 1));
    }
  }

  /**
   * Given an array of areas this function returns the mediaItems in those areas.
   * @param  aArea $areas
   * @param  $type Set the type of media to return (image, video, pdf, etc...)
   * @param  $limit Limit the number of mediaItems returned
   * @return array aMediaItems
   */
  public function getMediaForAreas($areas, $type = 'image', $limit = 5)
  {
    $aMediaItems = array();
    foreach($areas as $area)
    {
      foreach($this->Page->getArea($area) as $slot)
      {
        foreach($slot->MediaItems as $aMediaItem)
        {
          if(is_null($type) || $aMediaItem['type'] == $type)
          {
            $limit = $limit - 1;
            $aMediaItems[] = $aMediaItem;
            if($limit == 0) return $aMediaItems;
          }
        }
      }
    }
    return $aMediaItems;
  }

  /**
   * Gets the areas for this item as defined in app.yml
   * @return array $areas
   */
  public function getAreas()
  {
    $templates = sfConfig::get('app_'.$this->engine.'_templates');
    return $templates[$this['template']]['areas'];
  }


  /**
   * Publishes a blog post or event if user has permission
   */
  public function publish()
  {
    
    if($this->userHasPrivilege('publish'))
    {
      $this['status'] = 'published';
      if(is_null($this['published_at']))
      {
        $this['published_at'] = date('Y-m-d H:i:s');
      }
      $this->save();
    }
  }


  /**
   * Unpublishes a blog post or event if the user has permission
   */
  public function unpublish()
  {
    if($this->userHasPrivilege('publish'))
    {
      $this['status'] = 'draft';
      $this->save();
    }
  }

  /**
   * Permission methods
   */


  /**
   * Checks whether a user has permission to perform various actions on blog
   * post or event.
   *
   * @param string $privilege
   * @return boolean
   */
  public function userHasPrivilege($privilege = 'publish')
  {
    $user = sfContext::getInstance()->getUser();

    if(!$user->isAuthenticated())
      return false;
    
    if($user->hasCredential('admin'))
      return true;

    if($user->getGuardUser()->getId() == $this['author_id'])
      return true;
    
    if($privilege == 'edit')
    {
      return $this->userCanEdit($user->getGuardUser());
    }

    return false;
  }

  /**
   * Checks if a user can edit this post
   * @param sfGuardUser $user
   * @return <type>
   */
  public function userCanEdit(sfGuardUser $user)
  {
    $q = $this->getTable()->createQuery()
      ->addWhere('id = ?', $this['id']);
    Doctrine::getTable('aBlogItem')->filterByEditable($q, $user['id']);
    return count($q->execute());
  }

  /**
   * This function attempts to find the "best" engine to route a given post to.
   * based on the categories that are used on various engine pages.
   *
   * @return aPage the best engine page
   */
  public function findBestEngine()
  {
    $engines = Doctrine::getTable('aPage')->createQuery()
      ->addWhere('engine = ?', $this->engine)
      ->addWhere('admin != ?', true)
      ->execute();

    if(count($engines) == 0)
      return '';
    else if(count($engines) == 1)
      return $engines[0];

    //When there are more than one engine page we need to use some heuristics to
    //guess what the best page is.
    $catIds = array();
    foreach($this->Categories as $category)
    {
      $catIds[$category['id']] = $category['id'];
    }

    if(count($catIds) < 1)
      return $engines[0];

    $best = array(-1, '');
    
    foreach($engines as $engine)
    {
      $score = 0;
      foreach($engine->BlogCategories as $category)
      {
        if(isset($catIds[$category['id']]))
          $score = $score + 1;
      }
      if($score > $best[0])
      {
        $best = array($score, $engine);
      }
    }
    
    return $best[1];
  }
}