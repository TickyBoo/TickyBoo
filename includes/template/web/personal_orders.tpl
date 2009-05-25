<table width="100%" cellpadding="3" class="main">
	<tr>
    <td colspan="7" class="title"><h3>Current / Previous Orders</h3></td>
  </tr>
  <tr>
    <td><p><strong>{!event!}</strong></p></td>
	  <td><p><strong>{!ordernumber!}</strong></p></td>
	  <td><p><strong>{!orderdate!}</strong></p></td>
	  <td><p><strong>{!tickets!}</strong></p></td>
	  <td><p><strong>{!total_price!}</strong></p></td>
	  <td><p><b>{!status!}</b></p></td>
	  <td><p><b>{!options!}</b></p></td>
  </tr>
  {if $user->logged}
    {order->order_list user_id=$user->user_id order_by_date="DESC"}
	    {if $shop_order.order_status eq "cancel"}
  			<tr class='user_order_{$shop_order.order_status}'>
  		{elseif $shop_order.order_status eq "reemit"}
  			<tr class='user_order_{$shop_order.order_status}'>
  		{elseif $shop_order.order_status eq "res"}
  			<tr class='user_order_{$shop_order.order_status}'>
  		{elseif $shop_order.order_shipment_status eq "send"}
  			<tr class='user_order_{$shop_order.order_shipment_status}'>
  		{elseif $shop_order.order_payment_status eq "payed"}
  			<tr class='user_order_{$shop_order.order_payment_status}'>
  		{elseif $shop_order.order_status eq "ord"}
  			<tr class='user_order_{$shop_order.order_status}'>
  		{else}
  			<tr class='user_order_cancel'>
  		{/if}
    		<td class='admin_info'>
      		{order->tickets order_id=$shop_order.order_id limit=1}
      		{$shop_ticket.event_name}
      		{/order->tickets}
    		</td>
        <td class='admin_info'>{$shop_order.order_id}</td>
    		<td class='admin_info'>{$shop_order.order_date}</td>
    		<td class='admin_info'>{$shop_order.order_tickets_nr}</td>
    		<td class='admin_info'>{$shop_order.order_total_price}</td>
    		<td class='admin_info'>
    		{if $shop_order.order_status eq "cancel"}{!pers_cancel!}
    		{elseif $shop_order.order_status eq "reemit"}{!pers_reemit!}
    		{elseif $shop_order.order_status eq "res"}{!pers_res!}
    		{elseif $shop_order.order_shipment_status eq "send"}{!pers_sent!}
    		{elseif $shop_order.order_payment_status eq "payed"}{!pers_payed!}
    		{elseif $shop_order.order_status eq "ord"}{!pers_ord!}
    		{else}{!pers_unknown!}
    		{/if}</td>
    		<td class='admin_info'>
          <a href='?personal_page=orders&id={$shop_order.order_id}'>
            <img border='0' src='images/view.png'> {!view_order!}
          </a>
    		</td>
      </tr>
    {/order->order_list}
  {/if}
</table>