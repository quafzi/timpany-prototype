<h2><?php echo __('checkout', null, 'timpanyCheckout') ?></h2>
<ol id="checkout">
  <li id="billing_address" class="active">
    <?php echo __('billing address', null, 'timpanyCheckout') ?>
    <div>
        Here will appear the billing address form.
    </div>
  </li>
  <li id="shipping_address" class="active">
    <?php echo __('shipping address', null, 'timpanyCheckout') ?>
    <div>
        Here will appear the shipping address form.
    </div>
  </li>
  <li id="shipping_method" class="done">
    <?php echo __('shipping method', null, 'timpanyCheckout') ?>
    <div>
        Here will appear the shipping method form.
    </div>
  </li>
  <li id="payment_method">
    <?php echo __('payment method', null, 'timpanyCheckout') ?>
    <div>
        Here will appear the payment method form.
    </div>
  </li>
  <li id="review">
    <?php echo __('confirm your order', null, 'timpanyCheckout') ?>
    <div>
        Here will appear the review form. <?php echo link_to('Finish!', '@timpany_checkout_finish') ?>
    </div>
  </li>
</ol>
