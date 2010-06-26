<?php slot('body_class') ?>a-blog <?php echo $sf_params->get('module'); ?> <?php echo $sf_params->get('action') ?><?php end_slot() ?>

<?php slot('a-subnav') ?>
	<div class="a-subnav-wrapper blog">
		<div class="a-subnav-inner">
	    <?php include_component('aBlog', 'sidebar', array('params' => $params, 'dateRange' => $dateRange, 'categories' => $blogCategories)) ?>
	  </div> 
	</div>
<?php end_slot() ?>

<?php a_area('blog-header', array(
	'allowed_types' => array(
		'aRichText',
	),
  'type_options' => array(
		'aRichText' => array('tool' => 'Main'),
	))) ?>

<div id="a-blog-main" class="a-blog-main">
  <?php if ($sf_params->get('year')): ?>
  <h2><?php echo $sf_params->get('day') ?> <?php echo ($sf_params->get('month')) ? date('F', strtotime(date('Y').'-'.$sf_params->get('month').'-01')) : '' ?> <?php echo $sf_params->get('year') ?></h2>
  <ul class="a-controls a-blog-browser-controls">
    <li><?php echo link_to('Previous', 'aBlog/index?'.http_build_query($params['prev']), array('class' => 'a-arrow-btn icon a-arrow-left', )) ?></li>
    <li><?php echo link_to('Next', 'aBlog/index?'.http_build_query($params['next']), array('class' => 'a-arrow-btn icon a-arrow-right', )) ?></li>
  </ul>
  <?php endif ?>
  
  <?php if($sf_user->isAuthenticated()): ?>
  	<?php echo link_to('New Post', 'aBlogAdmin/new', array('class' => 'a-btn icon big a-add')) ?>
  <?php endif ?>

  <?php foreach ($pager->getResults() as $a_blog_post): ?>
  	<?php echo include_partial('aBlog/post', array('a_blog_post' => $a_blog_post)) ?>
  	<hr />
  <?php endforeach ?>

  <?php if ($pager->haveToPaginate()): ?>
 		<?php echo include_partial('aPager/pager', array('pager' => $pager, 'pagerUrl' => url_for('aBlog/index?'. http_build_query($params['pagination'])))); ?>
  <?php endif ?>

</div>
  
