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

{assign var='event_id'    value=$smarty.post.event_id}
{assign var='category_id' value=$smarty.post.category_id}

{if $event_id}
  {discount all='on' event_id=$event_id }{/discount}
  {if $shop_discounts}
    {include file="header.tpl" name=!discounts!}
    {category event='on' category_id=$category_id}
      <form action='index.php' method='post'>
        {ShowFormToken name='Discounts'}
        <table class="table_midtone">
          <tr>
            <td valign='top'>
              <table   width='100%' border='0' cellspacing='0'>
                <tr>
                  <td class='title2' colspan='{$shop_discounts_count+1}' valign='top'>
                    {$shop_category.event_name} - {$shop_category.category_name}
                  </td>
                </tr>
                <tr>
                  <td  valign='top'> <b>{!place_nr!}</b></td>
                  <td colspan='{$shop_discounts_count+1}'></td>
                </tr>
                {if $last_item->load_info()}{/if}
                {assign var='places_id' value=$last_item->places_id}
                {assign var='places_nr' value=$last_item->places_nr}
                {section name='i' loop=$places_id}
                  <tr>
                    <td >
                      {if $shop_category.category_numbering eq 'both'}
                        {$places_nr[i].0} - {$places_nr[i].1}
                      {elseif $shop_category.category_numbering eq 'rows'}
                        {!row!}{$places_nr[i].0}
                      {elseif $shop_category.category_numbering eq 'none'}
                        {$index}
                      {/if}
                    </td>
                    <td style='font-size:11px;font-family:Verdana;'>
                      <label><input class='checkbox_dark' type='radio' name='discount[{$places_id[i]}]' value='0' checked>{!normal!}</label>
                    </td>
                    {section name='d' loop=$shop_discounts}
                      <td style='font-size:11px;font-family:Verdana;'>
                        <label><input class='checkbox_dark' type='radio' name='discount[{$places_id[i]}]' value='{$shop_discounts[d].discount_id}'>{$shop_discounts[d].discount_name}</label>
                      </td>
                    {/section}
                  </tr>
                {/section}
                <tr>
                  <td colspan='{$shop_discounts_count+2}' align='center'>
                    <input type='hidden' name='event_id' value='{$shop_category.event_id}'>
                    <input type='hidden' name='category_id' value='{$shop_category.category_id}'>
                    <input type='hidden' name='item_id' value='{$last_item->id}'>
                    <input type='hidden' name='action' value='adddiscount'><br>
                    <input type='submit' name='submit' value='{!continue!}'>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </form>
    {/category}
  {else}
    {include file='cart_view.tpl'}
  {/if}
{/if}