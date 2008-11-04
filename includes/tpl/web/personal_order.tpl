{*
 * %%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 * Copyright (C) 2007-2008 Christopher Jenkins. All rights reserved.
 *
 * Original Design:
 *	phpMyTicket - ticket reservation system
 * 	Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of fusionTicket.
 *
 * This file may be distributed and/or modified under the terms of the
 * "GNU General Public License" version 3 as published by the Free
 * Software Foundation and appearing in the file LICENSE included in
 * the packaging of this file.
 *
 * This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
 * THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE.
 *
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@noctem.co.uk if any conditions of this licencing isn't
 * clear to you.
 *}

<table width="100%" cellpadding="3" class="main">
	<tr>
    <td colspan="5" class="title"><h3>{!orders!}</h3></td>
  </tr>
    {* if $user->logged}
    {order->vieworder user_id=$user->user_id }
    {/if *}
    {order->order_list user_id=$user->user_id order_id=$smarty.get.id limit='1'}
      <tr>
      	<td>
	      <table  cellspacing='1' cellpadding='4' border='0'>
	      	<tr>
			  <td class='title'>{!order_id!} {$shop_order.order_id}</td>
			</tr>
			<tr>
			  <td class='user_info'>
	    		{!number_tickets!}
	  		  </td>
			  <td class='subtitle'>{$shop_order.order_tickets_nr}</td>
			</tr>
	  		<tr>
			  <td class='user_info'>{!userid!}</td>
			  <td class='subtitle'>{$shop_order.order_user_id}</td>
			</tr>
			<tr>
			  <td class='user_info'>{!total_price!}</td>
			  <td class='subtitle'>{$shop_order.order_total_price|string_format:"%1.2f"} {$organizer_currency}</td>
			</tr>
			<tr>
			  <td class='user_info'>{!order_date!}</td>
			  <td class='subtitle'>{$shop_order.order_date}</td>
			</tr>
			<tr>
			  <td class='user_info'>{!status!}</td>
			  <td class='subtitle'>
			  {if $shop_order.order_status eq "res"}
			    <font color='orange'>{!reserved!}</font><br>
			  {elseif $shop_order.order_status eq "ord"}
			    <font color="blue">{!ordered!}</font>
			  {elseif $shop_order.order_status eq "cancel"}
			    <font color="#cccccc">{!cancelled!}</font>
			  {elseif $shop_order.order_status eq "reemit"}
			    <font color="#ffffcc">{!reemitted!}</font>
			    <a href='shop.php?action=view_order&order_id={$shop_order.order_reemited_id}'>
			    {$shop_order.order_reemited_id}</a>
			  {/if}
			  </td>
			</tr>
			{if $shop_order.order_status eq "res"}
			
			<tr>
			  <td colspan="2">
			  {update->countdown order_id=$shop_order.order_id reserved=true}
          {!buytimeleft!|replace:'~DAYS~':$order_remain.days|replace:'~HOURS~':$order_remain.hours|replace:'~MINS~':$order_remain.mins|replace:'~SECS~':$order_remain.seconds}<br>
				  <br>
				  {!autocancel!}
			  {/update->countdown}
			  </td>
			</tr>
			<form name='f' action='shop.php?personal_page=orders' method='post'>
			{order->tickets order_id=$shop_order.order_id min_date='on' }
			<input type='hidden' name='min_date' value='{$shop_ticket_min_date}'>
			{/order->tickets}
			<input type='hidden' name='action' value='reorder'>
			<input type="hidden" name="user_id" value="{$shop_order.order_user_id}" >
			<input type="hidden" name="order_id" value="{$shop_order.order_id}" >
			<tr>
			  <td colspan="2" align="left">
			  {!ordertickits!}<br>
			  <font color="red">{!reserv_cancel!}</font><br>
			  	<center>
				  <input type='submit' name='submit' value='Order'>
				</center>
			  </td>
			</tr>
			</form>
			{/if}
			<tr>
			  <td class="user_info">{!Payment!} {!status!}</td>
			  <td class="subtitle">
			  {if $shop_order.order_payment_status eq "none"}
			    <font color="#FF0000">{!notpaid!}</font>
			  {elseif $shop_order.order_payment_status eq "payed"}
			  	<font color="green">{!paid!}</font>
			  {/if}
			  </td>
			</tr>
			{if ($shop_order.order_status neq "res" and $shop_order.order_status neq "cancel") and $shop_order.order_payment_status eq "none" }
			<tr>
			  <td colspan="2">
			  <font color="Black" size="12px"><b>
			  {update->countdown order_id=$shop_order.order_id}
          {!paytimeleft!|replace:'~DAYS~':$order_remain.days|replace:'~HOURS~':$order_remain.hours|replace:'~MINS~':$order_remain.mins|replace:'~SECS~':$order_remain.seconds}<br>
				{/update->countdown}
				{!autocancel!}{!payhere!}</b></font>
			  	<br>
			  	{order->tickets order_id=$shop_order.order_id min_date='on' }
					<input type='hidden' name='min_date' value='{$shop_ticket_min_date}'>
				{/order->tickets}
				{handling handling_id=$shop_order.order_handling_id}
				  {if $shop_order.order_payment_status eq 'none'}
				  	{if $shop_handling.handling_html_template}
				  		{eval var=$shop_handling.handling_html_template}
				  	{/if}
				  {/if}
				{/handling}

			  </td>
			</tr>
			{/if}
			<tr>
			  <td class="user_info">{!shipment!} {!status!}</td>
			  <td class="subtitle">
			  {if $shop_order.order_shipment_status eq "none"}
			  	<font color="#FF0000">{!notsent!}</font>
			  {elseif $shop_order.order_shipment_status eq "send"}
			  	<font color='green'>{!sent!}</font>
			  {/if}
	  	  	  </td>
			</tr>
		  </table>
	  	</td>
	  </tr>
  	{/order->order_list}
  	  <tr>
  	  </tr>
    	<td colspan="=5">
      	  <a href="?personal_page=orders">{!go_back!}</a>
		</td>
  	  </tr>
  	  <tr>
  	  	<td>
  	  	  <table width='100%' cellspacing='1' cellpadding='4'>
			<tr>
		  	  <td class='title' colspan='8'>{!tickets!}<br></td>
			</tr>   
			<tr>
			  <td class='subtitle'>{!id!}</td>
			  <td class='subtitle'>{!event!}</td>
			  <td class='subtitle'>{!category!}</td>
			  <td class='subtitle'>{!zone!}</td>
			  <td class='subtitle'>{!seat!}</td>
			  <td class='subtitle'>{!discount!}</td>
			  <td class='subtitle'>{!price!}</td>
			</tr>
			{order->tickets order_id=$shop_order.order_id}
			{counter assign='row' print=false}
			<tr class='user_list_row_{$row%2}'>
			  <td class='admin_info'>{$shop_ticket.seat_id}</td>
			  <td class='admin_info'>{$shop_ticket.event_name}</td>
			  <td class='admin_info'>{$shop_ticket.category_name}</td>
			  <td class='admin_info'>{$shop_ticket.pmz_name}</td>
			  <td class='admin_info'>
			  {if not $shop_ticket.category_numbering or $shop_ticket.category_numbering eq "both"}
			  	{$shop_ticket.seat_row_nr}  -  {$shop_ticket.seat_nr}
			  {elseif $shop_ticket.category_numbering eq "rows"}
			  	{!row!}{$shop_ticket.seat_row_nr}
			  {else}
			  	---
			  {/if}</td>
			  <td class='admin_info'>
			  {if $shop_ticket.discount_name}
			  {$shop_ticket.discount_name}
			  {else}
			  None
			  {/if}
			  </td>
			  <td class='admin_info' align='right'>{$shop_ticket.seat_price}</td>
			  <td class='admin_info' align='center'></td>
			</tr>
			{/order->tickets}
		  </table>
		</td>
	  </tr>
    	<td colspan="=5">
      	  <a href="?personal_page=orders">{!go_back!}</a>
		</td>
  	  </tr>
</table>