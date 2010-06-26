<?php echo link_to($aEvent['title'], 'a_event_post', $aEvent) ?> by <?php echo $aEvent->Author ?>
<br/>
<?php include_partial('aEvent/meta', array('aEvent' => $aEvent)) ?>
<br/><br/>
<?php foreach($aEvent->Page->getArea('blog-body') as $slot): ?>
<?php echo $slot->getText() ?>
<?php endforeach ?>
