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
{include file="header.tpl"}
<br>
{capture assign="tabview"}{!pos_reservedlist!}|{!pos_unpaidlist!}|{!pos_unsentlist!}|{!pos_currenttickets!}{/capture} 
{gui->Tabbar menu=$tabview}



{if $TabBarid == 0} {* eq "reserved" *}
  {if $smarty.request.order_id}      
    {if $smarty.post.action eq "update_note"} {*Should be upgraded to an ajax call. that way just get a status back.*}
      {order->save_order_note order_id=$smarty.post.order_id note=$smarty.post.note}
      <div class='success'>
        {$order_note}
      </div>
    {elseif $smarty.get.action eq 'change_status'}
      {if $order->set_status_f($smarty.get.order_id,'pros') }
        <div class='success'>
          {!order_status_changed!}
        </div>
      {/if}
    {/if}
    {include file="process_view.tpl" status="res"}    
  {else}
    {include file="process_list.tpl" status="res"}
  {/if}
{elseif $TabBarid == 1} {*  eq "unpaid" *}
  {if $smarty.request.order_id}
    {if $smarty.get.action eq 'send'}
      {$order->set_status_f($smarty.get.order_id,'ord')}
      {$order->set_send_f($smarty.get.order_id) }
      <div class='success'>
        {!order_status_changed!}
      </div>
    {elseif $smarty.post.action eq "update_note"}
      {order->save_order_note order_id=$smarty.post.order_id note=$smarty.post.note}
      <div class='success'>
        {$order_note}
      </div>
    {/if}
    {include file="process_view.tpl" status="ord" not_status="payed" place='pos'}    
  {else}
    {include file="process_list.tpl" status="ord" not_status="payed" place='pos'}
  {/if}
{elseif $TabBarid == 2} {*  eq "unsent" *}
  {if $smarty.request.order_id}
    {if $smarty.post.action eq "update_note"}
      {order->save_order_note order_id=$smarty.post.order_id note=$smarty.post.note}
      <div class='success'>
        {$order_note}
      </div>
    {/if}
    {include file="process_view.tpl" not_sent=true not_status="send" status="payed" hand_shipment='post,sp'}    
  {else}
    {include file="process_list.tpl" not_sent=true not_status="send" status="payed" hand_shipment='post,sp'}
  {/if}
{elseif $TabBarid == 3} {*  eq "pos owned orders" *}
  {if $smarty.request.order_id}
    {if $smarty.post.action eq "update_note"}
      {order->save_order_note order_id=$smarty.post.order_id note=$smarty.post.note}
      <div class='success'>
        {$order_note}
      </div>
    {/if}
    {include file="process_view.tpl" place='pos'}
  {else}
    {include file="process_list.tpl" place='pos'}
  {/if}
{/if}
{include file="footer.tpl"}
