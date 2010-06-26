<?php foreach($a_blog_post->getTags() as $tag): ?>
<?php if(isset($i)) echo $i ?>
<?php echo link_to($tag, "@a_blog_admin_addFilter?name=tags_list&value=$tag") ?>
<?php $i = ', ' ?>
<?php endforeach ?>
