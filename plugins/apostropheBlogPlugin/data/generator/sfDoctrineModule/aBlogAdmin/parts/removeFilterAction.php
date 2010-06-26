  public function executeRemoveFilter(sfWebRequest $request)
  {
    $name = $request->getParameter('name');
    $value = $request->getParameter('value');
    
    $filters = $this->getUser()->getAttribute('<?php echo $this->getModuleName() ?>.filters', $this->configuration->getFilterDefaults(), 'admin_module');
    $filters[$name] = null;
    $this->getUser()->setAttribute('<?php echo $this->getModuleName() ?>.filters', $filters, 'admin_module');
    
    $this->redirect('@<?php echo $this->getUrlForAction('list') ?>');
  }