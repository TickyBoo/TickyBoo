{discount category_id=$smarty.request.category_id}
{if $shop_discount}
	<option id="{$shop_discount.discount_id}">
		{$shop_discount.discount_name} - {$organizer_currency}{$shop_discount.discount_price}
	</option>
{/if}
{/discount}
{if $shop_discount}
	<option value="0" selected="selected">{!none!}</option>
{/if}