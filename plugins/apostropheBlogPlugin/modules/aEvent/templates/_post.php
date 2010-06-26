<?php use_helper('a', 'I18N') ?>
<?php $catClass = ""; foreach ($a_event->getCategories() as $category): ?><?php $catClass .= " category-".aTools::slugify($category); ?><?php endforeach ?>

<div class="a-blog-item event <?php echo $a_event->getTemplate() ?><?php echo ($catClass != '')? $catClass:'' ?>">

  <?php if($a_event->userHasPrivilege('edit')): ?>
  <ul class="a-controls a-blog-post-controls">
		<li><?php echo link_to('Edit', 'a_event_admin_edit', $a_event, array('class' => 'a-btn icon a-edit no-label', )) ?></li>
	</ul>
	<?php endif ?>

<?php include_partial('aEvent/'.$a_event->getTemplate(), array('a_event' => $a_event, 'edit' => false)) ?>


</div>

