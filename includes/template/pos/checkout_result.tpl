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
<h2>
{if $pm_return.approved}
  {!pay_accept! }
{else}
  {!pay_refused! }
{/if}
</h2>
<table class="table_midtone" width='400'>
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