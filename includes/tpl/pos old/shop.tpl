{include file="header.tpl"}

{if $smarty.get.action eq home}
	{include file='index.tpl'}

{elseif $smarty.get.action eq save_prefs}
   {user_auth->set_prefs prefs=$smarty.get.user_prefs}
   {include file='view_options.tpl'}

{elseif $smarty.get.action eq view_options}
   {include file='view_options.tpl'}

{elseif $smarty.get.action eq view_order}
   {include file='view_order.tpl'}

{elseif $smarty.get.action eq view_orders}
   {include file='view_orders.tpl'}

{elseif $smarty.post.action eq submit_info}
	{if not $smarty.post.exst_user or $smarty.post.exst_user neq 1}
  		{user->login_guest user_id=$smarty.post.exst_user}
  	{else}
		{user->guest data=$smarty.post short='true'}
  	{/if}
	{if $user->logged}
		{order->make handling=$smarty.post.handling user_id=$user->user_id place='pos' no_fee=$smarty.post.no_fee no_cost=$smarty.post.no_cost}
	    {if $order_success}
	    	{include file='order_user_confirm.tpl'}
	      	{cart->destroy}
	    {else}
	    	<div class='error'>{$order_error}</div>
	    {/if}
	    {user->logout}
	{/if}


{elseif $smarty.post.action eq submit_reserve}
  {if $smarty.post.exst_user neq '1'}
	{user->login_guest user_id=$smarty.post.exst_user}
  {else}
	{user->guest data=$smarty.post short='true'}
  {/if}
  {if $user->logged}
    {order->make handling=$smarty.post.handling user_id=$user->user_id place='pos' no_fee=$smarty.post.no_fee}
    {if $order_success}
      {include file='order_reserve_confirm.tpl'}
      {cart->destroy}
    {else}
      <div class='error'>{$order_error}</div>
    {/if}
    {user->logout}
  {/if}

{elseif $smarty.post.action eq 'cancel_order'}
  {if $order->cancel_f($smarty.post.order_id) }
    <div class='succes'>
    {#order_canceled#}
    </div>
  {/if}

{elseif $smarty.get.action eq 'cancel_order'}
  {if $order->cancel_f($smarty.get.order_id,$smarty.get.place) }
  {/if}
   {include file='view_orders.tpl'}

{elseif $smarty.get.action eq 'cancel_ticket'}
  {if $order->delete_ticket_f($smarty.get.order_id,$smarty.get.ticket_id) }
  {/if}
   {include file='view_order.tpl'}

{elseif $smarty.post.action eq 'print_tickets'}
  {order->set_payed order_id=$smarty.post.order_id}
  {include file='print_order.tpl'}

{elseif $smarty.post.action eq 'confirm'}
  {* order->set_reserved order_id=$smarty.post.order_id *}
  {include file='view_orders.tpl'}

{elseif $smarty.post.action eq 'order_tickets'}
  {if $smarty.post.handling}
    {handling handling_id=$smarty.post.handling}
      {*{if $shop_handling.handling_shipment eq 'sp'}
	  {order->make user_id=$user_auth->user_id handling=$shop_handling.handing_id place='pos'}
	  {if $order_success}
	  	{include file='order_confirm.tpl'}
		{cart->destroy}
	  {else}<div class='error'>{$order_error}</div>
	  {/if}
	{else}*}
        {include file='user_address.tpl' handling_id=$smarty.post.handling}
    {*/if*}
    {/handling}
  {else}
    {include file='cart_view.tpl'}
  {/if}

 {elseif $smarty.post.action eq 'reserve_tickets'}
  {if $smarty.post.handling}
  	{handling handling_id=$smarty.post.handling}
      {include file='user_reserve.tpl'}
    {/handling}
  {else}
    {include file='cart_view.tpl'}
  {/if}



{elseif $smarty.post.action eq 'addtocart'}
  {assign var='last_item' value=$cart->add_item_f($smarty.post.event,$smarty.post.category,$smarty.post.place,'mode_kasse')}
  {if $last_item}
    {include file="discount.tpl" event_id=$smarty.post.event category_id=$smarty.post.category}
  {else}
    {include file="category.tpl"}
   {/if}

{elseif $smarty.post.action eq 'reorder'}
  {include file="view_reorder.tpl"}

{elseif $smarty.post.action eq 'order_res'}
  {order->res_to_order order_id=$smarty.post.order_id handling_id=$smarty.post.handling place='pos'}
  {if $order_success}
  	{include file='order_confirm.tpl'}
  {else}
	<div class='error'>Error</div>
  {/if}


{elseif $smarty.post.action eq "adddiscount"}
{cart->set_discounts event_id=$smarty.post.event_id category_id=$smarty.post.category_id item_id=$smarty.post.item_id discounts=$smarty.post.discount }
  {include file="cart_view.tpl"}

{elseif $smarty.get.action eq "remove"}
  {$cart->remove_item_f($smarty.get.event_id,$smarty.get.cat_id,$smarty.get.item)}
  {include file="cart_view.tpl"}

{elseif $smarty.get.action eq "view_cart"}
  {include file="cart_view.tpl"}

{elseif $smarty.get.category_id}
  {if $smarty.get.qty}
    {assign var='last_item' value=$cart->add_item_f($smarty.get.event_id,$smarty.get.category_id,$smarty.get.qty,'mode_kasse')}
      {if $last_item}
        {include file="discount.tpl" event_id=$smarty.get.event_id category_id=$smarty.get.category_id}
      {else}
        {include file="event.tpl"}
      {/if}
    {else}
      {include file="category.tpl"}
    {/if}

{elseif $smarty.get.event_id}
  {include file="event.tpl"}


{elseif $smarty.get.event_group_id}
  {include file="event_group.tpl"}


{elseif $smarty.get.action=='show_evgroup'}
  {include file="event_groups.tpl"}


{elseif $smarty.get.group_id}
  {include file="event_group.tpl"}

{elseif $smarty.get.action eq 'calendar'}
	{include file="calendar.tpl"}

{elseif $smarty.get.process}
	{if $smarty.get.process eq "paid"}
		{if $smarty.get.order_id}
		  {if $smarty.get.action eq 'change_status'}
  			{if $order->set_status_f($smarty.get.order_id,'pros') }
    		<div class='succes'>
    			{#order_status_changed#}
    		</div>
  			{/if}
  		  {/if}
		  {include file="process_view_paid.tpl"}
		{else}
		  {include file="process_list.tpl"}
		{/if}
	{elseif $smarty.get.process eq "processed"}
		{if $smarty.get.order_id}
		  {if $smarty.get.action eq 'send'}
  			{$order->set_send_f($smarty.get.order_id,'pros') }
    		<div class='succes'>
    			{#order_status_changed#}
    		</div>
  		  {/if}
  		  {include file="process_view_pros.tpl"}
  		{else}
		  {include file="process_listpros.tpl"}
		{/if}
	{elseif $smarty.get.process eq "sent"}
		{include file="process_listsent.tpl"}
	{elseif $smarty.get.process eq "reserved"}
		{include file="process_listres.tpl"}
	{else}
		{include file="process.tpl"}
	{/if}
{else}

  {include file="index.tpl"}
{/if}

{include file="footer.tpl"}
