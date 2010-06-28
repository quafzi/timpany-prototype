<?php 
$startDate = aDate::dayMonthYear($aEvent->getStartDate());
$endDate = aDate::dayMonthYear($aEvent->getEndDate());
$startTime = aDate::time($aEvent->getStartDate());
$endTime = aDate::time($aEvent->getEndDate());
?>

<ul class="a-blog-item-meta">
  <li class="start-date"><?php echo $startDate ?></li>
	<?php if ($startDate == $endDate): ?>
		<?php if ($startTime != $endTime): ?>
	  <li class="event-time"><?php echo $startTime ?> &ndash; <?php echo $endTime ?></li>
		<?php endif ?>
	<?php else: ?>
	  <li class="end-date">&ndash; <?php echo $endDate ?></li>
	<?php endif ?>

	<?php if (0): ?>
	<?php // Events authors are not important to end users, turned off for now ?>
  	<li class="author"><?php echo __('Posted By:', array(), 'apostrophe_blog') ?> <?php echo $aEvent->getAuthor() ?></li>   			
	<?php endif ?>
</ul>
