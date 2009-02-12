{*
%%copyright%% 
*}
{*if $user_auth->user_id neq 47}
{discount all='on' event_id=$event_id discount_name="Rab2"}{/discount}
{else*}
{discount all='on' event_id=$event_id}{/discount}
{*/if*}
{if $shop_discounts}
{category event='on' category_id=$category_id}
   <form action='shop.php' method='post'>
   <table  cellpadding='5' width='100%' border='0'>
   <tr><td class='title' valign='top'>    
    {#discounts#}
</td><td class='title' colspan='{$shop_discounts_count+1}' valign='top'> {$shop_category.event_name} - {$shop_category.category_name}</td></tr>
   <tr><td class='discount_item' valign='top'>
    {#place_nr#}

   </td><td colspan='{$shop_discounts_count+1}'></td></tr>

  {if $last_item->load_info()}{/if}
  {assign var='places_id' value=$last_item->places_id}
  {assign var='places_nr' value=$last_item->places_nr}
  
  {section name='i' loop=$places_id}
    <tr><td class='discount_value'>
    {if $shop_category.category_numbering eq 'both'}
      {$places_nr[i].0} - {$places_nr[i].1}
    {elseif $shop_category.category_numbering eq 'rows'}
    {#row#}
{$places_nr[i].0}
    {elseif $shop_category.category_numbering eq 'none'}
      {$index}
    {/if}  
    </td>
    <td class='discount_value'><label><input style='border:0px;' type='radio' name='discount[{$places_id[i]}]' value='0' checked>{#normal#}</label></td>
    
  {section name='d' loop=$shop_discounts}
      <td class='discount_value'><label><input style='border:0px;' type='radio' name='discount[{$places_id[i]}]' value='{$shop_discounts[d].discount_id}'>{$shop_discounts[d].discount_name}</label></td>
  {/section}
</tr>
  {/section}
<tr><td colspan='{$shop_discounts_count+2}' align='center'>
  <input type='hidden' name='event_id' value='{$shop_category.event_id}'>
  <input type='hidden' name='category_id' value='{$shop_category.category_id}'>
  <input type='hidden' name='item_id' value='{$last_item->id}'>
  <input type='hidden' name='action' value='adddiscount'>
  <input type='submit' name='submit' value='{#continue#}'></tr>
</table></form>

  
{/category}
{else}
  {include file='cart_view.tpl'}
{/if}

