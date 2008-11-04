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
<!--CART_RESUME.tpl-->

<table width="195px" border="0" cellspacing="0" cellpadding="0" class="cart_table">
  <tr>
  	<td class="cart_title">
  	  {!shopcart!}&nbsp;
  	  {if $cart->is_empty_f() }
  	  	<img src="images/caddie.gif">
  	  {else}
    		<img src="images/caddie_full.png" border='0'>
  	  {/if}
  	</td>
  </tr>
  <tr>
  	{if $cart->is_empty_f() }
	    <td valign="top" class='cart_content' align="center">{!no_tick_res!}</td>
	  {else}
    	{assign var="cart_overview" value=$cart->overview_f() }
    	<td valign="top" class='cart_content' align='left' >
    	  <table>
      		<tr>
      		  <td class="cart_content">
        		  {if $cart_overview.valid }
        		  	<img src='images/ticket-valid.png'> {!valid_tickets!} {$cart_overview.valid}<br><br>
        		  {/if}
        		  {if $cart_overview.expired}
        		  	<img src='images/ticket-expired.png'> {!expired_tickets!} {$cart_overview.expired}<br><br>
        		  {/if}
        		  {if $cart_overview.valid }
                {assign var=timetl value=$smarty.now+$cart_overview.secttl}
          			<img src='images/clock.gif'> {!tick_exp_in!} <span id="countdown1">{$timetl|date_format:'%Y-%m-%d %H:%M:%S'} GMT+00:00</span>
        		  {/if}
      		  </td>
      		</tr>
    	  </table>
  	  </td>
    </tr>
    <tr>
    	<td class='cart_content'>
    	  <table>
        	{cart->items}
        	  {if not $seat_item->is_expired()}
          		<tr>
          		  <td class='cart_content' style='border-bottom:#cccccc 1px solid;padding-bottom:4px;padding-top:4px; font-size:10px;'>
            		  {$event_item->event_name}<br>
            		  {$category_item->cat_name}<br>
            		  {$seat_item->count()} {!x_tick!}{*$category_item->cat_price*}
          		  </td>
          		  <td  width="45%" valign='top' class='cart_content' style='border-bottom:#cccccc 1px solid;padding-bottom:4px;padding-top:4 font-size:10px;'>
            			<b>{$seat_item->total_price($category_item->cat_price)|string_format:"%.2f"}</b> {$organizer_currency}
            			<br>
            			<img src='images/clock.gif' valign='middle' align='middle'> {$seat_item->ttl()} min.
          		  </td>
          		</tr>
            {/if}
       		{/cart->items}
      		{if $cart_overview.valid }
        		<tr>
        		  <td align='center' class='cart_content' colspan='2'>
          			<br>
          			<a href='shop.php?action=view_cart'>{!view_order!}</a>
          			<br>
          			<br>{!tot_tick_price!} {cart->total_price|string_format:"%.2f"} {$organizer_currency}
        		  </td>
        		</tr>
        		<tr>
        		  <td align='center' class='cart_content' colspan='2'>
          			<form action="kasse.php">
            			<input type="submit" name="go_pay" value="{!checkout!}">
          			</form>
        	    </td>
        		</tr>
      		{/if}
    	  </table>
  	  {/if}
  	</td>
  </tr>
</table>
{$login_msg}