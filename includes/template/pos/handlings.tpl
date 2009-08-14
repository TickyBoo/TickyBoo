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

  	{assign var=min_date value=$cart->min_date_f()}
    {capture name='total_price'}
      {cart->total_price}
    {/capture}
  	{handling sp='on' event_date=$min_date }
      {assign var='total_price' value=$smarty.capture.total_price}

  		<tr class="{cycle name='payments' values='admin_list_row_0, admin_list_row_1'}">
  		  <td class='payment_form'> &nbsp;
      	  {if $smarty.post.handling_id == $shop_handling.handling_id}
      			 {assign var=checked value="checked='checked'"}
          {else}
             {assign var=checked value=""}
          {/if}
    		  <input {$checked} type='radio' id='{$shop_handling.handling_id}_check' class='checkbox_dark' name='handling_id' value='{$shop_handling.handling_id}'>
  		  </td>
  		  <td class='payment_form'>
  		  	<label for='{$shop_handling.handling_id}_check'>
    		  	{!payment!}: {eval var=$shop_handling.handling_text_payment}<br>
    		  	{!shipment!}: {eval var=$shop_handling.handling_text_shipment}
  		  	</label>
  		  </td>
  		  <td  class='view_cart_td'  valign='top'  align='right' width='100' id='price_{$shop_handling.handling_id}'>
     			{assign var=fee value="`$total_price*$shop_handling.handling_fee_percent/100.00`"}
     			{assign var=fee value="`$fee+$shop_handling.handling_fee_fix`"}
     			{if $fee neq 0.00}
           + {gui->valuta value=$fee|string_format:"%.2f"}
          {/if}
      	  {if $smarty.post.handling_id eq $shop_handling.handling_id}
       			{assign var=total_price value="`$total_price+$fee`"}
          {/if}
  		  </td>
  		</tr>
    {/handling}
  	<tr>
     	<td class='view_cart_total' colspan='2'>
      	{!total_price!}
    	</td>
    	<td class='view_cart_total' align='right' id='total_price'>
        {valuta value=$total_price|string_format:"%.2f"}
    	</td>
   	</tr>

  	{if $update_view.currentres}
  		<tr class="{cycle values='TblHigher,TblLower'}">
  			<td colspan="3">
   			  {*$update_view.maxres*}
   			  {!limit!}
  			</td>
  		</tr>
  	{/if}
