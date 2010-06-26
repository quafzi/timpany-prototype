<?php foreach($a_blog_post->Editors as $editor): ?>
<?php echo link_to($editor->username, '@a_blog_admin_addFilter?name=editors_list&value='.$editor->id, 'post=true') ?> 
<?php endforeach ?>