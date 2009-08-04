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
{if $user->mode() eq 0 && !$user->active}
	{include file="user_activate.tpl"}
{else}
	{include file="header.tpl" name=!shopping_cart_check_out! header=!Handling_cont_mess!}
    {if  $user->mode() lte 2 && $user->new_member}
    	<table class='table_dark' cellpadding='5' bgcolor='white' width='100%'>
        	<tr>
				<td class='TblLower'>
          			<span class='title'>{!act_name!}<br><br> </span>
          			{include file="user_registred.tpl"}
        		</td>
			</tr>
		</table>
 	{/if}

	{if $order_error}
    	<div class='error'>{$order_error}</div><br />
	{/if}

  	{include file="cart_content.tpl" check_out="on" }
  	{assign var=total value=$cart->total_price_f()}
	<br />
  	<table cellpadding="0" cellspacing='0' border='0' width='100%'>
    	<tr>
    		<td width="50%" valign="top" align="left">
      			{include file="user_address.tpl" title="on"}
    		</td>
    		<td valign='top' align="right">
    			{if !$update->is_demo()}
       			<form method='post' name='handling' onsubmit='this.submit.disabled=true;return true;'>
          			{ShowFormToken name='OrderHandling'}
          			<input type='hidden' name='action' value='confirm' />
    			{/if}
 	  			<table border=0 width='90%' cellpadding="5" bgcolor='white'>
        			<tr>
        		  		<td colspan='3' class='TblHeader' align='left'>{!handlings!}</td>
       				</tr>
        			{assign var=min_date value=$cart->min_date_f()}
        			{handling www='on' event_date=$min_date }
          			
				  	<tr class="{cycle name='payments' values='TblHigher,TblLower'}">
          		  		<td class='payment_form'>
            		  		<input checked="checked" type='radio' id='{$shop_handling.handling_id}_check' class='checkbox_dark' name='handling_id' value='{$shop_handling.handling_id}'>
          		  		</td>
          		  		<td class='payment_form'>
          		  			<label for='{$shop_handling.handling_id}_check'>
            		  			{!payment!}: {eval var=$shop_handling.handling_text_payment}<br>
            		  			{!shipment!}: {eval var=$shop_handling.handling_text_shipment}
          		  			</label>
          		  		</td>
          		  		<td class='payment_form' align='right'>
             				{assign var=fee value="`$total*$shop_handling.handling_fee_percent/100.00+$shop_handling.handling_fee_fix`"}
             				{if  $fee}
                   				+ {gui->valuta value=$fee|string_format:"%.2f"}
                  			{/if}&nbsp;
          		  		</td>
          			</tr>
            		{/handling}
        			{if $update_view.currentres}
       				<tr class="{cycle values='TblHigher,TblLower'}">
          				<td colspan="3">
           			  		{*$update_view.maxres*}
           			  		{!limit!}
          				</td>
          			</tr>
        			{/if}
      			</table>
  				<br />
    	    	<input type='submit' name='submit' value='{!order_it!}'/>
    			{if !$update->is_demo()}
    			</form>
    			{else}
       			<div class='error'><br/> For safety issues we have disabled the order button. </div>
    			{/if}
    			{* update->view event_date=$min_date user=user->user_id *}
    			{if $update_view.can_reserve }
        			{if !$update->is_demo()}
       				<form action='' method='post' name='handling' onsubmit='this.submit.disabled=true;return true;'>
          				<input type='hidden' name='action' value='reserve'>
          			{ShowFormToken name='ReservHandling'}
          			{/if}
    		  		{!orclick!}
      					<input type='submit' name='submit_reserve' value='{!reserve!}'>
    				
					{if !$update->is_demo()}
					</form>
    				{/if}
 				{/if}
    		</td>
    	</tr>
	</table>
{/if}
{include file="footer.tpl"}