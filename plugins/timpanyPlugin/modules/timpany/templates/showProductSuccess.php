<div id="product-show">
  <h2><?php echo $product->getName() ?></h2>
  <div id="product-images">
    <?php echo image_tag('../timpanyPlugin/images/missing_image.png', array('alt_and_title' => 'no image available'))?>
  </div>
  <ul>
  	<li>
      <div class="product-type"><?php echo $product->getClassName() ?></div>
      <div class="product-price">
        <dl>
          <dt><?php echo __('net price', null, 'timpany') ?></dt>
          <dd><?php echo format_currency($product->getNetPrice()) ?> €</dd>
        </dl>
        <dl>
          <dt><?php echo __('gross price', null, 'timpany') ?></dt>
          <dd>
            <?php echo format_currency($product->getGrossPrice(0)) ?> €
            <div class="vat-notice">
              (<?php echo __('incl. {tax_percent}% VAT', array('{tax_percent}' => $product->getTaxPercent(0)), 'timpany') ?>)
            </div>
          </dd>
        </dl>
      </div>
      <div class="product-availability"><?php echo format_number_choice(__('[0] no items left |[1,1] only one item left |(1,10] only {number} items left |(10,Inf] available', null, 'timpany'), array('{number}' => $product->getInventory()), $product->getInventory()) ?></div>
      <div class="product-name"><?php echo add_to_cart($product, __('add to cart', null, 'timpany')) ?></div>
      <div class="product-description"><?php echo $product->getDescription(0) ?></div>
  	</li>
  </ul>
</div>
