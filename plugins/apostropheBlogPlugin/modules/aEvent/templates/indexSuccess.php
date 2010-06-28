<?php slot('body_class') ?>a-blog <?php echo $sf_params->get('module'); ?> <?php echo $sf_params->get('action') ?><?php end_slot() ?>

<?php slot('a-subnav') ?>
	<div class="a-subnav-wrapper blog">
		<div class="a-subnav-inner">
	    <?php include_component('aEvent', 'sidebar', array('params' => $params, 'dateRange' => $dateRange, 'categories' => $blogCategories)) ?>
	  </div> 
	</div>
<?php end_slot() ?>

<div id="a-blog-main" class="a-blog-main">
  <?php if ($sf_params->get('year')): ?>
  <h2><?php echo $sf_params->get('day') ?> <?php echo ($sf_params->get('month')) ? date('F', strtotime(date('Y').'-'.$sf_params->get('month').'-01')) : '' ?> <?php echo $sf_params->get('year') ?></h2>
  <ul class="a-controls a-blog-browser-controls">
    <li><?php echo link_to('Previous', 'aEvent/index?'.http_build_query($params['prev']), array('class' => 'a-arrow-btn icon a-arrow-left', )) ?></li>
    <li><?php echo link_to('Next', 'aEvent/index?'.http_build_query($params['next']), array('class' => 'a-arrow-btn icon a-arrow-right', )) ?></li>
  </ul>
  <?php endif ?>
  
  <?php if($sf_user->isAuthenticated()): ?>
  	<?php echo link_to('New Event', '@a_event_admin_new', array('class' => 'a-btn icon big a-add')) ?>
  <?php endif ?>

  <?php foreach ($pager->getResults() as $a_event): ?>
  	<?php echo include_partial('aEvent/post', array('a_event' => $a_event, 'edit' => false, )) ?>
  	<hr />
  <?php endforeach ?>

    <?php if ($pager->haveToPaginate()): ?>
 		<?php echo include_partial('aPager/pager', array('pager' => $pager, 'pagerUrl' => url_for('aEvent/index?'. http_build_query($params['pagination'])))); ?>
  <?php endif ?>
</div>
  
