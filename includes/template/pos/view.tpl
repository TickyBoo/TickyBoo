{*
%%copyright%% 
*}

{if $smarty.get.action eq 'cancel_order'}
  {$order->cancel_f($smarty.get.order_id,$smarty.get.place) }
  {include file="process_select.tpl"}

{elseif $smarty.get.action eq 'cancel_ticket'}
  {$order->delete_ticket_f($smarty.get.order_id,$smarty.get.ticket_id) }
  {include file="process_select.tpl"}

{elseif $smarty.post.action eq 'print'}
  {order->set_payed order_id=$smarty.post.order_id}
  {include file='print.tpl'}
  
{elseif $smarty.post.action eq 'confirm'}
  {include file="process_select.tpl"}

{elseif $smarty.request.action eq 'reorder'}
  {include file="view_reorder.tpl"}
  
{elseif $smarty.post.action eq 'order_res'}
  {order->res_to_order order_id=$smarty.post.order_id handling_id=$smarty.post.handling place='pos'}
  {if $order_success}
    {include file='order_confirm.tpl'}
  {else} 
    <div class='error'>Error</div>
    {include file="process_select.tpl"}
  {/if}
{else}
  {include file="process_select.tpl"}
{/if}