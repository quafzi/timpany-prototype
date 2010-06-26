<?php if ($sf_params->get('module') != 'aEventAdmin'): ?>
<h3 class="a-blog-item-title">
  <?php echo link_to($a_event->getTitle(), 'a_event_post', $a_event) ?>
</h3>
<?php include_partial('aEvent/meta', array('aEvent' => $a_event)) ?>

<?php endif ?>

<?php a_area('blog-body', array(
  'edit' => $edit, 'toolbar' => 'basic', 'slug' => $a_event->Page->slug,
  'allowed_types' => array('aRichText', 'aSlideshow', 'aVideo', 'aPDF'),
  'type_options' => array(
    'aRichText' => array('tool' => 'Main'),   
    'aSlideshow' => array("width" => 580, "flexHeight" => true, 'resizeType' => 's', 'constraints' => array('minimum-width' => 580)),
		'aVideo' => array('width' => 580, 'flexHeight' => true, 'resizeType' => 's'), 
		'aPDF' => array('width' => 580, 'flexHeight' => true, 'resizeType' => 's'),				
))) ?>

<?php include_partial('aEvent/addThis', array('aEvent' => $a_event)) ?>