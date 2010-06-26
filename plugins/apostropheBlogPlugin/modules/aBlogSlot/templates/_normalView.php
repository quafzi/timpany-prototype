<?php include_partial('a/simpleEditWithVariants', array('pageid' => $page->id, 'name' => $name, 'permid' => $permid, 'slot' => $slot, 'page' => $page)) ?>

<?php foreach ($aBlogPosts as $aBlogPost): ?>
	<?php include_partial('aBlogSingleSlot/post', array('options' => $options, 'aBlogItem' => $aBlogPost)) ?>
<?php endforeach ?>
