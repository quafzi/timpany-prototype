<?php use_helper('I18N') ?>
<?php $links = array() ?>
<?php foreach ($tags as $tag): ?>
  <?php $links[] = link_to($tag, "aMedia/index?" . http_build_query(array("tag" => $tag))) ?>
<?php endforeach ?>
<?php // Hopefully there are reasonable I18N equivalents everywhere? ?>
<?php echo implode(__(", ", null, 'apostrophe'), $links) ?>
