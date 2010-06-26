<ul class="a-event-date-range block">
	<?php if ($a_event->getStartDate() != $a_event->getEndDate()): ?>
		<li class="start_date">
			<span>Start</span>
			<?php echo false !== strtotime($a_event->getStartDate()) ? format_date($a_event->getStartDate(), "f") : '&nbsp;' ?>
		</li>
		<li class="end_date">
			<span>End</span>
			<?php echo false !== strtotime($a_event->getEndDate()) ? format_date($a_event->getEndDate(), "f") : '&nbsp;' ?>
		</li>
	<?php else: ?>
		<li class="start_date">
			<?php echo false !== strtotime($a_event->getStartDate()) ? format_date($a_event->getStartDate(), "f") : '&nbsp;' ?>
		</li>			
	<?php endif ?>
</ul>