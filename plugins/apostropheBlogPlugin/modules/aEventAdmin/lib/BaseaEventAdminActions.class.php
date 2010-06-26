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
    $search = $request->getParameter('q', '');
    $q = Doctrine::getTable('aEvent')->createQuery()
      ->andWhere("title LIKE ?", '%'.$search.'%');
    Doctrine::getTable('aEvent')->addPublished($q);
    $this->aEvents = $q->execute();
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
    if(!aPageTable::getFirstEnginePage('aBlog'))
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