<div class="user_info">
  <?php if($sf_user->isAuthenticated()): ?>
    <?php echo __(
        'Welcome, {full_name}!',
        array('{full_name}' => $sf_user->getGuardUser()->getUsername()),
        'timpany') ?>
    (<?php echo link_to(__('log off', null, 'timpany'), '@sf_guard_signout') ?>)
  <?php else: ?>
    <?php echo __('You are guest.') ?>
    (<?php echo link_to(__('log in', null, 'timpany'), '@sf_guard_signin') ?>)
  <?php endif; ?>
</div>
