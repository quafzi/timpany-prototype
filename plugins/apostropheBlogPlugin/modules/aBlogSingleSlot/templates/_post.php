<?php $full = a_get_option($options, 'full', false) ?>
<?php $template = a_get_option($options, 'template', $aBlogItem['template']) ?>
<?php $subtemplate = a_get_option($options, 'subtemplate', 'slot') ?>
<?php $templateOptionsAll = a_get_option($options, 'template_options', array()) ?>
<?php $templateOptions = a_get_option($templateOptionsAll, $template, array()) ?>
<?php $subtemplate = a_get_option($templateOptions, 'subtemplate', $subtemplate) ?>
<?php if ($full): ?>
	<?php $suffix = ''; ?>
<?php else: ?>
	<?php $suffix = '_'.$subtemplate; ?>
<?php endif ?>
<?php // Allows styling based on whether there is media in the post, ?>
<?php // the blog template, and the subtemplate ?>
<div class="a-blog-item post<?php echo ($aBlogItem->hasMedia())? ' has-media':''; ?> <?php echo $template ?> <?php echo $subtemplate ?>">
<?php // TODO: passing a variable as both underscore and intercap is silly clean this up make the partials consistent but look out for overrides ?>
<?php include_partial('aBlog/'.$template.$suffix, array('aBlogPost' => $aBlogItem, 'a_blog_post' => $aBlogItem, 'edit' => false, 'options' => $options)) ?>
</div>