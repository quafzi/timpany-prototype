<?php $filterForm = new aBlogPostFormFilter() ?>
<?php $filterFieldConfig = $this->configuration->getFormFilterFields($filterForm) ?>
<?php $filterFields = $filterForm->getFields() ?>
<?php foreach ($this->configuration->getValue('list.display') as $name => $field): ?>
	[?php slot('a-admin.current-header') ?]
	<th class="a-admin-<?php echo strtolower($field->getType()) ?> a-column-<?php echo $name ?>">
  <?php if(isset($filterFieldConfig[$name])): ?>
    <?php //This field needs dropdown filters to be applied ?>
    <ul class="a-multi-title">
      <li><a href="#" class="a-btn a-sort-label">[?php echo __('<?php echo $field->getConfig('label') ?>', array(), '<?php echo $this->getI18nCatalogue() ?>') ?]</a>
        <div class="filternav <?php echo aTools::slugify($name); ?>">
          <hr/>
    <?php if($filterFieldConfig[$name]->isComponent()): ?>
      [?php include_component('<?php echo $this->getModuleName() ?>', 'list_th_<?php echo $name ?>_dropdown', array('filters' => $filters, 'name' => '<?php echo $name ?>'  )) ?]
    <?php elseif($filterFieldConfig[$name]->isPartial()): ?>
      [?php include_partial('<?php echo $this->getModuleName() ?>/list_th_<?php echo $name ?>_dropdown', array('filters' => $filters, 'name' => '<?php echo $name ?>'  )) ?]
    <?php elseif(in_array($filterFields[$name], array('Enum', 'ForeignKey', 'ManyKey'))): ?>
      [?php include_partial('<?php echo $this->getModuleName() ?>/list_th_dropdown', array('filters' => $filters, 'name' => '<?php echo $name ?>'  )) ?]    
    <?php endif ?>
        </div>
      </li>
    </ul>
  <?php else: ?>
    <span class="a-simple-title">[?php echo __('<?php echo $field->getConfig('label') ?>', array(), '<?php echo $this->getI18nCatalogue() ?>') ?]</span>
  <?php endif; ?>

  <?php if ($field->isReal()): ?>
  
  [?php if ('<?php echo $name ?>' == $sort[0]): ?]

		[?php ($sort[1] == 'asc')? $sortLabel = __("Descending", array(), 'a-admin'): $sortLabel = __("Ascending", array(), 'a-admin'); ?]

    [?php echo link_to(
			$sortLabel,
			'<?php echo $this->getModuleName() ?>/index?sort=<?php echo $name ?>&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'), 
			array('class' => 'a-btn flag flag-right nobg icon a-sort-arrow sorting '.$sort[1], 'title' => __($sortLabel, array(), 'a-admin'))) 
		?]
		
    [?php else: ?]

    [?php echo link_to(
      __("Ascending", array(), 'a-admin'),
      '<?php echo $this->getModuleName() ?>/index?sort=<?php echo $name ?>&sort_type=asc', 
			array('class' => 'a-btn flag flag-right nobg icon a-sort-arrow asc', 'title' => __('Ascending', array(), 'a-admin'))) 
		?]
		
  [?php endif; ?]

  <?php endif ?>
	</th>
	[?php end_slot(); ?]

<?php echo $this->addCredentialCondition("[?php include_slot('a-admin.current-header') ?]", $field->getConfig()) ?>

<?php endforeach; ?>
