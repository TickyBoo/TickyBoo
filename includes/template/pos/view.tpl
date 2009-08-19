{*
%%copyright%% 
*}


{if $smarty.request.ajax neq 'yes'}
	{include file="header.tpl"}
{/if}

{if $smarty.get.action eq 'save_prefs'}
   {pos->set_prefs prefs=$smarty.get.user_prefs}
   {include file='view_options.tpl'}

{elseif $smarty.get.action eq 'view_options'}
   {include file='view_options.tpl'}

{elseif $smarty.get.action eq 'view_order'}
   {include file='view_order.tpl'}

{elseif $smarty.get.action eq 'view_orders'}
   {include file='view_orders.tpl'}

{elseif $smarty.get.action eq 'cancel_order'}
  {$order->cancel_f($smarty.get.order_id,$smarty.get.place) }
  {include file='view_orders.tpl'}

{elseif $smarty.get.action eq 'cancel_ticket'}
  {$order->delete_ticket_f($smarty.get.order_id,$smarty.get.ticket_id) }
  {include file='view_order.tpl'}

{elseif $smarty.post.action eq 'print_tickets'}
  {order->set_payed order_id=$smarty.post.order_id}
  {include file='print_order.tpl'}
  
{elseif $smarty.post.action eq 'confirm'}
  {include file='view_orders.tpl'}

{elseif $smarty.post.action eq 'reorder'}
  {include file="view_reorder.tpl"}
  
{elseif $smarty.post.action eq 'order_res'}
  {order->res_to_order order_id=$smarty.post.order_id handling_id=$smarty.post.handling place='pos'}
  {if $order_success}
  	{include file='order_confirm.tpl'}
  {else} 
	<div class='error'>Error</div>
  {/if}

{elseif $smarty.request.process}
	{if $smarty.request.process eq "paid"}
		{if $smarty.request.order_id}		  
  		  	{if $smarty.post.action eq "update_note"}
				{order->save_order_note order_id=$smarty.post.order_id note=$smarty.post.note}
				<div class='success'>
    				{$order_note}
    			</div>
    			{include file="process_view_paid.tpl"}	
		  	{elseif $smarty.get.action eq 'change_status'}
	  			{if $order->set_status_f($smarty.get.order_id,'pros') }
	    			<div class='success'>
	    				{!order_status_changed!}
   					</div>
					{include file="process_list.tpl"}
				{else}
  					{include file="process_view_paid.tpl"}
  				{/if}
			{else}
				{include file="process_view_paid.tpl"}		
		  	{/if}
		{else}
		  {include file="process_list.tpl"}
		{/if}
	{elseif $smarty.request.process eq "processed"}
		{if $smarty.request.order_id}
			{if $smarty.get.action eq 'send'}
				{$order->set_status_f($smarty.get.order_id,'ord')}
  				{$order->set_send_f($smarty.get.order_id) }
   				<div class='success'>
	    			{!order_status_changed!}
	    		</div>
	    		{include file="process_listpros.tpl"}
  		  	{elseif $smarty.post.action eq "update_note"}
				{order->save_order_note order_id=$smarty.post.order_id note=$smarty.post.note}
				<div class='success'>
    				{$order_note}
    			</div>
    			{include file="process_view_pros.tpl"}
			{else}
				{include file="process_view_pros.tpl"}			
			{/if}
  		{else}
		  {include file="process_listpros.tpl"}
		{/if}
	{elseif $smarty.request.process eq "sent"}
		{if $smarty.request.order_id}
			{if $smarty.post.action eq "update_note"}
				{order->save_order_note order_id=$smarty.post.order_id note=$smarty.post.note}
				<div class='success'>
    				{$order_note}
    			</div>
 			{/if}
			{include file="process_view_sent.tpl"}
		{else}
			{include file="process_listsent.tpl"}
		{/if}
	{elseif $smarty.get.process eq "reserved"}
		{include file="process_listres.tpl"}
	{else}
		{include file="view_select.tpl"}
	{/if}
{else}
  {include file="view_select.tpl"}
{/if}

{if $smarty.get.ajax neq 'yes' and $smarty.post.ajax neq 'yes'}
	{include file="footer.tpl"}
{/if}