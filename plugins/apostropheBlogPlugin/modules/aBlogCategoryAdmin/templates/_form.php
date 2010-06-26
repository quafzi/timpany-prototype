<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<div class="a-admin-form-container">
  <?php echo form_tag_for($form, '@a_blog_category_admin', array('id'=>'a-admin-form')) ?>
    <?php echo $form->renderHiddenFields() ?>

    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>

    <?php foreach ($configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') as $fieldset => $fields): ?>
      <?php include_partial('aBlogCategoryAdmin/form_fieldset', array('a_blog_category' => $a_blog_category, 'form' => $form, 'fields' => $fields, 'fieldset' => $fieldset)) ?>
    <?php endforeach; ?>

    <?php include_partial('aBlogCategoryAdmin/form_actions', array('a_blog_category' => $a_blog_category, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </form>
</div>

<script type="text/javascript">
  aMultipleSelect('.a-admin-form-field-users_list', { 'choose-one': '<?php echo __('Choose Users', array(), 'apostrophe_blog') ?>' });
</script>