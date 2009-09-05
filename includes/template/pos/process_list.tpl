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

{literal}
  <script>
    <!--
      // Author: Matt Kruse <matt@mattkruse.com>
      // WWW: http://www.mattkruse.com/
      TabNext()

      // Function to auto-tab field
      // Arguments:
      // obj :  The input object (this)
      // event: Either 'up' or 'down' depending on the keypress event
      // len  : Max length of field - tab when input reaches this length
      // next_field: input object to get focus after this one

      var field_length=0;

      function TabNext(obj,event,len,next_field) {
        if (event == "down") {
          field_length=obj.value.length;
        }
        else if (event == "up") {
          if (obj.value.length != field_length) {
            field_length=obj.value.length;
            if (field_length == len) {
              next_field.focus();
            }
          }
        }
      }
    -->
  </script>
{/literal}

{assign var='length' value='15'}

{assign var='dates' value="fromd=`$smarty.get.fromd`&fromm=`$smarty.get.fromm`&fromy=`$smarty.get.fromy`&tod=`$smarty.get.tod`&tom=`$smarty.get.tom`&toy=`$smarty.get.toy`"}
{assign var='pos' value="first=`$smarty.get.first`"}

{if $smarty.get.fromy and $smarty.get.fromm and $smarty.get.fromd}
    {assign var='from' value="`$smarty.get.fromy`-`$smarty.get.fromm`-`$smarty.get.fromd`"}
{/if}

{if $smarty.get.toy and $smarty.get.tom and $smarty.get.tod}
    {assign var='to' value="`$smarty.get.toy`-`$smarty.get.tom`-`$smarty.get.tod` 23:59:59.999999"}
{/if}
  
<table border='0' width='100%' >
  <tr>
    <td>
      <table width='100%' cellspacing='1' cellpadding='5' border=0 >
      {include file='process_dateselect.tpl'}
      <tr class='subtitle'>
          <td>ID</td>
          <td>{!total_price!}</td>
          <td>{!tickets!}</td>
          <td>{!timestamp!}</td>
          <td>{!actions!}</td>
        </tr>

        {order->order_list status=$status not_status=$not_status not_sent=$not_sent first=$smarty.get.offset length=$length start_date=$from end_date=$to}
          {counter print=false assign=count}  
          {if $count lt ($length+1)}    

            {if $shop_order.order_status eq "cancel"}
              <tr class='admin_order_{$shop_order.order_status}'>
            {elseif $shop_order.order_status eq "reemit"}
              <tr class='admin_order_{$shop_order.order_status}'>
            {elseif $shop_order.order_status eq "res"}
              <tr class='admin_order_{$shop_order.order_status}'>
            {elseif $shop_order.order_shipment_status eq "send"}
              <tr class='admin_order_{$shop_order.order_shipment_status}'>
            {elseif $shop_order.order_payment_status eq "payed"}
              <tr class='admin_order_{$shop_order.order_payment_status}'>
            {elseif $shop_order.order_status eq "ord"}
              <tr class='admin_order_{$shop_order.order_status}'>
            {else}
              <tr class='admin_order_cancel'>
            {/if}
              <td class='admin_info'>{$shop_order.order_id}</td>
              <td class='admin_info'>{$shop_order.order_total_price}</td>
              <td class='admin_info'>{$shop_order.order_tickets_nr}</td>
              <td class='admin_info'>{$shop_order.order_date}</td>
              <td class='admin_info' align="right">
                <a href='view.php?order_id={$shop_order.order_id}'>
                  <img src='images/view.png' border='0'>
                </a>
                {if $shop_order.order_status neq "cancel" and $shop_order.order_status neq "reemit"}
                  <a href='print.php?order_id={$shop_order.order_id}'>
                    <img border='0' src='images/printer.gif'>
                  </a> 
                  <a href='javascript:if(confirm("{!cancel_order!} {$shop_order.order_id}?")){literal}{location.href="view.php?action=cancel_order&place={/literal}{$shop_order.order_place}{literal}&order_id={/literal}{$shop_order.order_id}&{$dates}&{$pos}{literal}";}{/literal}'>
                    <img border='0' src='images/trash.png'>
                  </a>
                {/if}
              </td>
            </tr>
          {/if}
        {/order->order_list}
      </table>
      {gui->navigation offset=$smarty.get.offset count=$shop_orders_count length=$length}
    <br />
    {include file='process_menu.tpl'}
    </td>
  </tr>
</table>

