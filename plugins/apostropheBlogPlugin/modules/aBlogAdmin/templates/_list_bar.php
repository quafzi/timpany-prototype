<div id="a-admin-bar" <?php if (count($sf_user->getAttribute('aBlogAdmin.filters', null, 'admin_module'))): ?>class="has-filters"<?php endif ?>>
  <!-- <h2 class="a-admin-title you-are-here"><?php echo __('Blog Post Admin', array(), 'messages') ?></h2> -->
</div>

<div class="a-admin-title-sentence">

<h3> 	
	<?php $appliedFilters = $filters->getAppliedFilters(); ?>

	<?php if ($appliedFilters): ?>
		You are viewing posts 
	<?php else: ?>
		You are viewing all posts
	<?php endif ?>	

	<?php $n=1; foreach($configuration->getFormFields($filters, 'filter') as $fields): ?>
	  <?php foreach ($fields as $name => $field): ?>
	    <?php if(isset($appliedFilters[$name])): ?>
	      <?php echo $field->getConfig('label', $name) ?>
	      <?php foreach($appliedFilters[$name] as $value): ?>
	        <?php echo link_to($value, "@a_blog_admin_removeFilter?name=$name&value=$value", array('class' => 'selected')) ?><?php if ($n < count($appliedFilters)): ?>,<?php endif ?>
	      <?php $n++; endforeach ?>
	    <?php endif ?>
		<?php endforeach ?>
	<?php endforeach ?>
</h3>

</div>

<script type="text/javascript">
	$(document).ready(function() {
	  $('a.selected').prepend('<span class="close"></span>')
	});
</script>