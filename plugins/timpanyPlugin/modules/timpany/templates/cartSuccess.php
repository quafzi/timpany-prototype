<h2><?php echo __('cart', null, 'timpany') ?></h2>
<div id="cart">
  <?php if(isset($product)): ?>
    Sie haben <span class="product-name"><?php echo $product->getName() ?></span> in Ihren Warenkorb gelegt.
  <?php endif; ?>
  
  <dd><?php echo format_number_choice(__('[0] Your cart is empty. |[1] Your cart contains one item: |(1,Inf] Your cart contains {number} items:', null, 'timpanyCart'), array('{number}' => $cart->getItemCount()), $cart->getItemCount()) ?></dd>
  <?php if(0 < $cart->getItemCount()): ?>
    <table class="cart-items">
      <thead>
      	<tr>
          <th><?php echo __('count', null, 'timpanyCart') ?></th>
          <th><?php echo __('article', null, 'timpanyCart') ?></th>
          <th><?php echo __('net price', null, 'timpanyCart') ?></th>
          <th><?php echo __('gross price', null, 'timpanyCart') ?></th>
          <th><?php echo __('tax', null, 'timpanyCart') ?></th>
          <th><?php echo __('net sum', null, 'timpanyCart') ?></th>
          <th><?php echo __('gross sum', null, 'timpanyCart') ?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cart->getItems() as $item): ?>
          <tr>
          	<td class="product-count"><?php echo $item['count'] ?></td>
          	<td class="product-name"><?php echo link_to($item['product']->getName(), '@timpany_product?category=xxx&product=' . $item['product']->getSlug()) ?></td>
          	<td class="product-price"><?php echo format_currency($item['product']->getNetPrice()) ?> €</td>
          	<td class="product-price"><?php echo format_currency($item['product']->getGrossPrice(0)) ?> €</td>
          	<td class="vat-notice">(inkl. <?php echo $item['product']->getTaxPercent(0) ?>% MwSt.)</td>
          	<td class="item-netsum"><?php echo format_currency($item['net_sum']) ?> €</td>
          	<td class="item-grosssum"><?php echo format_currency($item['gross_sum']) ?> €</td>
          	<td class="link"><?php echo link_to(__('remove', null, 'timpanyCart'), '@timpany_cart_remove?product='.$item['product']->getSlug()) ?></td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    Nettosumme: <?php echo format_currency($cart->getNetSum()) ?> €<br />
    Bruttosumme: <?php echo format_currency($cart->getGrossSum()) ?> €
    <?php echo button_to('&crarr;', '@timpany_checkout', array('title' => __('buy now', null, 'timpanyCart')))?>
  <?php endif; ?>
  
  <div><?php echo link_to(__('find more', null, 'timpanyCart'), '@timpany_index') ?></div>
</div>