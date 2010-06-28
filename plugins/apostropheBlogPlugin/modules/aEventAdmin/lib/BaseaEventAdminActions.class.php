<?php
require_once dirname(__FILE__).'/aEventAdminGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/aEventAdminGeneratorHelper.class.php';
/**
 * Base actions for the aEventPlugin aEventAdmin module.
 * 
 * @package     aEventPlugin
 * @subpackage  aEventAdmin
 * @author      Dan Ordille <dan@punkave.com>
 */
abstract class BaseaEventAdminActions extends autoAEventAdminActions
{ 
  
  public function preExecute()
  {
    parent::preExecute();
    if(sfConfig::get('app_aBlog_use_bundled_assets', true))
    {
      $this->getResponse()->addStylesheet('/apostropheBlogPlugin/css/aBlog.css');
      $this->getResponse()->addJavascript('/apostropheBlogPlugin/js/aBlog.js');
    }
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->a_event = new aEvent();
    $this->a_event->Author = $this->getUser()->getGuardUser();
    $this->a_event->save();
    $this->redirect('a_event_admin_edit',$this->a_event);
  }
    
  public function executeAutocomplete(sfWebRequest $request)
  {
    // Search is in virtual pages, the TITLE field is dead (or going to be) and not
    // I18N, we have to cope with that correctly. I tried to use Zend Search but we
    // can't easily distinguish blog pages from the rest and that seems to be a deeper
    // architectural problem. I still had to fix a few things in PluginaBlogItem which
    // was locking the virtual pages down and making them unsearchable by normal mortals.
    // Now it locks them down only when they are not status = published. Republish things
    // to get the benefit of this on existing sites
    
    $this->aEvents = aBlogItemTable::titleSearch($request->getParameter('q'), '@a_event_search_redirect');
    $this->setLayout(false);
  }
  
  public function executeUpdate(sfWebRequest $request)
  {
    if($this->getUser()->hasCredential('admin'))
    {
      $this->a_event = $this->getRoute()->getObject();
    }
    else
    {
      $this->a_event = Doctrine::getTable('aEvent')->findOneEditable($request->getParameter('id'), $this->getUser()->getGuardUser()->getId());
    }
    $this->forward404Unless($this->a_event);
    $this->form = $this->configuration->getForm($this->a_event);

    if($request->isXmlHttpRequest())
    {
      $this->setLayout(false);
      $response = array();
      $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
      if ($this->form->isValid())
      {
        $this->a_event = $this->form->save();
        //We need to recreate the form to handle the fact that it is not possible to change the value of a sfFormField
        $this->form = $this->configuration->getForm($this->a_event);
        $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $this->a_event)));
      }

      $response['errors'] = $this->form->getErrorSchema()->getErrors();
      aPageTable::queryWithTitles()
        ->addWhere('page_id = ?', $this->a_event['page_id'])
        ->execute();
      $response['aBlogPost'] = $this->a_event->toArray();
      $response['aBlogPost']['title'] = html_entity_decode($response['aBlogPost']['title'], ENT_COMPAT, 'UTF-8');
      $response['modified'] = $this->a_event->getLastModified();
      $response['time'] = aDate::time($this->a_event['updated_at']);
      //Any additional messages can go here
      $output = json_encode($response);
      $this->getResponse()->setHttpHeader("X-JSON", '('.$output.')');
      return sfView::HEADER_ONLY;
    }
    else
    {
      $this->processForm($request, $this->form);
    }
    $this->setTemplate('edit');
  }

  public function executeRedirect()
  {
    $aEvent = $this->getRoute()->getObject();
    aRouteTools::pushTargetEnginePage($aEvent->findBestEngine());
    $url = $this->generateUrl('a_event_post', $aEvent);
    $this->redirect($url);
  }

  public function executeCategories()
  {
    $this->redirect('@a_blog_category_admin');
  }

  public function executeIndex(sfWebRequest $request)
  {
    if(!aPageTable::getFirstEnginePage('aEvent'))
    {
      $this->setTemplate('engineWarning');
    }

    parent::executeIndex($request);
    aBlogItemTable::populatePages($this->pager->getResults());
  }

  public function executeEdit(sfWebRequest $request)
  {
    if($this->getUser()->hasCredential('admin'))
    {
      $this->a_event = $this->getRoute()->getObject();
    }
    else
    {
      $this->a_event = Doctrine::getTable('aEvent')->findOneEditable($request->getParameter('id'), $this->getUser()->getGuardUser()->getId());
    }
    $this->forward404Unless($this->a_event);
    $this->form = $this->configuration->getForm($this->a_event);
    aBlogItemTable::populatePages(array($this->a_event));
  }

  protected function buildQuery()
  {
    $query = parent::buildQuery();
    $query->leftJoin($query->getRootAlias().'.Author')
      ->leftJoin($query->getRootAlias().'.Editors')
      ->leftJoin($query->getRootAlias().'.Categories')
      ->leftJoin($query->getRootAlias().'.Page');
    return $query;
  }
}