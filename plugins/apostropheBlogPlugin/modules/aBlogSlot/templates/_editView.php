<?php // Just echo the form. You might want to render the form fields differently ?>

<?php echo $form->renderHiddenFields() ?>

<h4 class="a-slot-form-title">Blog Posts</h4>

<div class="a-form-row count">
	<?php echo $form['count']->renderLabel(__('Posts', array(), 'apostrophe_blog')) ?>
	<div class="a-form-field">
		<?php echo $form['count']->render() ?>
		<?php echo $form['count']->renderHelp() ?>
	</div>
	<div class="a-form-error"><?php echo $form['count']->renderError() ?></div>
</div>

<div class="a-form-row categories">
	<?php echo $form['categories_list']->renderLabel(__('Category', array(), 'apostrophe_blog')) ?>
	<div class="a-form-field">
		<?php echo $form['categories_list']->render() ?>
		<?php echo $form['categories_list']->renderHelp() ?>
	</div>
	<div class="a-form-error"><?php echo $form['categories_list']->renderError() ?></div>
</div>

<div class="a-form-row tags">
	<?php echo $form['tags_list']->renderLabel(__('Tags', array(), 'apostrophe_blog')) ?>
	<div class="a-form-field">
		<?php echo $form['tags_list']->render() ?>
		<?php echo $form['tags_list']->renderHelp() ?>
	</div>
	<div class="a-form-error"><?php echo $form['tags_list']->renderError() ?></div>
</div>

<script type="text/javascript" charset="utf-8" src="/sfDoctrineActAsTaggablePlugin/js/pkTagahead.js"></script>
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
    pkTagahead(<?php echo json_encode(url_for("taggableComplete/complete")) ?>);
    aMultipleSelect('#a-<?php echo $form->getName() ?>', { 'choose-one': 'Add Categories' });
		$('#a-<?php echo $form->getName() ?>').addClass('a-options dropshadow');			
  });
</script>

