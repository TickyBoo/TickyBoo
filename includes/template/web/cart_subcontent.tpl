{*                  %%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2010 Christopher Jenkins, Niels, Lou. All rights reserved.
 *
 * Original Design:
 *	phpMyTicket - ticket reservation system
 * 	Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of FusionTicket.
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
 * Any links or references to Fusion Ticket must be left in under our licensing agreement.
 *
 * By USING this file you are agreeing to the above terms of use. REMOVING this licence does NOT
 * remove your obligation to the terms of use.
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact help@fusionticket.com if any conditions of this licencing isn't
 * clear to you.
 *}
<tr class="{cycle name='events' values='tr_0,tr_1'}">
  <td  valign='top'> <b>{$event_item->event_name}</b> <br>
    {$event_item->event_date|date_format:!shortdate_format!} -
    {$event_item->event_time|date_format:!time_format!} <br>
    {$event_item->event_ort_name} - {$event_item->event_ort_city}
  </td>
  <td  valign='top'>
    {$seat_item->count()} x {$category_item->cat_name} {* ({valuta value=$category_item->cat_price|string_format:"%.2f"}) *}
    {if $seat_item->discounts}        {* there are discounts *}
      <table border='0' width='100%'>
        {section name="seats" loop=$seats_id}
          <tr>
            <td class='view_cart_td'><li>
              {if !$category_item->cat_numbering or $category_item->cat_numbering eq 'both'}
                  {$seats_nr[seats][0]} - {$seats_nr[seats][1]}
              {elseif $category_item->cat_numbering eq 'seat'}
                  {$seats_nr[seats][1]}
              {elseif $category_item->cat_numbering eq 'rows'}
                  {!row!} {$seats_nr[seats][0]}
              {/if}</li>
            </td>
            <td class='view_cart_td'>
              {assign var='disc' value=$seat_item->discounts[seats]}
              {if $disc}
                  {$disc->discount_name}
              {else}
                  {!normal!}
              {/if}
            </td>
            <td align='right'>
              {if $disc}
                {valuta value=$disc->apply_to($category_item->cat_price)|string_format:"%.2f"}
              {else}
                {valuta value=$category_item->cat_price|string_format:"%.2f"}
              {/if}
            </td>
          </tr>
        {/section}
      </table>
    {else}                              {* no discounts *}
      <table border='0' width='100%'>
        <tr>
          <td class='view_cart_td'><li>
            {if !$category_item->cat_numbering or $category_item->cat_numbering eq 'both'}
              {section name="seats" loop=$seats_id}
                 {$seats_nr[seats][0]} - {$seats_nr[seats][1]}
              {/section}
            {elseif $category_item->cat_numbering eq 'seat'}
              {section name="seats" loop=$seats_id}
                {$seats_nr[seats][1]}
              {/section}
            {elseif $category_item->cat_numbering eq 'rows'}
              {foreach key=row item=count from=$seat_item_rows_count}
                {$count} x {!row!} {$row}
              {/foreach}
            {/if}
            </li>
          </td>
          <td class='view_cart_td'>
            {!normal!}
          </td>
          <td align='right'>
            {valuta value=$category_item->cat_price|string_format:"%.2f"}
          </td>
        </tr>
      </table>
    {/if}
  </td>
  <td  valign='top' align='right' >
    {valuta value=$seat_item->total_price($category_item->cat_price)|string_format:"%.2f"}
  </td>
  {if $three_cols neq "on"}
    <td  valign='top'>
      {if $seat_item->is_expired()}
        <span style="color:#ff0000;">{!expired!}</span>
      {else}
        <img src='{$_SHOP_themeimages}clock.gif' valign="middle" align="middle"> {$seat_item->ttl()} {!minutes!}.
      {/if}
      {if $check_out neq "on"}
        <br><a  href='index.php?action=remove&event_id={$event_item->event_id}&cat_id={$category_item->cat_id}&item={$seat_item_id}'>
          {!remove!}
        </a>
      {/if}
    </td>
  {/if}
</tr>