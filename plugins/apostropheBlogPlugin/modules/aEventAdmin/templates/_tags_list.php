<?php foreach($a_event->getTags() as $tag): ?>
<?php if(isset($i)) echo $i ?>
<?php echo link_to($tag, "@a_event_admin_addFilter?name=tags_list&value=$tag") ?>
<?php $i = ', ' ?>
<?php endforeach ?>
