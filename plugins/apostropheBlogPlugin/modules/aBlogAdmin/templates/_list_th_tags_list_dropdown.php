<?php //Popular tags will go here eventually ?>
<?php $letter = ''; ?>
<?php $choices = $filters['tags_list']->getWidget()->getChoices() ?>
<?php $n = 0; ?>
<?php $tagSeparator = "<i>,</i>"; ?>
<?php foreach($choices as $id => $choice): ?>
<?php   if(strtoupper($choice[0]) == $letter): ?><?php echo $tagSeparator ?><?php   else: ?>
<?php     if(strtoupper($choice[0]) != 'A'): ?></span><?php endif ?>
<?php   $letter = strtoupper($choice[0]) ?>
<span<?php echo ($n == 0)? ' class="first"':'' ?><?php echo ($n == count($choices))? ' class="last"':'' ?>>
  <b><?php echo $letter ?></b>
<?php endif ?>
<?php echo link_to($choice, 'aBlogAdmin/addFilter?name=tags_list&value='.$id, 'post=true') ?>
<?php $n++; endforeach ?>
