{*
%%%copyright%%%
 * phpMyTicket - ticket reservation system
 * Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of phpMyTicket.
 *
 * This file may be distributed and/or modified under the terms of the
 * "GNU General Public License" version 2 as published by the Free
 * Software Foundation and appearing in the file LICENSE included in
 * the packaging of this file.
 *
 * Licencees holding a valid "phpmyticket professional licence" version 1
 * may use this file in accordance with the "phpmyticket professional licence"
 * version 1 Agreement provided with the Software.
 *
 * This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
 * THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE.
 *
 * The "phpmyticket professional licence" version 1 is available at
 * http://www.phpmyticket.com/ and in the file
 * PROFESSIONAL_LICENCE included in the packaging of this file.
 * For pricing of this licence please contact us via e-mail to 
 * info@phpmyticket.com.
 * Further contact information is available at http://www.phpmyticket.com/
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@phpmyticket.com if any conditions of this licencing isn't 
 * clear to you.
 
 *}{assign var="cart_empty" value=$cart->is_empty_f()}
{if $cart_empty }

<table cellpadding='5' cellspacing='1' width='100%' bgcolor="#ffffff" >
<tr><td class='title' align='center'>{#shopping_cart_pos#}
</td></tr>
<tr><td align='center' class='view_cart_td' ><br><br>{#cart_empty_pos#}<br><br><br><br>  
</td></tr></table>

{else}

<table  cellpadding='5' cellspacing='0' width='100%' border='0'> 
<tr><td class='title' colspan='4' align='center' valign='top'  bgcolor="#ffffff">
   {#shopping_cart_pos#} 
</td></tr>
<tr><td class='help' colspan='4' align='center' valign='top'  bgcolor="#ffffff">
    Check the tickets below: to add more click "Order More Tickets"<br>
   	Check the Delivery method and Payment Method.<br>
    To reserve the tickets click "Reserve Tickets Button".<br>
</td></tr> 
<tr><td class='view_cart_title' valign='top'  bgcolor="#ffffff">    
   {#event#}
</td> 
<td class='view_cart_title' valign='top'  bgcolor="#ffffff">    
    {#tickets#}
 </td>  
<td class='view_cart_title'  valign='top'  bgcolor="#ffffff">    
    {#total#}
</td> 
<td class='view_cart_title' valign='top'  bgcolor="#ffffff">    
    {#expires_in#}
</td> 
</tr>
{cart->items}

<tr class='view_cart_tr'>
<td class='view_cart_td' valign='top'  bgcolor="#ffffff"> <b>{$event_item->event_name}</b> <br>

{$event_item->event_date|date_format:"%e %b %Y"} - 
{$event_item->event_time|date_format:" %Hh%M"} <br> 
{$event_item->event_ort_name}
{$category_item->cat_name} {$category_item->cat_price|string_format:"%.2f"}
{$organizer_currency}

</td><td class='view_cart_td' valign='top'  bgcolor="#ffffff">
<table border='0'>
{section name="seats" loop=$seats_id}
<tr><td class='view_cart_td'>
{if !$category_item->cat_numbering or $category_item->cat_numbering eq 'both'}
    {$seats_nr[seats][0]} - {$seats_nr[seats][1]}
{elseif $category_item->cat_numbering eq 'rows'}
    {#row#}
 {$seats_nr[seats][0]}
{/if}
</td><td class='view_cart_td'>
{assign var='disc' value=$seat_item->discounts[seats]}
{if $disc}
    {$disc->discount_name}
{else}
    {#normal#}
{/if}
</td><td class='view_cart_td'>
{if $disc}
 {$disc->apply_to($category_item->cat_price)|string_format:"%.2f"} 
 {$organizer_currency}
{else}
  {$category_item->cat_price|string_format:"%.2f"} 
	{$organizer_currency}
{/if}

</td></tr>  
{/section}
</table>
		
</td> <td class='view_cart_td'  valign='top'  bgcolor="#ffffff">
{$seat_item->total_price($category_item->cat_price)|string_format:"%.2f"}  
</td>
<td class='view_cart_td'  valign='top'  bgcolor="#ffffff"> 
{if $seat_item->is_expired()}
    <font color='red'>{#expired#}</font>
{else}
<img src='images/clock.gif' valign="middle" align="middle"> {$seat_item->ttl()} min.    
{/if}    
<br><a class='shop_link' href='index.php?action=remove&event_id={$event_item->event_id}&cat_id={$category_item->cat_id}&item={$seat_item_id}'> 
    {#remove#}
</a>
 
</td></tr>
 
{/cart->items}
 <tr><td class='view_cart_total' colspan='3'  bgcolor="#ffffff">    
    {#total_price#}
 </td><td class='view_cart_total'   bgcolor="#ffffff"> {cart->total_price|string_format:"%.2f"} 
 {$organizer_currency}
 </td></tr> 
 </table>
{/if}
