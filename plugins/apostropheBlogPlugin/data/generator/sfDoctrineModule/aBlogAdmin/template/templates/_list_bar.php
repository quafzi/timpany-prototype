<div id="a-admin-bar" [?php if (count($sf_user->getAttribute('<?php echo $this->getModuleName() ?>.filters', null, 'admin_module'))): ?]class="has-filters"[?php endif ?]>
	<h2 class="a-admin-title you-are-here">[?php echo <?php echo $this->getI18NString('list.title') ?> ?]</h2>
</div>

[?php foreach ($configuration->getFormFields($filters, 'filter') as  $fields): ?]
  [?php foreach ($fields as $name => $field): ?]
    [?php if (aBlogToolkit::isFilterSet($filters, $name)): ?]
    <u>[?php echo $field->getConfig('label', $name) ?]: [?php echo aBlogToolkit::getFilterFieldValue($filters, $name) ?]</u> [?php echo link_to("X", "<?php echo $this->params['route_prefix'] ?>/removeFilter?filter_field=$name", "method = POST") ?] ,
    [?php endif ?]
  [?php endforeach ?]
[?php endforeach ?]

