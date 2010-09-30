<h2><?php echo __('Thank you!', null, 'timpanyCheckout') ?></h2>
<div id="checkout-success">
  <?php echo __('Thank you for your order (#{order_number}). We\'ll keep you informed of the progress.', array('{order_number}' => $order->getId()), 'timpanyCheckout') ?>
</div>