<?php foreach($a_event->Editors as $editor): ?>
<?php echo link_to($editor->username, '@a_event_admin_addFilter?name=editors_list&value='.$editor->id, 'post=true') ?> 
<?php endforeach ?>