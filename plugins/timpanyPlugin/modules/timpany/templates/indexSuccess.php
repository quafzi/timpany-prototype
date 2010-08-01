<?php use_helper('I18N') ?>

<h2>Produktübersicht</h2>
<?php echo format_number_choice('[0] Es stehen keine Produkte zur Auswahl. |[1] Zur Zeit ist nur ein Produkt verfügbar. |(1,Inf] Es stehen {number} Produkte zur Auswahl.', array('{number}' => count($products)), count($products)) ?>

<ul>
    <?php foreach ($products as $product): ?>
    	<li>
    		<div class="product-name"><?php echo $product->getName() ?></div>
    		<div class="product-price"><?php echo $product->getNetPrice() ?> €</div>
    		<div class="product-price"><?php echo $product->getGrossPrice(0) ?> €</div>
    		<div class="vat-notice">(inkl. <?php echo 100.0*$product->getTaxRate(0) ?>% MwSt.)</div>
    	</li>
    <?php endforeach ?>
</ul>
