<h3 class="a-blog-item-title"><?php echo link_to($aBlogPost['title'], 'a_blog_post', $aBlogPost) ?></h3>

<ul class="a-blog-item-meta">
	<li class="date"><?php echo aDate::long($aBlogPost['published_at']) ?></li>
	<li class="author"><?php echo __('Posted By:', array(), 'apostrophe_blog') ?> <?php echo $aBlogPost->getAuthor() ?></li>   			
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
