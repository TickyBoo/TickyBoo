{*
%%%copyright%%%
 * Fusion Ticket System
 * Based on phpMyTicket - ticket reservation system
 * Orginal Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * Copyright (C) 2007-2008 Christopher Jenkins
 *
 * This file is part of fusion ticket, it may be modified or used in any senario but
 * not as your own. This file is free and open source any distrubution of your own
 * will have to apply to the GNU rules as well.
 *
 * This file may be distributed and/or modified under the terms of the
 * "GNU General Public License" version 2 as published by the Free
 * Software Foundation and appearing in the file LICENSE included in
 * the packaging of this file.
 *
 *
 * This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
 * THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE.
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 *}
{if $smarty.post.item_number gt 0}
	{order->order_list order_id=$smarty.post.item_number user=on limit=1}
	{if $shop_order.order_payment_status eq 'none'}
		{handling handling_id=$shop_order.order_handling_id pm_exec="on_notify"}
		{/handling}
	{/if}
	{/order->order_list}
{/if}