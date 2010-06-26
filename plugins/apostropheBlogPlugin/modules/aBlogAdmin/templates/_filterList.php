<?php foreach($filters->getAppliedFilters() as $name => $value): ?>
  <p><?php echo $filters[$name]->renderLabel() ?>
  <?php if(is_array($value)): ?>
    <?php foreach($value as $val): ?>
      <?php echo $val ?>
    <?php endforeach ?>
  <?php else: ?>
    <?php echo $value ?>
  <?php endif ?>
  </p>
<?php endforeach ?>

