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
        <?php foreach ($cart->getItems() as $key=>$item): ?>
          <tr>
          	<td class="product-count"><?php echo $item->getCount() ?></td>
          	<td class="product-name"><?php echo $item['product_data']['name']  //link_to($item['product_data']['name'], '@timpany_product?category=xxx&product=' . $item['product']->getSlug()) ?></td>
          	<td class="product-price"><?php echo format_currency(round($item['product_data']['net_price'], 2), 'EUR') ?></td>
          	<td class="product-price"><?php echo format_currency(round($item->getGrossPrice('de'), 2), 'EUR') ?></td>
          	<td class="vat-notice">(inkl. <?php echo $item->getTaxPercent('de') ?>% MwSt.)</td>
          	<td class="item-netsum"><?php echo format_currency(round($item->getNetSum(), 2), 'EUR') ?></td>
          	<td class="item-grosssum"><?php echo format_currency(round($item->getGrossSum('de'), 2), 'EUR') ?></td>
          	<td class="link"><?php echo link_to(__('remove', null, 'timpanyCart'), '@timpany_cart_remove?product='.$key) ?></td>
          </tr>
        <?php endforeach ?>
        <tr class="cart-totals first">
          <th colspan="6">
            Nettosumme:
          </th>
          <td>
            <?php echo format_currency(round($cart->getNetSum(), 2), 'EUR') ?>
          </td>
          <td />
        </tr>
        <tr class="cart-totals">
          <th colspan="6">
            Bruttosumme:
          </th>
          <td>
            <?php echo format_currency(round($cart->getGrossSum(), 2), 'EUR') ?>
          </td>
          <td />
        </tr>
      </tbody>
    </table>
    <div class="link-to-checkout">
      <?php echo button_to('&crarr;', '@timpany_checkout', array('title' => __('buy now', null, 'timpanyCart'), 'class' => 'button')) ?>
    </div>
  <?php endif; ?>
  
  <div><?php echo link_to(__('find more', null, 'timpanyCart'), '@timpany_index') ?></div>
</div>