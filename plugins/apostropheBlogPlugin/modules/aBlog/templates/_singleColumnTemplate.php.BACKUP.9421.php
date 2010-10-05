<?php if ($sf_params->get('module') != 'aBlogAdmin'): ?>
<h3 class="a-blog-item-title">
  <?php echo link_to($a_blog_post->getTitle(), 'a_blog_post', $a_blog_post) ?>
</h3>
<ul class="a-blog-item-meta">
  <li class="date"><?php echo aDate::pretty($a_blog_post['published_at']); ?></li>
  <li class="author"><?php echo __('Posted By:', array(), 'apostrophe_blog') ?> <?php echo $a_blog_post->getAuthor() ?></li>   
</ul>
<?php endif ?>

<?php a_area('blog-body', array(
  'edit' => $edit, 'toolbar' => 'basic', 'slug' => $a_blog_post->Page->slug,
  'allowed_types' => array('aRichText', 'aSlideshow', 'aVideo', 'aPDF'),
  'type_options' => array(
    'aRichText' => array('tool' => 'Main'),   
    'aSlideshow' => array("width" => 680, "flexHeight" => true, 'resizeType' => 's', 'constraints' => array('minimum-width' => 680)),
		'aVideo' => array('width' => 680, 'flexHeight' => true, 'resizeType' => 's'), 
		'aPDF' => array('width' => 680, 'flexHeight' => true, 'resizeType' => 's'),				
))) ?>

<?php include_partial('aBlog/addThis', array('aBlogPost' => $a_blog_post)) ?>
