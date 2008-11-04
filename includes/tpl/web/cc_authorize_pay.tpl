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

{if $shop_handling.pm_return.approved}
  {include file="header.tpl",name=!pay_accept!}
{else}
  {include file="header.tpl",name=!pay_refused!}
{/if}
{if $smarty.post.order_id gt 0}
  {order->order_list order_id=$smarty.post.order_id user=on length=1}
    {if $shop_order.order_payment_status eq 'none'}
      {handling handling_id=$shop_order.order_handling_id pm_exec="on_cc_submit"}
        <table class="table_midtone">
          {if $shop_handling.pm_return.approved}
         	  <tr>
              <td>
        		    {!order_id!} <b>{$shop_order.order_id}</b><br>
                {!trx_id!}
        		    <b>{$shop_handling.pm_return.details.transaction_id}</b>
              </td>
            </tr>
        	{else}
        		<tr>
              <td>
                <div class='error'>
        			    ( {$shop_handling.pm_return.details.response_code} /
        			      {$shop_handling.pm_return.details.response_subcode} /
        			      {$shop_handling.pm_return.details.response_reason_code} )
        			      {$shop_handling.pm_return.details.response_reason_text}
        			  </div>
              </td>
            </tr>
            <tr>
              <td>
          			{eval var=$shop_handling.handling_html_template}
              </td>
            </tr>
         	{/if}
        </table>
      {/handling}
    {/if}
  {/order->order_list}
{/if}
{include file="footer.tpl"}