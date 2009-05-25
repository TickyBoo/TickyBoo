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
 *}{strip}{* include file="header.tpl" *}
 
{if $smarty.request.action eq "addtocart"}
  {assign var='last_item' value=$cart->add_item_f($smarty.post.event_id,$smarty.post.category_id,$smarty.post.place,'mode_web')}
  {if $last_item}
    {include file="discount.tpl"}
  {else}
    {include file="category.tpl"}
  {/if}

{elseif $smarty.request.action eq "adddiscount"}
  {cart->set_discounts event_id=$smarty.post.event_id 
    category_id=$smarty.post.category_id item_id=$smarty.post.item_id
    discounts=$smarty.post.discount }
  {include file="cart_view.tpl"}
{elseif $smarty.get.action eq 'activate'}
  {include file="activate.tpl"}
{elseif $smarty.get.action eq "remove"}
  {$cart->remove_item_f($smarty.get.event_id,$smarty.get.cat_id,$smarty.get.item)}
  {include file="cart_view.tpl"}

{elseif $smarty.request.action eq "view_cart"}
  {include file="cart_view.tpl"}
  
{elseif $smarty.request.category_id}
  {if $smarty.post.qty}
    {assign var='last_item' value=$cart->add_item_f($smarty.request.event_id,$smarty.request.category_id,$smarty.request.qty)}
    {if $last_item}
      {include file="discount.tpl"}
    {else}
      {include file="event.tpl" event_id=$smarty.request.event_id}
    {/if}
  {else} 
    {include file="category.tpl"}
  {/if}

{elseif $smarty.request.event_id}
  {include file="event.tpl"  event_id=$smarty.get.event_id}

{elseif $smarty.request.event_group_id}
  {include file="event_group.tpl"}

{elseif $smarty.request.event_groups}
  {include file="event_groups.tpl"}

{elseif $smarty.request.event_type}
  {include file="event_type.tpl"}

{elseif $smarty.request.action eq 'login'}
	{user->login username=$smarty.post.username password=$smarty.post.password uri=$smarty.post.uri}
	{if $login_error.error AND not $user->logged}
  		{include file="loginerror.tpl"}
  {else}
    {include file="last_event_list.tpl"}
	{/if}

{elseif $smarty.request.register_user}
  {if $smarty.request.action eq 'login'}
    {user->login username=$smarty.post.username password=$smarty.post.password}
  {elseif $smarty.request.action eq 'register'}
    {if $smarty.post.submit_info}
      {user->guest data=$smarty.post}
    {elseif $smarty.post.submit_register}
      {user->member data=$smarty.post}
    {/if}
    {assign var='user_data' value=$smarty.post}
  {/if}
  {if not $user->logged}
      {include file="inscription.tpl"}
  {else}
    {include file="last_event_list.tpl"}
  {/if}  

{elseif $smarty.request.personal_page}
  {if $user->logged}
    {include file="header.tpl"}
  	{if $smarty.request.personal_page eq 'details'}
	    {if $smarty.request.action eq 'update'}
      	{if $smarty.post.submit_update}
      		{user->update_member data=$smarty.post}
        {/if}
        {assign var='user_data' value=$smarty.post}
        {if $user->logged}
        	{include file="user_update.tpl"}
        {else}
			{include file="personal_page.tpl"}
        {/if}
      {else}
	  	{include file="user_update.tpl"}
      {/if}
    {elseif $smarty.request.personal_page eq 'orders'}
    	{if $smarty.request.action eq 'order_res'}
    	  {order->res_to_order order_id=$smarty.post.order_id handling_id=$smarty.post.handling}
  		  {if $order_success}
  			  {include file="personal_orders.tpl"}
  		  {else} 
		 	    <div class='error'>Error</div>
  		  {/if}
  		{elseif $smarty.request.action eq 'reorder'}
  			{include file="personal_reorder.tpl"}
  		{elseif $smarty.get.id}
    		{include file="personal_order.tpl"}
    	{else}
    		{include file="personal_orders.tpl"}
	  	{/if}
    {else}
		  {include file="personal_page.tpl"}
    {/if}
  {else}
    {include file="last_event_list.tpl"}
  {/if}
{else}
  {include file="last_event_list.tpl"}
{/if}

<!-- End of massive Elseif -->
{if !$nofooter}
  {include file="footer.tpl"}
{/if}
{/strip}