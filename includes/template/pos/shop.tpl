{*
%%copyright%% 
*}
{if $smarty.get.event_group_id}
  {include file="event_group.tpl"}   

{elseif $smarty.get.action=='show_evgroup'}
  {include file="event_groups.tpl"}

{* New Order Ajax Calls *}

{elseif $smarty.request.ajax eq 'yes'}
	{if $smarty.request.action eq 'addtocart'}
		{assign var='result' value=$cart->add_item_f($smarty.request.event_id, $smarty.request.category_id, $smarty.request.place, 'mode_pos', false, $smarty.request.discount_id)}
		{print_r var=$result}
 	{elseif $smarty.request.action eq "remove"}
		{$cart->remove_item_f($smarty.request.event_id,$smarty.request.category_id,$smarty.request.item)}
 	{/if}
{else}
  {include file="order.tpl"}
{/if}
