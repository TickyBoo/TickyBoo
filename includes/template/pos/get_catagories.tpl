{if $smarty.request.category_id}
	{category event_id=$smarty.request.event_id category_id=$smarty.request.category_id stats="on"}
		{if $shop_category.cs_free > 0 }
			{if $shop_category.category_numbering eq 'none'}
				<input type='text' name='place' size='4' maxlength='2' />
			{else}
				{include file="get_seatchart.tpl"}
			{/if}
		{/if}
	{/category}
{else}
	{category event_id=$smarty.request.event_id stats="on"}
	  {counter assign="cat_num" print=false}
	  
	  {if $shop_category.cs_free>0}
	    {assign var=js_array value="$js_array unnum_cats[unnum_cats.length]='`$shop_category.category_numbering`';"}
	    {capture assign=opt}
	      <option value='{$shop_category.category_id}'>
	          {$shop_category.category_name} -  {valuta var='10.10'}{$shop_category.category_price}
	      </option>
	    {/capture}  
	    {assign var=opt_array value="$opt_array $opt"}
	  {/if}
	{/category}
	{$opt_array}
{/if}