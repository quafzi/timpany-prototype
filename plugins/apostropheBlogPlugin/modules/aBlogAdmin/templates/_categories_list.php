<?php foreach($a_blog_post->Categories as $category): ?>
<?php echo link_to($category->name, '@a_blog_admin_addFilter?name=categories_list&value='.$category->id, 'post=true') ?> 
<?php endforeach ?>