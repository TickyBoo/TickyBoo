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

{if not $cart->can_checkout_f()}
  {include file="cart_view.tpl"}
{elseif !$user->logged}
  {if $smarty.get.action eq 'login'}
    {user->login username=$smarty.post.username password=$smarty.post.password uri=$smarty.post.uri}
  {elseif $smarty.get.action eq 'register'}
    {if $smarty.post.submit_info}
      {user->guest data=$smarty.post}
    {elseif $smarty.post.submit_register}
      {user->member data=$smarty.post}
    {/if}
    {assign var='user_data' value=$smarty.post}
  {/if}
  {if not $user->logged}
    {include file="user.tpl"}
  {else}
    {include file="order_preview.tpl"}
  {/if}
{else}
  {if $smarty.post.action eq 'order' and $smarty.post.handling}
    {order->make handling=$smarty.post.handling place="www"}
		{if $order_success}
			{include file="order_confirm.tpl"}
			{cart->destroy}
			{if $user->is_guest}{user->logout}{/if}
		{else}
      {include file="header.tpl" name=!shopping_cart! header='<div class='error'>$order_error</div>'}
		{/if}
	{elseif $smarty.post.action eq 'reserve' and $smarty.post.handling}
		{order->make handling=$smarty.post.handling place="www"}
		{if $order_success}
			{include file="order_confirm.tpl"}
			{cart->destroy}
			{if $user->is_guest}{user->logout}{/if}
		{else}
      {include file="header.tpl" name=!shopping_cart! header='<div class='error'>$order_error</div>'}
		{/if}
	{else}
  	{include file="order_preview.tpl"}
  {/if}
{/if}
{include file="footer.tpl"}