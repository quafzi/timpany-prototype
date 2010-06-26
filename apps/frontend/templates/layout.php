<?php // We supply a default layout with the apostrophePlugin which is great for ?>
<?php // both CMS and non-CMS pages because you can easily override every section ?>
<?php // via Symfony slots. We've put this require here to ensure that we don't ?>
<?php // accidentally let this copy get out of sync with the one in the plugin. ?>
<?php // YES, you may ABSOLUTELY replace this layout.php with your own. ?>

<?php require sfConfig::get('sf_plugins_dir') . '/apostrophePlugin/modules/a/templates/layout.php' ?>
