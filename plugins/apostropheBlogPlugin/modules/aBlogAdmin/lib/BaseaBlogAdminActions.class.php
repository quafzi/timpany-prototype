<?php
require_once dirname(__FILE__).'/aBlogAdminGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/aBlogAdminGeneratorHelper.class.php';
/**
 * Base actions for the aBlogPlugin aBlogAdmin module.
 * 
 * @package     aBlogPlugin
 * @subpackage  aBlogAdmin
 * @author      Dan Ordille <dan@punkave.com>
 */
abstract class BaseaBlogAdminActions extends autoABlogAdminActions
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
    $this->a_blog_post = new aBlogPost();
    $this->a_blog_post->Author = $this->getUser()->getGuardUser();
    $this->a_blog_post->save();
    $this->redirect('a_blog_admin_edit', $this->a_blog_post);
  }
    
  public function executeAutocomplete(sfWebRequest $request)
  {
    $this->aBlogPosts = aBlogItemTable::titleSearch($request->getParameter('q'), '@a_blog_search_redirect');
    $this->setLayout(false);
  }
  
  public function executeUpdate(sfWebRequest $request)
  {
    if($this->getUser()->hasCredential('admin'))
    {
      $this->a_blog_post = $this->getRoute()->getObject();
    }
    else
    {
      $this->a_blog_post = Doctrine::getTable('aBlogPost')->findOneEditable($request->getParameter('id'), $this->getUser()->getGuardUser()->getId());
    }
    $this->forward404Unless($this->a_blog_post);
    $this->form = $this->configuration->getForm($this->a_blog_post);

    if($request->isXmlHttpRequest())
    {
      $this->setLayout(false);
      $response = array();
      $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
      if ($this->form->isValid())
      {
        $this->a_blog_post = $this->form->save();
        //We need to recreate the form to handle the fact that it is not possible to change the value of a sfFormField
        $this->form = $this->configuration->getForm($this->a_blog_post);
        $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $this->a_blog_post)));
      }
      
      $response['errors'] = $this->form->getErrorSchema()->getErrors();
      aPageTable::queryWithTitles()
        ->addWhere('page_id = ?', $this->a_blog_post['page_id'])
        ->execute();
      $response['aBlogPost'] = $this->a_blog_post->toArray();
      // We need to decode the title because jQuery will be stuffing it in with .value, which
      // doesn't need the escaping
      $response['aBlogPost']['title'] = html_entity_decode($response['aBlogPost']['title'], ENT_COMPAT, 'UTF-8');
      $response['modified'] = $this->a_blog_post->getLastModified();
      $response['time'] = aDate::time($this->a_blog_post['updated_at']);
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
    $aBlogPost = $this->getRoute()->getObject();
    aRouteTools::pushTargetEnginePage($aBlogPost->findBestEngine());
    $this->redirect($this->generateUrl('a_blog_post', $this->getRoute()->getObject()));
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
      $this->a_blog_post = $this->getRoute()->getObject();
    }
    else
    {
      $this->a_blog_post = Doctrine::getTable('aBlogPost')->findOneEditable($request->getParameter('id'), $this->getUser()->getGuardUser()->getId());
    }
    $this->forward404Unless($this->a_blog_post);
    $this->form = $this->configuration->getForm($this->a_blog_post);

    aBlogItemTable::populatePages(array($this->a_blog_post));
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
