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

{if not $cart->can_checkout_f()}
  {include file="cart_view.tpl"}
{elseif not $user->logged}
  {if $smarty.get.action eq 'login'}
    {user->login username=$smarty.post.username password=$smarty.post.password'}
  {elseif $smarty.get.action eq 'register'}
    {if $smarty.post.submit_info}
      {user->guest data=$smarty.post}
    {elseif $smarty.post.submit_register}
      {user->member data=$smarty.post}
    {/if}
  {/if}
  {if not $user->logged}
    {include file="user.tpl"}
  {else}
    {include file="order_preview.tpl"}
  {/if}
{else}  
    {if $smarty.post.action eq 'pay' and $smarty.post.payment}
      {order payment=$smarty.post.payment}
      {if $order_success}
        {include file="order_confirm.tpl"}
	{cart->destroy}
	{if $user->is_guest}{user->logout}{/if}
      {else}
        <div class='error'>{$order_error}</div> 	
      {/if}	
    {else}
        {include file="order_preview.tpl"}
    {/if}
{/if}
{include file="footer.tpl"}
