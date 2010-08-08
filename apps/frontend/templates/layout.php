<?php use_helper('I18N', 'Timpany') ?>
<?php // It also makes a fine site-wide layout, which gives you global slots on non-page templates ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<?php include_http_metas() ?>
	<?php include_metas() ?>
	<?php include_title() ?>
	<?php // 1.3 and up don't do this automatically (no common filter) ?>
    <?php include_stylesheets() ?>
	<?php include_javascripts() ?>
	<link rel="icon" href="<?php echo $sf_request->getRelativeUrlRoot() ?>/timpanyPlugin/images/timpany.ico" type="image/x-icon" />

	<!--[if lt IE 7]>
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function() {
			aIE6(<?php echo ($sf_user->isAuthenticated())? 'true':'false' ?>, <?php echo json_encode(__('You are using IE6! That is just awful! Apostrophe does not support editing using Internet Explorer 6. Why don\'t you try upgrading? <a href="http://www.getfirefox.com">Firefox</a> <a href="http://www.google.com/chrome">Chrome</a> 	<a href="http://www.apple.com/safari/download/">Safari</a> <a href="http://www.microsoft.com/windows/internet-explorer/worldwide-sites.aspx">IE8</a>', null, 'apostrophe')) ?>);
		});		
	</script>
	<![endif]-->	

	<!--[if lte IE 7]>
		<link rel="stylesheet" type="text/css" href="/apostrophePlugin/css/a-ie.css" />	
	<![endif]-->
		
</head>

<?php // body_class allows you to set a class for the body element from a template ?>
<body class="<?php if (has_slot('body_class')): ?><?php include_slot('body_class') ?><?php endif ?><?php if (($sf_user->isAuthenticated())): ?> logged-in<?php endif ?>">

  <?php // Everyone gets this now, but internally it determines which controls you should ?>
  <?php // actually see ?>
	<div id="a-wrapper">
      <div class='header'>
        <div id='logo'><?php echo link_to('Timpany', '@timpany_index', array('title' => __('go to home page'))) ?></div>
        <div id='slogan'>webshop of the future</div>
        <?php include_component('timpany', 'cartInfo') ?>
      </div>
      <?php // Note that just about everything can be suppressed or replaced by setting a ?>
      <?php // Symfony slot. Use them - don't write zillions of layouts or do layout stuff ?>
      <?php // in the template (except by setting a slot). To suppress one of these slots ?>
      <?php // completely in one line of code, just do: slot('a-whichever', '') ?>
      <div id="a-content">
        <?php echo $sf_data->getRaw('sf_content') ?>
      </div>
      <div class='footer'>
        This is a timpany demo shop.
      </div>
	</div>
</body>
</html>
