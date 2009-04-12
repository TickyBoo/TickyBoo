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
 
 *}<center><table width='600' border='0'>
<tr>
<div class="help">Check the details, If cash or check Click "Confirm and Print"<br>
		If you have to pay with Credit/Debit Card click the "PayNow" button and Follow the Paypal instructions.</div>
<td width='300' align='left'>
 <table  width='100%' border='0' cellspacing='0' cellpadding='1'
style='padding:5px;'>
<tr>
 <tr><td class='user_address_td'>{user->user_firstname} 
 {user->user_lastname}</td></tr>
 <tr><td class='user_address_td'>{user->user_address}</td></tr>
 {if $user->user_address1}
 <tr><td class='user_address_td'>{user->user_address1}</td></tr> 
 {/if}
<tr><td class='user_address_td'>{user->user_zip} {user->user_city}</td></tr>
<tr><td class='user_address_td'>{* country code=$user->user_country *}{include file="countries.tpl" code=$user->user_country}</td></tr>
<tr><td class='user_address_td'>{user->user_email}</td></tr></table>
</td><td align='right' width='350'>
<a href='javascript:window.print()'>
<img border='0' src='images/printer.png'></a>
</td></tr></table><br>
<table class='view_cart' cellpadding='3' border='0' width='600' style='padding:5px;'> 
<tr><td class='title'  align='center' colspan='4'>   
    {#order_id#}
{$order_id} </td></tr>
<tr><td class='view_cart_title' valign='top'>    
   {#event#}
</td> 
<td class='view_cart_title' valign='top'>    
    {#date_venue#}
</td> 
<td class='view_cart_title' valign='top'>    
    {#tickets#}
 </td> 
<td class='view_cart_title' align='right' valign='top'>    
    {#total#}
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
{$category_item->cat_name} ({$category_item->cat_price|string_format:"%.2f"}{$organizer_currency})


{if !$category_item->cat_numbering or $category_item->cat_numbering eq 'both'}
  {section name="seats" loop=$seats_id}
    <li> {$seats_nr[seats][0]} - {$seats_nr[seats][1]}
  {/section}
{elseif $category_item->cat_numbering eq 'rows'}
{foreach key=row item=count from=$seat_item_rows_count}
  <li>{$count} x    
    {#row#}
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
<tr><td class='order_ptotal'>    
    {#total_without_charges#}
</td>
<td class='order_ptotal'>{$organizer_currency} {$order_partial_price|string_format:"%1.2f"}</td></tr>
<tr><td class='order_ptotal' >    
   {#charges#}
</td>
<td class='order_ptotal'> {$organizer_currency} {$order_fee|string_format:"%1.2f"}</td></tr>
<tr>
	<td class='view_cart_total' >{#total_price#}</td>
	<td class='view_cart_total'> {$organizer_currency} {$order_total_price|string_format:"%1.2f"}</td>
</tr>
<tr>
  <td colspan="2">
	{handling handling_id=$smarty.post.handling}
	{if $shop_handling.handling_html_template}
	{eval var=$shop_handling.handling_html_template}
	{/if}
	{/handling}
  </td>
</tr>
</table></center>

{literal}
<script LANGUAGE='JavaScript'>
    <!--
    function confirmSubmit()
    {
    var agree=confirm(\"".$this->con("sure_to_cancel")."\");
    if (agree)
    return true ;
    else
    return false ;
    }
    // -->
    </script>
{/literal}
    
<br>
<center>
<table width='600' border='0'>
  <tr>
	<td align='center'>

<form action='index.php' method='post'>
    <input type='submit' name='print_tickets' value='Confirm and Print'>
    <input type='hidden' name='order_id' value='{$order_id}'>
    <input type='hidden' name='action' value='print_tickets'>
    </form>

</td><td align='center'>
    <form action='index.php' method='post'>
    <input type='submit' name='cancel_order' value='{#cancel_order#}' onClick='return confirmSubmit()'>
    <input type='hidden' name='action' value='cancel_order'>
    <input type='hidden' name='order_id' value='{$order_id}'>
    </form>
	</td>
  </tr>
</table>
</center>

