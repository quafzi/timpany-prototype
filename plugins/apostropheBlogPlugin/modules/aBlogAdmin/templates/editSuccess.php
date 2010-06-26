<?php use_helper('I18N', 'Date', 'jQuery', 'a') ?>
<?php include_partial('assets') ?>
<?php slot('body_class') ?>a-admin a-blog-admin <?php echo $sf_params->get('module'); ?> <?php echo $sf_params->get('action') ?> <?php echo $a_blog_post['template'] ?><?php end_slot() ?>

<div class="a-admin-container <?php echo $sf_params->get('module') ?>">
	
	<?php slot('a-subnav') ?>
		<div class="a-subnav-wrapper blog">
			<div class="a-subnav-inner">	
				<ul class="a-admin-action-controls">
					<li><a href="<?php echo url_for('@a_blog_admin'); ?>" class="a-btn big alt"><?php echo __('View All Posts', array(), 'apostrophe-blog') ?></a></li>
	         <?php include_partial('list_actions', array('helper' => $helper)) ?>
				</ul>
			  <?php include_partial('aBlogAdmin/form_bar') ?>				
				<div class="a-admin-title-sentence">
					<h3 class="new-item"><?php echo __('You are creating a new post.', array(), 'apostrophe_blog') ?></h3>
					<h3 class="draft-item"><?php echo __('You are working on a draft post.', array(), 'apostrophe_blog') ?></h3>					
					<h3 class="published-item"><?php echo __('You are editing a published post.', array(), 'apostrophe_blog') ?></h3>
					<span class="flash-message"> <?php echo __('Post Saved at', array(), 'apostrophe_blog') ?></span>
				</div>
			</div> 
	  </div>
	<?php end_slot() ?>
  
  <?php include_partial('flashes') ?>
	
	<div class="a-admin-content main">	
		
		<?php if (0): ?> <?php // We aren't using status messages right now ?>
			<dl id="a-blog-item-status-messages"></dl>
		<?php endif ?>
		
		<div id="a-blog-item-title-interface" class="a-blog-item-title-interface">
			<input type="text" id="a_blog_item_title_interface" value="<?php echo ($a_blog_post->title == 'untitled')? '':$a_blog_post->title ?>" />
			<div id="a-blog-item-title-placeholder"><?php echo __('Title your post...', array(), 'apostrophe-blog') ?></div>
		  <ul class="a-controls blog-title">
		    <li><a href="#" class="a-btn a-save big"><?php echo __('Save', array(), 'apostrophe_blog') ?></a></li>
		    <li><a href="#" class="a-btn a-cancel no-label big"><?php echo __('Cancel', array(), 'apostrophe_blog') ?></a></li>
		  </ul>				
		</div>		

		<div id="a-blog-item-permalink-interface">
			<h6>Permalink:</h6> 
			<div class="a-blog-item-permalink-wrapper url">
        <span><?php echo aTools::urlForPage($a_blog_post->findBestEngine()->getSlug()).'/' ?></span><?php // Dan, Can you echo the REAL URL prefix here -- I don't know how to build a URL based on the complex blog route business we are doing  ?>
			</div>
			<div class="a-blog-item-permalink-wrapper slug">
				<input type="text" name="a_blog_item_permalink_interface" value="<?php echo $a_blog_post->slug ?>" id="a_blog_item_permalink_interface">
			  <ul class="a-controls blog-slug">
			    <li><a href="#" class="a-btn a-save mini"><?php echo __('Save', array(), 'apostrophe_blog') ?></a></li>
			    <li><a href="#" class="a-btn a-cancel no-label mini"><?php echo __('Cancel', array(), 'apostrophe_blog') ?></a></li>
			  </ul>				
			</div>
		</div>

		<div class="a-blog-item post<?php echo ($a_blog_post->hasMedia())? ' has-media':''; ?> <?php echo $a_blog_post->getTemplate() ?>">
  		<?php include_partial('aBlog/'.$a_blog_post->getTemplate(), array('a_blog_post' => $a_blog_post, 'edit' => true)) ?>
		</div>

  </div>

  <div class="a-admin-sidebar">
    <div id='a-admin-blog-post-form'>
    <?php include_partial('form', array('a_blog_post' => $a_blog_post, 'form' => $form)) ?>
    </div>
  </div>
  
	<?php if (isset($a_blog_post['updated_at'])): ?>
		<div id="post-last-saved" class="post-updated-at option">
			<h6>
					<b>Last Saved:</b>
					<span></span>
			</h6>		
		</div>
	<?php endif ?>
	
  <div class="a-admin-footer">
    <?php include_partial('form_footer', array('a_blog_post' => $a_blog_post, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>
  </form>
<?php //include_partial('form_actions', array('a_blog_post' => $a_blog_post, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
</div>