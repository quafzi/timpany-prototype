<h2>Produktdetailansicht</h2>

<ul>
  	<li>
  		<div class="product-name"><?php echo $product->getName() ?></div>
  		<div class="product-type"><?php echo $product->getClassName() ?></div>
  		<div class="product-price"><?php echo format_currency($product->getNetPrice()) ?> €</div>
  		<div class="product-price"><?php echo format_currency($product->getGrossPrice(0)) ?> €</div>
  		<div class="vat-notice">(inkl. <?php echo $product->getTaxPercent(0) ?>% MwSt.)</div>
        <div class="product-availability"><?php echo format_number_choice('[0] no items left |[1,10] only {number} items left |(1,Inf] available', array('{number}' => $product->getInventory()), $product->getInventory()) ?>
  		<div class="product-name"><?php echo add_to_cart($product, __('add to cart', null, 'timpany')) ?></div>
  		<div class="product-description"><?php echo $product->getDescription(0) ?></div>
  	</li>
</ul>
