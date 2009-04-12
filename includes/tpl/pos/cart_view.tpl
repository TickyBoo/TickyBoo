{*
 * phpMyTicket - a ticket reservation system
 * Copyright (C) 2004 Anna Putrino, Stanislav Chachkov
 *
 * This file is part of phpMyTicket.
 *
 * phpMyTicket is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * phpMyTicket is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with phpMyTicket; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *}<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <td align='center' valign='top'>
   {include file="cart_content.tpl"}
<br>
<center>
{if $cart->can_checkout_f()}
  <form action='index.php' method=post>
<table width='100%' border='0' cellspacing='0' cellpadding='1'style='padding:5px; border:#45436d 1px solid;'> 
<tr>
  <td rowspan='7'><img src='images/dot.gif' width='1' height='100'></td>
  <td colspan='3' align='left'><font size='2'> <b>{!payment!}</b></font></td>
</tr>
{handling sp='on'}  
{* example: how to restrict handlings
 if ($shop_handling.handling_id neq 29) or ($user_auth->user_id eq 1265) *}
<tr><td class='payment_form'>
{if $shop_handling.handling_shipment eq 'sp'}
<input style='border:0px;' type='radio' id='{$shop_handling.handling_id}_check' name='handling' value='{$shop_handling.handling_id}'>
{else}
<input style='border:0px;' type='radio' id='{$shop_handling.handling_id}_check' name='handling' value='{$shop_handling.handling_id}'>
{/if}
</td>
<td class='payment_form'><label for='{$shop_handling.handling_id}_check'>
	{!payment!}
	{if $shop_handling.handling_text_payment}{eval var=$shop_handling.handling_text_payment}{/if}
	<br>
	{!shipment!}
	{if $shop_handling.handling_text_shipment}{eval var=$shop_handling.handling_text_shipment}{/if}
    </label>
</td>
<td class='payment_form'>
    {assign var=fee value="`$shop_handling.handling_fee_percent*$total/100.00+$shop_handling.handling_fee_fix`"} 
    + {$fee|string_format:"%.2f"} {$organizer_currency} 
  </td></tr>
{* /example /if*}
{/handling}
{* /payment *}
</table>
<br>
  <input type='submit' name='submit_payment' value='{#order_it#}'>
  <input type='hidden' name='action' value='order_tickets'>
  </form>
  <form action='index.php' method='post'>
  <input type="hidden" name='handling' value='1'>
  <input type='submit' name='submit_reserve' value='Reserve Tickets'>
  <input type='hidden' name='action' value='reserve_tickets'>
  </form>
{/if}
<a class='shop_link' href='index.php'>{!order_more_tickets!}
</a>
</center>
</td>
        </tr>
      </table>