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
 *}
<table class='view_cart' cellpadding='3' border='0' width='650' style='padding:5px;'> 
<tr><td class='title'  align='center' colspan='4'>{!order_id!} {$order_id}<br>
	<div class="help">{!pos_checkdetails!}</div> </td></tr>
<tr><td class='view_cart_title' valign='top'>    
   {!event!}
</td> 
<td class='view_cart_title' valign='top'>    
    {!date_venue!}
</td> 
<td class='view_cart_title' valign='top'>    
    {!tickets!}
 </td> 
<td class='view_cart_title' align='right' valign='top'>    
    {!total!}
</td> 
</tr>
{cart->items}
{if $order_seats_id[$seats_id.0]}
<tr class='view_cart_tr'>
<td class='view_cart_td' valign='top'> {$event_item->event_name} </td>
<td class='view_cart_td' valign='top'>
{$event_item->event_date|date_format:"%e %b %Y"} - 
{$event_item->event_time|date_format:" %Hh%M"} <br> 
{$event_item->event_ort_name}</td>
<td class='view_cart_td' valign='top'>
{$seat_item->count()} x
{$category_item->cat_name} ({$category_item->cat_price|string_format:"%.2f"}
{$organizer_currency})
{if !$category_item->cat_numbering or $category_item->cat_numbering eq 'both'}
  {section name="seats" loop=$seats_id}
    <li> {$seats_nr[seats][0]} - {$seats_nr[seats][1]}
  {/section}
{elseif $category_item->cat_numbering eq 'rows'}
{foreach key=row item=count from=$seat_item_rows_count}
  <li>{$count} x    
    {!row!}
{$row}
{/foreach}
{/if}  
</td> <td class='view_cart_td' align='right' valign='top'>
{$seat_item->total_price($category_item->cat_price)|string_format:"%.2f"}  
</td>
</tr>
{/if} 
{/cart->items}
</td></tr>
<tr>
<td class='view_cart_total' >    
    {!total_price!}
 </td>
<td class='view_cart_total'> 
{$organizer_currency}
{$order_total_price|string_format:"%1.2f"}</td></tr>
</table></center>
{literal}
<script LANGUAGE='JavaScript'>
    <!--
    function confirmSubmit()
    {
    var agree=confirm("".$this->con("sure_to_cancel")."");
    if (agree)
    return true ;
    else
    return false ;
    }
    // -->
    </script>
{/literal}
<br><center><table width='600' border='0'><tr><td align='center'>
{if !$update->is_demo()}
<form action='index.php' method='post'>
    <input type='hidden' name='action' value='print_tickets'>
{/if}
    <input type='submit' name='print_tickets' value='{!confirm_and_print!}'>
    <input type='hidden' name='order_id' value='{$order_id}'>
    </form>
</td><td align='center'>
    <form action='index.php' method='post'>
    <input type='submit' name='cancel_order' value='{!cancel_order!}' onclick='return confirmSubmit()'>
    <input type='hidden' name='action' value='cancel_order'>
    <input type='hidden' name='order_id' value='{$order_id}'>
{if !$update->is_demo()}
    </form>
{/if}
    </td></tr></table></center>