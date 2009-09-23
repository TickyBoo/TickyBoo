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
  {!pay_accept!}
{else}
  {!pay_refused!}
{/if}
  '>
{/strip}
	<table class="table_midtone" width='400'>
    	{if !$pm_return.approved}
    	<tr>
        	<td colspan=2>{!pay_reg!}!</td>
        </tr>
		{/if}
      	<tr>
	  		<td>{!order_id!}</td>
        	<td><b>{$shop_order.order_id}</b></td>
      	</tr>
      	{if $pm_return.transaction_id}
        <tr>
        	<td>{!trx_id!}</td>
          	<td><b>{$pm_return.transaction_id}</b><br/></td>
        </tr>
      	{/if}
      	{if $pm_return.response}
        <tr>
        	<td colspan=2 {if !$pm_return.approved}class='error'{/if}>
            	{eval var=$pm_return.response}
          	</td>
        </tr>
      	{/if}
      	
      	{if $pm_return.approved}
        <tr> 
        	<td>
            	<a href='checkout.php?action=print&{$order->EncodeSecureCode($order->obj)}&mode=2' target='_blank'>{!printinvoice!}</a>
          	</td>
  			<td align='right'>&nbsp;
  			
            {if $shop_order.order_handling->handling_shipment eq "sp"}
            	<a id='printticket' href='checkout.php?action=print&{$order->EncodeSecureCode($order->obj)}&mode=1' style='display:none;' target='_blank'>{!printtickets!}</a>
            {/if}
          	</td>
    	</tr>
      	{/if}
	</table>

  </div>
  <script type="text/javascript">
  var timerid = 0;
  var orderid = {$shop_order.order_id};
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
          if (timerid) {
            clearTimeout(timerid);
          }
          {/literal}window.location = '{$_SHOP_root}index.php';{literal}
        }
    	});
  	});
  {/literal}
  {if $shop_order.order_handling->handling_shipment eq "sp"}
    {literal}

      //The refresh orderpage, the ajax manager SHOULD ALLWAYS be used where possible.
      var checkpaint = function(){
        ajaxQManager.add({
          type:		"POST",
          url:		"ajax.php?x=canprint",
          dataType:	"json",
          data:		{"pos":true,"action":"Canprint",orderid:orderid},
          success:function(data, status){
            if(data.status){
              $('#printticket').show();
            } else {
              	timerid = setTimeout('checkpaint()', 1000);
            }
          }	
        });
      } 
      checkpaint();       
    {/literal}
  {/if}
  </script>
  {include file="footer.tpl"}