<?php include_partial('a/simpleEditWithVariants', array('pageid' => $page->id, 'name' => $name, 'permid' => $permid, 'slot' => $slot, 'page' => $page)) ?>

<?php if ($aBlogItem): ?>
  <?php include_partial('aEventSingleSlot/post', array('aBlogItem' => $aBlogItem, 'options' => $options)) ?>
<?php endif ?>
