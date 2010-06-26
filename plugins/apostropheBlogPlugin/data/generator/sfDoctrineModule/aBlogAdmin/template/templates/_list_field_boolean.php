[?php if ($value): ?]
  [?php echo image_tag(((sfConfig::get('app_aAdmin_web_dir'))?sfConfig::get('app_aAdmin_web_dir'):'/apostrophePlugin').'/images/tick.png', array('alt' => __('Checked', array(), 'a-admin'), 'title' => __('Checked', array(), 'a-admin'))) ?]
[?php else: ?]
  &nbsp;
[?php endif; ?]
