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
{assign var='order_id' value=$smarty.request.order_id}

{order->order_list first=0 length=1 status=$status not_status=$not_status}
  {assign var='next_order_id' value=$shop_order.order_id}
{/order->order_list}
<br>
<form name='f' action='view.php' method='post'>
  <table width='100%' border=0>
    {order->order_list order_id=$order_id}
      <tr>
        <td width='50%' valign='top'>
          <table width='99%' border='0'>
            <tr>
              <td class='title' colspan=1 >
                {!order_id!} {$shop_order.order_id}
              </td>
              <td class='title'  align='right'>&nbsp;
                {if $shop_order.order_status neq "cancel" and $shop_order.order_status neq "reemit"}
                  <a href='print.php?mode=doit&order_id={$shop_order.order_id}'><img border='0' src='images/printer.gif'></a> 
                  <a href='javascript:if(confirm("{!pos_deleteorder!}")){literal}{location.href="view.php?action=cancel_order&order_id={/literal}{$shop_order.order_id}{literal}";}{/literal}'>
                    <img border='0' src='images/trash.png'>
                  </a>
                {/if}
              </td>
            </tr>
            <tr>
              <td class='admin_info'>Next Order ID</td>
              <td class='subtitle'>{$next_order_id}</td>
            </tr>
            <tr>
              <td class='admin_info'>{!number_tickets!}</td>
              <td class='subtitle'>{$shop_order.order_tickets_nr}</td>
            </tr>
            <tr>
              <td class='admin_info'>{!user!} {!id!}</td>
              <td class='subtitle'>{$shop_order.order_user_id}</td>
            </tr>
            <tr>
              <td class='admin_info'>{!total_price!}</td>
              <td class='subtitle'>{$shop_order.order_total_price|string_format:"%1.2f"} {$organizer_currency}</td>
            </tr>
            <tr>
              <td class='admin_info'>{!order_date!}</td>
              <td class='subtitle'>{$shop_order.order_date}</td>
            </tr>
            <tr>
              <td class='admin_info'>{!status!}</td>
              <td class='subtitle'>
                {if $shop_order.order_status eq "res"}
                  <font color='orange'>{!reserved!}</font>
                {elseif $shop_order.order_status eq "ord"}
                  <font color='blue'>{!ordered!}</font>
                {elseif $shop_order.order_status eq "pros"}
                  <font color='blue'>{!processed!}</font>
                {elseif $shop_order.order_status eq "cancel"}
                  <font color='#cccccc'>{!cancelled!}</font>
                {elseif $shop_order.order_status eq "reemit"}
                  <font color='#ffffcc'>{!reemitted!}</font>
                  (<a href='view.php?action=view_order&order_id={$shop_order.order_reemited_id}'>
                    {$shop_order.order_reemited_id}
                  </a>)
                {/if}
              </td>
            </tr>
            {if $shop_order.order_status eq "res"}
              <input type='hidden' name='action' value='reorder' />
              <input type="hidden" name="user_id" value="{$shop_order.order_user_id}" />
              <input type="hidden" name="order_id" value="{$shop_order.order_id}" />
              <tr>
                <td colspan="2" align="left"> {!pos_reorder_info!}<br>
                  <center>
                    <input type='submit' name='submit' value='Order'>
                  </center>
                </td>
              </tr>
            {/if}
            <tr>
              <td class="admin_info">{!paymentstatus!}</td>
              <td class="subtitle">
                {if $shop_order.order_payment_status eq "none"}
                  <font color="#FF0000">{!notpaid!}</font>
                {elseif $shop_order.order_payment_status eq "payed"}
                  <font color='#00CC00'>{!paid!}</font>
                {/if}
              </td>
            </tr>
            <tr>
              <td class="admin_info">{!shipmentstatus!}</td>
              <td class="subtitle">
                {if $shop_order.order_shipment_status eq "none"}
                  <font color="#FF0000">{!notsent!}</font>
                {elseif $shop_order.order_shipment_status eq "send"}
                  <font color='#00CC00'>{!sent!}</font>
                {/if}
              </td>
            </tr>
          </table>
        </td>
        <td width="50%" valign="top" align='right'>
          <table width="99%" border=0>
            <tr>
              <td class="title" colspan=2 valign="top">{!pers_info!}</td>
            </tr>
            <tr>
              <td class="admin_info" valign="top">{!user_firstname!}</td>
              <td class="sub_title" valign="top">{$user_order.user_firstname}</td>
            </tr>
            <tr>
              <td class="admin_info" valign="top">{!user_lastname!}</td>
              <td class="sub_title" valign="top">{$user_order.user_lastname}</td>
            </tr>
            <tr>
              <td class="admin_info" valign="top">{!user_Address!} </td>
              <td class="sub_title" valign="top">{$user_order.user_address}</td>
            </tr>
            <tr>
              <td class="admin_info" valign="top">{!user_Address1!}</td>
              <td class="sub_title" valign="top">{$user_order.user_address1}</td>
            </tr>
            <tr>
              <td class="admin_info" valign="top">{!user_zip!}</td>
              <td class="sub_title" valign="top">{$user_order.user_zip}</td>
            </tr>
            <tr>
              <td class="admin_info" valign="top">{!user_city!}</td>
              <td class="sub_title" valign="top">{$user_order.user_city}</td>
            </tr>
            <tr>
              <td class="admin_info" valign="top">{!user_state!}</td>
              <td class="sub_title" valign="top">{$user_order.user_state}</td>
            </tr>
            <tr>
              <td class="admin_info" valign="top">{!user_country!}</td>
              <td class="sub_title" valign="top">{include file="countries.tpl" code=$user_order.user_country}</td>
            </tr>
            <tr>
              <td class="admin_info" valign="top">{!user_phone!}</td>
              <td class="sub_title" valign="top">{$user_order.user_phone}</td>
            </tr>
            <tr>
              <td class="admin_info" valign="top">{!user_email!}</td>
              <td class="sub_title" valign="top">{$user_order.user_email}</td>
            </tr>
          </table>
        </td>
      </tr>
    {/order->order_list}
    <tr>
      <td colspan="2">
        <table width='100%' bgcolor="lightgrey" border=0>
          <tr>
            <td width='33%' align="left"><a href="view.php">{!pos_goback!}</a></td>
            <td width='34%' align="center"> &nbsp;
              {if $status eq "payed"}
                <a href='javascript:if(confirm("{!pos_processorder!}")){literal}{location.href="view.php?order_id={/literal}{$shop_order.order_id}{literal}&action=change_status";}{/literal}'>
                  {!pos_clicktoproc!}
                </a>
              {elseif $status eq "pros" }
                <a href='javascript:if(confirm("{!pos_ticketready!}")){literal}{location.href="view.php?process=processed&order_id={/literal}{$shop_order.order_id}{literal}&action=send";}{/literal}'>
                  {!pos_clicktosent!}
                </a>
             {/if}
              &nbsp;
            </td>
            <td width='33%' align="right">
              <a href="view.php?order_id={$next_order_id}">{!pos_nextorder!}
                </a>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <table width='100%' cellspacing='1' cellpadding='4' border=0>
          <tr>
            <td class='title' colspan='9'>{!tickets!}<br></td>
          </tr>   
          <tr>
            <td class='subtitle'>{!id!}</td>
            <td class='subtitle'>{!event!}</td>
            <td class='subtitle'>{!event_date!}</td>
            <td class='subtitle'>{!category!}</td>
            <td class='subtitle'>{!zone!}</td>
            <td class='subtitle'>{!seat!}</td>
            <td class='subtitle'>{!discount!}</td>
            <td class='subtitle'>{!price!}</td>
            <td class='subtitle'>&nbsp;</td>
          </tr>
          {order->tickets order_id=$shop_order.order_id}
            {counter assign='row' print=false}
            <input type='hidden' name='place[]' value='{$shop_ticket.seat_id}'/>
            <tr class='admin_list_row_{$row%2}'>
              <td class='admin_info'>{$shop_ticket.seat_id}</td>
              <td class='admin_info'>{$shop_ticket.event_name}</td>
              <td class='admin_info'><b>{$shop_ticket.event_date}</b></td>
              <td class='admin_info'>{$shop_ticket.category_name}</td>
              <td class='admin_info'>{$shop_ticket.pmz_name}</td>
              <td class='admin_info'>
                {if not $shop_ticket.category_numbering or $shop_ticket.category_numbering eq "both"}
                  {$shop_ticket.seat_row_nr}  -  {$shop_ticket.seat_nr}
                {elseif $shop_ticket.category_numbering eq "rows"}
                  {!row!}{$shop_ticket.seat_row_nr}
                {else}
                   ---
                {/if}
              </td>
              <td class='admin_info'>{$shop_ticket.discount_name}</td>
              <td class='admin_info' align='right'>{$shop_ticket.seat_price}</td>
              <td class='admin_info' align='center'>
                <a href='javascript:if(confirm("{!cancel_ticket!} {$shop_ticket.seat_id}?")){literal}{location.href="view.php?action=cancel_ticket&order_id={/literal}{$shop_ticket.seat_order_id}&ticket_id={$shop_ticket.seat_id}{literal}";}{/literal}'>
                  <img border='0' src='images/trash.png' />
                </a>
              </td>
            </tr>
          {/order->tickets}
        </table>
        <br />
      </td>
    </tr>
    <tr>
      <form name='f' action='view.php' method='post'>
        <input type="hidden" name="action" value="update_note" />
        <input type="hidden" name="order_id" value="{$shop_order.order_id}" />
        <td valign='top'>{!pos_enternote!}
        </td>
        <td>
          <textarea name="note" cols="40" rows="8" wrap="VIRTUAL">{$shop_order.order_note}</textarea>
          <br />
          <input type="submit" value="Save Note" />
        </td>
      </form>
    </tr>
  </table>
</form>
<br />