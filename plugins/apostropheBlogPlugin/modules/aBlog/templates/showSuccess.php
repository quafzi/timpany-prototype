<?php slot('body_class') ?>a-blog <?php echo $sf_params->get('module'); ?> <?php echo $sf_params->get('action') ?><?php end_slot() ?>

<?php slot('a-subnav') ?>
	<div class="a-subnav-wrapper blog">
		<div class="a-subnav-inner">
	    <?php include_component('aBlog', 'sidebar', array('params' => $params, 'dateRange' => $dateRange, 'categories' => $blogCategories, 'reset' => true, 'noFeed' => true)) ?>
	  </div> 
	</div>
<?php end_slot() ?>

<?php echo include_partial('aBlog/post', array('a_blog_post' => $aBlogPost)) ?>

<?php if($aBlogPost['allow_comments']): ?><?php endif ?>