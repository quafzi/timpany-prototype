<?php foreach($a_event->Categories as $category): ?>
<?php echo link_to($category->name, '@a_event_admin_addFilter?name=categories_list&value='.$category->id, 'post=true') ?> 
<?php endforeach ?>