{*                  %%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2009 Christopher Jenkins, Niels, Lou. All rights reserved.
 *
 * Original Design:
 *	phpMyTicket - ticket reservation system
 * 	Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of FusionTicket.
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
 * Any links or references to Fusion Ticket must be left in under our licensing agreement.
 *
 * By USING this file you are agreeing to the above terms of use. REMOVING this licence does NOT
 * remove your obligation to the terms of use.
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact help@fusionticket.com if any conditions of this licencing isn't
 * clear to you.
 *}
{if $pm_return.approved}
  {include file="header.tpl" name=!pay_accept! }
{else}
  {include file="header.tpl" name=!pay_refused! }
{/if}
<table class="table_midtone">
  <tr>
    <td>
        {if !$pm_return.approved}
          {!pay_reg!}!
        {/if}
        <br>
		    {!order_id!} <b>{$shop_order.order_id}</b><br>
		    {if $pm_return.transaction_id}
          {!trx_id!}   <b>{$pm_return.transaction_id}</b><br>
        {/if}
        <br> <br>
        {if !$pm_return.approved}
          <div class='error'>
  	    {else}
          <div>
        {/if}
        {if $pm_return.response}
	        {eval var=$pm_return.response}
        {/if}
			  </div>

    </td>
  </tr>
</table>
{include file="footer.tpl"}