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
 *}{strip}
{include file='order.tpl' nofooter=true}
  <div id="checkout_result" title='
{if $pm_return.approved}
  {!pay_accept! }
{else}
  {!pay_refused! }
{/if}
  '>
{/strip}
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
          {if $pm_return.approved}
            <table width='100%'>
              <tr> <td>
                <a href='checkout.php?action=print&{$order->EncodeSecureCode($order->obj)}&mode=2' target='_blank'>{!printinvoice!}</a>
              </td><td align='right'>
                <a href='checkout.php?action=print&{$order->EncodeSecureCode($order->obj)}&mode=1' target='_blank'>{!printtickets!}</a>
              </td></tr>
            </table>
            <br>
          {/if}
        </td>
      </tr>
    </table>

  </div>
  <script type="text/javascript">
  {literal}
  	$(document).ready(function(){
    	$("#checkout_result").dialog({
    		bgiframe: false,
    		autoOpen: true,
    		height: 'auto',
    		width: 'auto',
    		modal: true,
    		buttons: {
          'Close': function() {
	    	   	$(this).dialog('close');
          }
	  		},
    	  close: function(event, ui) {
          {/literal}window.location = '{$_SHOP_root}index.php';{literal}
        }
    	});
  	});
  {/literal}
  </script>
  {include file="footer.tpl"}





