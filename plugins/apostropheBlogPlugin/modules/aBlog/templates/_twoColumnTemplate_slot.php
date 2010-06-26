<div class="a-blog-item post<?php echo ($aBlogPost->hasMedia())? ' has-media':''; ?>">

	<?php if (0): ?>
	<?php if($aBlogPost->userHasPrivilege('edit')): ?>
  <ul class="a-controls a-blog-post-controls">
		<li><?php echo link_to('Edit', 'a_blog_admin_edit', $aBlogPost, array('class' => 'a-btn icon a-edit flag no-label', )) ?></li>

	 	<?php if($aBlogPost->userHasPrivilege('delete')): ?>
		<li><?php echo link_to('Delete', 'a_blog_admin_delete', $aBlogPost, array('class' => 'a-btn icon a-delete no-label', 'method' => 'delete', 'confirm' => __('Are you sure you want to delete this post?', array(), 'apostrophe_blog'), )) ?></li>
		<?php endif ?>
	</ul>
	<?php endif ?>
	<?php endif ?>


  <h3 class="a-blog-item-title"><?php echo link_to($aBlogPost['title'], 'a_blog_post', $aBlogPost) ?></h3>
  <ul class="a-blog-item-meta">
		<li class="date"><?php echo aDate::pretty($aBlogPost['published_at']) ?></li>
  </ul>

	<?php if($options['maxImages'] && $aBlogPost->hasMedia()): ?>
		<div class="a-blog-item-media">
		<?php include_component('aSlideshowSlot', 'slideshow', array(
		  'items' => $aBlogPost->getMediaForArea('blog-body', 'image', $options['maxImages']),
		  'id' => 'a-slideshow-blogitem-'.$aBlogPost['id'],
		  'options' => $options['slideshowOptions']
		  )) ?>
		</div>
	<?php endif ?>

  <div class="a-blog-item-excerpt-container">
		<div class="a-blog-item-excerpt">
			<?php echo $aBlogPost->getTextForArea('blog-body', $options['excerptLength']) ?>
		</div>
    <div class="a-blog-read-more">
      <?php echo link_to('Read More', 'a_blog_post', $aBlogPost, array('class' => 'a-blog-more')) ?>
    </div>
  </div>
</div>