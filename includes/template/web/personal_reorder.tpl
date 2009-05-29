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
    <td colspan="5" class="title"><h3>Current / Previous Order</h3></td>
    </tr>
    {* if $user->logged}
      {order->vieworder user_id=$user->user_id }
    {/if *}
    {order->order_list user_id=$user->user_id order_id=$smarty.post.order_id length='1'}
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
			  <td class='user_info'>{!user!} {!id!}</td>
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
			    (<a href='index.php?action=view_order&order_id={$shop_order.order_reemited_id}'>
			    {$shop_order.order_reemited_id}</a>)
			  {/if}
			  </td>
			</tr>
			<tr>
			  <td colspan="2">
			  {if $shop_order.order_status eq "res"}
			  {update->countdown order_id=$shop_order.order_id reserved=true}
			  	You have {$order_remain.days} Days, {$order_remain.hours} Hours, {$order_remain.mins} Mins, {$order_remain.seconds} Seconds
				  <br>
				  Left to buy your ticket(s)!<br>
				  <br>
				  After this time they are automaticaly canceled!
			  {/update->countdown}
			  {/if}
			  </td>
			</tr>
			<tr>
			  <td class="user_info">Payment {!status!}</td>
			  <td class="subtitle">
			  {if $shop_order.order_payment_status eq "none"}
			    <font color="#FF0000">Not {!paid!}</font>
			  {elseif $shop_order.order_payment_status eq "payed"}
			  	<font color="green">{!paid!}</font>
			  {/if}
			  </td>
			</tr>
			{if ($shop_order.order_status neq "res" and $shop_order.order_status neq "cancel") or $shop_order.order_payment_status eq "payed" }
			<tr>
			  <td colspan="2">
			  <font color="Black" size="12px"><b>
			  	{update->countdown order_id=$shop_order.order_id}
			  	You Have {$order_remain.mins} Mins and {$order_remain.seconds} Seconds to pay for your order!<br><br>
				{/update->countdown}
				After this time your order is automaticaly canceled!<br><br>
			  	To Pay Now, Check Here:</b></font>
			  	<br>
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
			  <td class="user_info">Shipment {!status!}</td>
			  <td class="subtitle">
			  {if $shop_order.order_shipment_status eq "none"}
			  	<font color="#FF0000">Not {!sent!}</font>
			  {elseif $shop_order.order_shipment_status eq "send"}
			  	<font color='green'>{!sent!}</font>
			  {/if}
	  	  	  </td>
			</tr>
		  </table>
	  	</td>
	  </tr>
	    <tr>
		  	<td colspan="2">
		  	<form name='f' action='index.php' method='post'>
          <input type='hidden' name='personal_page' value='orders'>
  			  <input type="hidden" name="order_id" value='{$shop_order.order_id}'>
	  		  <input type='hidden' name='action' value='order_res'>
          {ShowFormToken name='reorder'}
			  <table width='100%' border='0' cellspacing='0' cellpadding='1'style='padding:5px; border:#45436d 1px solid;'>
			  <center>
				<tr>
				  <td rowspan='7'><img src='images/dot.gif' width='1' height='100'></td>
				  <td colspan='3' align='left'><font size='2'> <b>{!payment!}</b></font></td>
				</tr>
				{order->tickets order_id=$shop_order.order_id min_date='on' }
					{assign var="event_date" value=$shop_ticket_min_date}
				{/order->tickets}
				{handling www='on' event_date=$event_date}
				<tr>
				  <td class='payment_form'>
	<input style='border:0px;' type='radio' id='{$shop_handling.handling_id}_check' name='handling' value='{$shop_handling.handling_id}'>
				  </td>
				  <td class='payment_form'><label for='{$shop_handling.handling_id}_check'>{!payment!}
				  {if $shop_handling.handling_text_payment}{eval var=$shop_handling.handling_text_payment}{/if}
				  <br>{!shipment!}
				  {if $shop_handling.handling_text_shipment}{eval var=$shop_handling.handling_text_shipment}{/if}
				  </label>
				  </td>
				  <td class='payment_form'>
					{assign var=fee value="`$shop_handling.handling_fee_percent*$shop_order.order_total_price/100.00+$shop_handling.handling_fee_fix`"} + {$fee|string_format:"%.2f"} {$organizer_currency}
				  </td>
				</tr>
				{/handling}
			  </table>
			  <br>
			  <input type='submit' name='submit_payment' value='{!order_it!}'>
			  </center>
			  </form>
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