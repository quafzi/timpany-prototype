<ul class="a-blog-item-meta">
  <li class="start-day"><?php echo aDate::dayAndTime($aEvent->getStartDate()) ?></li>
  <li class="start-date"><?php echo aDate::dayMonthYear($aEvent->getStartDate()) ?><?php if ($aEvent->getStartDate() != $aEvent->getEndDate()): ?> &mdash;<?php endif ?></li>
	<?php if ($aEvent->getStartDate() != $aEvent->getEndDate()): ?>
		<li class="end-day"><?php echo aDate::dayAndTime($aEvent->getEndDate()) ?></li>
	  <li class="end-date"><?php echo aDate::dayMonthYear($aEvent->getEndDate()) ?></li>
	<?php endif ?>
	<?php if (0): ?>
	<?php // Events authors are not important to end users, turned off for now ?>
  	<li class="author"><?php echo __('Posted By:', array(), 'apostrophe_blog') ?> <?php echo $aEvent->getAuthor() ?></li>   			
	<?php endif ?>
</ul>
