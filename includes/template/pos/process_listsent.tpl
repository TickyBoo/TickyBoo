{*
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 * Copyright (C) 2007-2008 Christopher Jenkins. All rights reserved.
 *
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
 */
 *}

<table border='0' width='100%' >
  <tr>
	<td>
	  <table width='100%' cellspacing='1' cellpadding='5' border=0>
		<tr>
		  <td class='title' colspan='5' align='center'>
    		{!pos_sentorders!}
		  </td>
		</tr>
    	<tr>
		  <td colspan='5' align='center'>
		  {literal}
		  <script>
		    <!--
			// Author: Matt Kruse <matt@mattkruse.com>
			// WWW: http://www.mattkruse.com/
			TabNext()
			// Function to auto-tab field
			// Arguments:
			// obj :  The input object (this)
			// event: Either 'up' or 'down' depending on the keypress event
			// len  : Max length of field - tab when input reaches this length
			// next_field: input object to get focus after this one
			
			var field_length=0;
			
			function TabNext(obj,event,len,next_field) {
			  if (event == "down") {
			    field_length=obj.value.length;
			  }
			  else if (event == "up") {
			    if (obj.value.length != field_length) {
			      field_length=obj.value.length;
			      if (field_length == len) {
			        next_field.focus();
			}}}}
			-->
		  </script>
		  {/literal}
		  <form action='shop.php' method='get'>
		  	<input type='hidden' name='action' value='view_orders' />
			<table border='0' width='100%' style='border-top:#45436d 1px solid;border-bottom:#45436d 1px solid;'>
			  <tr>
			  	<td class='admin_info'>{!from!}</td>
  				<td class='note'>
				  <input type='text' name='fromd' value='{$smarty.get.fromd}' size='2' maxlength='2' onKeyDown="TabNext(this,'down',2)" onKeyUp="TabNext(this,'up',2,this.form['fromm'])" > -
  				  <input type='text' name='fromm' value='{$smarty.get.fromm}' size='2' maxlength='2' onKeyDown="TabNext(this,'down',2)" onKeyUp="TabNext(this,'up',2,this.form['fromy'])"> -
  				  <input type='text' name='fromy' value='{$smarty.get.fromy}' size='4' maxlength='4'> (dd-mm-yyyy)
  				</td>
				<td class='admin_info'>{!to!}</td>
  				<td class='note'>
				  <input type='text' name='tod' value='{$smarty.get.tod}' size='2' maxlength='2' onKeyDown="TabNext(this,'down',2)" onKeyUp="TabNext(this,'up',2,this.form['tom'])" > - 
				  <input type='text' name='tom' value='{$smarty.get.tom}' size='2' maxlength='2' onKeyDown="TabNext(this,'down',2)" onKeyUp="TabNext(this,'up',2,this.form['toy'])"> -
  				  <input type='text' name='toy' value='{$smarty.get.toy}' size='4' maxlength='4'> (dd-mm-yyyy)
  				</td>
				<td class='admin_info' colspan='2'>
				  <input type='submit' name='submit' value='submit' />
				</td>
			  </tr>
			</table>
		  </form>
		  </td>
		</tr>
		<tr class='subtitle'>
		 <td>ID</td>
		  <td>{!total_price!}</td>
		  <td>{!tickets!}</td>
		  <td>{!timestamp!}</td>
		  <td>{!actions!}</td>
		  <!--<td>{!actions!}</td>-->
		</tr>
		{assign var='length' value='15'}

		{assign var='dates' value="fromd=`$smarty.get.fromd`&fromm=`$smarty.get.fromm`&fromy=`$smarty.get.fromy`&tod=`$smarty.get.tod`&tom=`$smarty.get.tom`&toy=`$smarty.get.toy`"}
		{assign var='pos' value="first=`$smarty.get.first`"}

		{if $smarty.get.fromy and $smarty.get.fromm and $smarty.get.fromd}
  		  {assign var='from' value="`$smarty.get.fromy`-`$smarty.get.fromm`-`$smarty.get.fromd`"}
		{/if}

		{if $smarty.get.toy and $smarty.get.tom and $smarty.get.tod}
  		  {assign var='to' value="`$smarty.get.toy`-`$smarty.get.tom`-`$smarty.get.tod` 23:59:59.999999"}
		{/if}

		{order->order_list status="send" first=$smarty.get.first length=$length start_date=$from end_date=$to}
		{counter print=false assign=count}  
		{if $count lt ($length+1)}    

		{if $shop_order.order_status eq "cancel"}
		<tr class='admin_order_{$shop_order.order_status}'>
		{elseif $shop_order.order_status eq "reemit"}
		<tr class='admin_order_{$shop_order.order_status}'>
		{elseif $shop_order.order_status eq "res"}
		<tr class='admin_order_{$shop_order.order_status}'>
		{elseif $shop_order.order_shipment_status eq "send"}
		<tr class='admin_order_{$shop_order.order_shipment_status}'>
		{elseif $shop_order.order_payment_status eq "payed"}
		<tr class='admin_order_{$shop_order.order_payment_status}'>
		{elseif $shop_order.order_status eq "ord"}
		<tr class='admin_order_{$shop_order.order_status}'>
		{else}
		<tr class='admin_order_cancel'>
		{/if}
		  <td class='admin_info'>{$shop_order.order_id}</td>
		  <td class='admin_info'>{$shop_order.order_total_price}</td>
		  <td class='admin_info'>{$shop_order.order_tickets_nr}</td>
		  <td class='admin_info'>{$shop_order.order_date}</td>
		  <!--<td class='admin_info'></td>-->
		  <td class='admin_info' align="right">
		  	<a href='shop.php?process=sent&order_id={$shop_order.order_id}'>Click to View</a> 
		  {if $shop_order.order_status neq "cancel" and $shop_order.order_status neq "reemit"}
			<a href='print.php?mode=doit&order_id={$shop_order.order_id}'><img border='0' src='images/printer.gif'></a> 
			<a href='javascript:if(confirm("{!cancel_order!} {$shop_order.order_id}?")){literal}{location.href="shop.php?action=cancel_order&place={/literal}{$shop_order.order_place}{literal}&order_id={/literal}{$shop_order.order_id}&{$dates}&{$pos}{literal}";}{/literal}'>
			<img border='0' src='images/trash.png' /></a>
		  {else}
		  </td>
		  {/if}
		</tr>
	  	{/if}
		{/order->order_list}
  	  </table>
<!-- navigation -->
	  <table width='100%' border='0' ><tr>
		<td width='33%' align='left'>

		{if $smarty.get.first gt 0}
		  {if ($smarty.get.first-$length) lt 0}
		    <a href='shop.php?action=view_orders&first=0'>&lt;&lt;&lt; {!prev!}</a>
		  {else}
		    <a href='shop.php?action=view_orders&first={$smarty.get.first-$length}&{$dates}'>&lt;&lt;&lt; {!prev!}</a>
		  {/if}
		{/if}
		</td>
		<td align='center' width='33%' class='admin_info'>
		{$smarty.get.first+1} 
		  - 
		{if $count eq ($length+1)}
		  {$smarty.get.first+$length}
		{else}
		  {$smarty.get.first+$count}
		{/if}
		</td>
		<td align='right'  width='33%'>
		{if $count eq $length}
		  <a href='shop.php?action=view_orders&first={$smarty.get.first+$length}&{$dates}'>{!next!} &gt;&gt;&gt;</a>
		{/if}
		</td>
	  </tr>
	</table>
	<br />
	{include file='menu_order.tpl'}
  </td>
</tr>
<tr>
	<td colspan="2" width='33%' align="left" bgcolor="lightgrey" ><a href="view.php?process=on">Back to {!pos_currenttickets!}</a></td>
</tr>
</table>