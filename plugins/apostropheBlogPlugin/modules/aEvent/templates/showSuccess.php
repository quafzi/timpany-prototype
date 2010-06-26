<?php slot('body_class') ?>a-blog <?php echo $sf_params->get('module'); ?> <?php echo $sf_params->get('action') ?><?php end_slot() ?>

<?php slot('a-subnav') ?>
	<div class="a-subnav-wrapper blog">
		<div class="a-subnav-inner">
	    <?php include_component('aEvent', 'sidebar', array('params' => $params, 'dateRange' => $dateRange, 'categories' => $blogCategories, 'reset' => true, 'noFeed' => true)) ?>
	  </div> 
	</div>
<?php end_slot() ?>

<?php echo include_partial('aEvent/post', array('a_event' => $aEvent)) ?>

<?php if($aEvent['allow_comments']): ?><?php endif ?>


