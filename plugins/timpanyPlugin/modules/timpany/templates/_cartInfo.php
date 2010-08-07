<dl class="cart_info">
  <dt><?php echo link_to(__('cart', null, 'timpany'), '@timpany_cart')?></dt>
  <dd><?php echo format_number_choice('[0] empty |[1] one item |(1,Inf] {number} items', array('{number}' => $item_count), $item_count) ?></dd>
</dl>
