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
{capture assign="tabview"}{!pos_currenttickets!}|{!pos_paidlist!}|{!pos_unsentlist!}{/capture} 
{gui->Tabbar menu=$tabview}



{if $TabBarid == 0} {* eq "paid" *}
  {if $smarty.request.order_id}      
    {if $smarty.post.action eq "update_note"}
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
    {include file="process_view.tpl" status="payed" not_status="pros"}    
  {else}
    {include file="process_list.tpl" status="payed" not_status="pros" not_sent=true}
  {/if}
{elseif $TabBarid == 1} {*  eq "processed" *}
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
    {include file="process_view.tpl" status="pros" not_status="send"}    
  {else}
    {include file="process_list.tpl" status="pros" not_status="send"}
  {/if}
{elseif $TabBarid == 2} {*  eq "sent" *}
  {if $smarty.request.order_id}
    {if $smarty.post.action eq "update_note"}
      {order->save_order_note order_id=$smarty.post.order_id note=$smarty.post.note}
      <div class='success'>
        {$order_note}
      </div>
    {/if}
    {include file="process_view.tpl" status="sent"}    
  {else}
    {include file="process_list.tpl" status="send"}
  {/if}
{/if}
{include file="footer.tpl"}
