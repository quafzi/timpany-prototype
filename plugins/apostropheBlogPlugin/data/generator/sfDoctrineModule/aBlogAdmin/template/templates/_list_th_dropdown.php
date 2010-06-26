[?php $values = $filters[$name]->getValue() ?]
[?php $values = is_array($values)? $values : array($values) ?]
<ul>
	[?php foreach($filters[$name]->getWidget()->getChoices() as $id => $choice): ?]
	[?php if($choice != ''): ?]
  [?php $class = in_array($id, $values)? 'selected' : '' ?]
	<li class="[?php echo $class ?]">
    [?php echo link_to($choice, '<?php echo $this->getModuleName() ?>/addFilter?name='.$name.'&value='.$id, 'post=true') ?]
  </li>
	[?php endif ?]
	[?php endforeach ?]
</ul>