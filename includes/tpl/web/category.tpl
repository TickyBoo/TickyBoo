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

{if $smarty.get.category_id}
  {assign var="category_id" value=$smarty.get.category_id}
{elseif $smarty.post.category}
  {assign var="category_id" value=$smarty.post.category}
{/if}
{category category_id=$category_id event='on' placemap='on'}

  {if $shop_category.category_numbering neq 'none'}
    {include file="header.tpl" name=!elect_seats! }
    <form name='f' action='index.php' method='post'>
      <table class='pm_info'>
        <tr><td class='title' align='center'>{$shop_category.event_name}</td></tr>
        <tr>
          <td class='title' align='center'>
            {$shop_category.event_date|date_format:!date_format!} - {$shop_category.event_time|date_format:!time_format!}  {* "%A %e %B %Y" *}
            {$shop_category.pm_name} - {$shop_category.category_name} ({valuta value=$shop_category.category_price})
          </td>
        </tr>
        {if not $user->logged}
          <tr><td  align='center'>
          {!sign_in_first!}
          </td></tr>
        {/if}
        {if $shop_category.category_numbering neq 'none'}
          <tr>
            <td  align='center'>
         	    {!select_seat!}
              {!click_on_reserve!}
            </td>
          </tr>
        {/if}
        {if $shop_category.event_order_limit}
          <tr>
            <td  align='center'>
              {!order_limit!}
              {$shop_category.event_order_limit}
            </td>
          </tr>
        {/if}

        {if $shop_category.category_numbering eq 'none'}
          </table><br>
          <center>
            <table border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td class='event_data'>
                  {!number_seats!}
                </td>
                <td class='title'>
                  <input type='text' name='place' size='4' maxlength='4' align='right' />
                  <input type='hidden' name='numbering' value='none' />
                </td>
              </tr>
            </table>
          </center>
        {else}
            {if $shop_category.category_numbering eq "rows"}
              <tr>
                <td class='choice_info' align='center'>
                  <b>{!only_rows_numbered!}</b>
                </td>
              </tr>
            {/if}
          </table><br><br>
          <center>
            {placemap  category=$shop_category}
          </center>
        {/if}
      <br>
      <center>
        {if not $user->logged}
        	<p>{!Please_login!}</p>
        {else}
          <input type='submit' name='submit' value='{!reserve!}'>
        {/if}
  	  </center>
      <input type='hidden' name='category' value='{$shop_category.category_id}'>
      <input type='hidden' name='event' value='{$shop_category.category_event_id}'>
      <input type='hidden' name='action' value='addtocart'>
    </form>
  {else}
    {include file="event.tpl"}
  {/if}
{/category}

