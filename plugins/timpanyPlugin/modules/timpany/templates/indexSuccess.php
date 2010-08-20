<?php if(isset($category)): ?>
    <h2><?php echo $category->getName() ?></h2>
<?php endif; ?>

<?php // echo format_number_choice('[0] Es stehen keine Produkte zur Auswahl. |[1] Zur Zeit ist nur ein Produkt verfügbar. |(1,Inf] Es stehen {number} Produkte zur Auswahl.', array('{number}' => count($products)), count($products)) ?>

<ul class="product_list">
    <?php foreach ($products as $key=>$product): ?>
        <?php $detail_link = '@timpany_product?category=xxx&product=' . $product->getSlug() ?>
    	<li class="product_box<?php if(0==($key+1)%3): ?> last-of-line<?php endif; ?>" onclick="location.href='<?php echo url_for($detail_link) ?>'">
    		<div class="product-name">
    		  <?php echo link_to($product->getName(), $detail_link) ?>
            </div>
            <div class="product-image">
              <?php echo image_tag('../timpanyPlugin/images/missing_image.png', array('alt_and_title' => 'no image available', 'width' => '100')) ?>
            </div>
    		<div class="product-price">
              <dl>
                <dt><?php echo __('net price', null, 'timpany') ?></dt>
                <dd><?php echo format_currency(round($product->getNetPrice(), 2), 'EUR') ?></dd>
              </dl>
              <dl>
                <dt><?php echo __('gross price', null, 'timpany') ?></dt>
                <dd>
                  <?php echo format_currency(round($product->getGrossPrice(0), 2), 'EUR') ?>
                  <div class="vat-notice">
                    (<?php echo __('incl. {tax_percent}% VAT', array('{tax_percent}' => $product->getTaxPercent(0)), 'timpany') ?>)
                  </div>
                </dd>
              </dl>
            </div>
            <div class="cart-link">
              <?php echo link_to(__('add to cart', null, 'timpany'), '@timpany_cart_add?product='.$product->getSlug()) ?>
            </div>
    	</li>
    <?php endforeach ?>
</ul>
<br clear="all" />
