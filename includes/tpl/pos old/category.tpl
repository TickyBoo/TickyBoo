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
 
 *}<table class='pm_box'><tr><td>
{if $smarty.get.category_id}
  {assign var=category_id value=$smarty.get.category_id}
{elseif $smarty.post.category}
  {assign var=category_id value=$smarty.post.category}
{/if}

{if $category_id}
{category category_id=$category_id event='on' placemap='on'}

  <table width='100%' cellpadding='2' cellspacing='0' class='pm_info'>
  <tr><td class='title' align='center'>{$shop_category.event_name}</td></tr>
  <tr><td class='title' align='center'>
  {$shop_category.event_date|date_format:"%e %b %Y"} - {$shop_category.event_time|date_format:" %Hh%M"}
  </td></tr>
  <tr><td class='title' align='center'>
  {$shop_category.ort_name} - {$shop_category.category_name} 
  ({$shop_category.category_price})
  </td></tr>

 {if $shop_category.category_numbering neq 'none'}
  <tr><td class='help' align='center'>
    {#select_seat#}, the seat name will appear if you hover your mouse over it.<br>
	Free Seats are Green, Red Seats are Taken. 
  </td></tr>
  <tr><td class='help' align='center'>
    {#click_on_reserve#}
</td></tr>
 {/if} 
{if $shop_category.category_numbering eq "rows"}
 <tr><td class='choice_info' align='center'><b>
    {#only_rows_numbered#}</b></td></tr>
{/if}
</table>
<form name='f' action='shop.php' method='post'><center>

{if $shop_category.category_numbering eq 'none'}
<br><br>
<span class='title'>
{#number_seats#} : </span>
<input type='text' name='place' size=4 maxlength=2>

{else}
{placemap category=$shop_category}
{/if}

<input type='submit' name='submit' value='{#reserve#}'>
	  </center>
          <input type='hidden' name='category' value='{$shop_category.category_id}'>
          <input type='hidden' name='event' value='{$shop_category.category_event_id}'>
          <input type='hidden' name='action' value='addtocart'>
          </form>
  
{/category}
{/if}
</td></tr></table>
