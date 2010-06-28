<?php use_helper('I18N', 'Date', 'jQuery', 'a') ?>
<?php include_partial('assets') ?>
<?php slot('body_class') ?>a-admin a-blog-admin <?php echo $sf_params->get('module'); ?> <?php echo $sf_params->get('action') ?> <?php echo $a_event['template'] ?><?php end_slot() ?>

<div class="a-admin-container <?php echo $sf_params->get('module') ?>">

	<?php slot('a-subnav') ?>
		<div class="a-subnav-wrapper blog">
			<div class="a-subnav-inner">
				<ul class="a-admin-action-controls">
					<li><a href="<?php echo url_for('@a_event_admin'); ?>" class="a-btn big alt"><?php echo __('View All Events', array(), 'apostrophe-blog') ?></a></li>
	         <?php include_partial('list_actions', array('helper' => $helper)) ?>
				</ul>
			  <?php include_partial('aEventAdmin/form_bar') ?>				
				<div class="a-admin-title-sentence">
					<h3 class="new-item"><?php echo __('You are creating a new event.', array(), 'apostrophe_blog') ?></h3>
					<h3 class="draft-item"><?php echo __('You are working on a draft event.', array(), 'apostrophe_blog') ?></h3>					
					<h3 class="published-item"><?php echo __('You are editing a published event.', array(), 'apostrophe_blog') ?></h3>
					<span class="flash-message"> <?php echo __('Event Saved at', array(), 'apostrophe_blog') ?></span>
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
			<input type="text" id="a_blog_item_title_interface" value="<?php echo ($a_event->title == 'untitled')? '':$a_event->title ?>" />
			<div id="a-blog-item-title-placeholder"><?php echo __('Title your post...', array(), 'apostrophe-blog') ?></div>
		  <ul class="a-controls blog-title">
		    <li><a href="#" class="a-btn a-save big"><?php echo __('Save', array(), 'apostrophe_blog') ?></a></li>
		    <li><a href="#" class="a-btn a-cancel no-label big"><?php echo __('Cancel', array(), 'apostrophe_blog') ?></a></li>
		  </ul>				
		</div>

		<div id="a-blog-item-permalink-interface">
			<h6>Permalink:</h6>
			<div class="a-blog-item-permalink-wrapper url">
        <span><?php echo aTools::urlForPage($a_event->findBestEngine()->getSlug()).'/' ?></span>
			</div>
			<div class="a-blog-item-permalink-wrapper slug">
				<input type="text" name="a_blog_item_interface" value="<?php echo $a_event->slug ?>" id="a_blog_item_permalink_interface">
			  <ul class="a-controls blog-slug">
			    <li><a href="#" class="a-btn a-save mini"><?php echo __('Save', array(), 'apostrophe_blog') ?></a></li>
			    <li><a href="#" class="a-btn a-cancel no-label mini"><?php echo __('Cancel', array(), 'apostrophe_blog') ?></a></li>
			  </ul>
			</div>
		</div>

		<div class="a-blog-item event<?php echo ($a_event->hasMedia())? ' has-media':''; ?> <?php echo $a_event->getTemplate() ?>">
  		<?php include_partial('aEvent/'.$a_event->getTemplate(), array('a_event' => $a_event, 'edit' => true)) ?>
		</div>

  </div>

  <div class="a-admin-sidebar">
    <div id='a-admin-blog-post-form'>
    <?php include_partial('form', array('a_event' => $a_event, 'form' => $form)) ?>
    </div>
  </div>

	<?php if (isset($a_event['updated_at'])): ?>
		<div id="post-last-saved" class="post-updated-at option">
			<h6>
					<b>Last Saved:</b>
					<span></span>
			</h6>		
		</div>
	<?php endif ?>

  <div class="a-admin-footer">
    <?php include_partial('form_footer', array('a_event' => $a_event, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>
  </form>
<?php //include_partial('form_actions', array('a_event' => $a_event, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
</div>