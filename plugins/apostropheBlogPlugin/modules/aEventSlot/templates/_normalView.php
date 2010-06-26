<?php include_partial('a/simpleEditWithVariants', array('pageid' => $page->id, 'name' => $name, 'permid' => $permid, 'slot' => $slot, 'page' => $page)) ?>

<?php foreach ($aEvents as $aEvent): ?>
	<?php include_partial('aEventSingleSlot/post', array('options' => $options, 'aBlogItem' => $aEvent,)) ?>
<?php endforeach ?>
