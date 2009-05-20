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
{include file="header.tpl" name=!shopping_cart! header=!Handling_cont_mess!}

    {if $smarty.session.new_member eq true}
      {include file="just_registred.tpl"}
    {/if}

		{if $order_error}
      <div class='error'>{$order_error}</div><br>
		{/if}
    <br> <br>
   Location: {gui->selection name='venue' options='~All location|2009~Olumpic stadion|2011~Demolocation' nolabel=true} Dates:
        {gui->selection name='dates' options='~All dates|020090801~01.08.2009|20090807~07.08.2009|20090901~01.09.2009|20091101~01.11.2008' nolabel=true}

{include file="cart_content.tpl" check_out="on" cart_show_always="on"}
    <tr>
      <td>
          &nbsp;
      </td>
    </tr>
    <tr>
      <td class='view_cart_title' valign='top'  bgcolor="#ffffff">
          {gui->selection name='events' options='2009~Aida - Olumpic stadion - 01.08.2009 |2011~Aida - Demolocation - 7.08.2009' nolabel=true}<br>
      </td>
      <td class='view_cart_title' valign='top'  bgcolor="#ffffff">
          {gui->selection name='catagory' options='01.00~Cat1 - normal - € 108.00|01.02~Cat1 - Childs € 75,99' nolabel=true}
      </td>
      <td class='view_cart_title'  valign='top'  bgcolor="#ffffff">
          <select name='qty[{$shop_category.category_id}][]' >
            <option value='0' selected > 0 </option>
            {section name="myLoop" start=1 loop=15}
              <option value='{$smarty.section.myLoop.index}' > {$smarty.section.myLoop.index} </option>
            {/section}
          </select>
      </td>
      <td class='view_cart_title' valign='top'  bgcolor="#ffffff">
        <button value='add'>add</button>
      </td>
    </tr>
  </table>

{assign var=total value=$cart->total_price_f()}
<br> <hr> <br>
  {if !$update->is_demo()}
     	<form method='post' name='handling' onsubmit='this.submit.disabled=true;return true;'>
        {ShowFormToken name='OrderHandling'}
        <input type='hidden' name='action' value='confirm' />
  {/if}
<table cellpadding="0" cellspacing='0' border='0' width='100%'>
  <tr>
  	<td width="50%" valign="top" align="left">
			<table width='100%' border='1' cellspacing='1' cellpadding='5' align='left' style='padding-left:50px;'>
				{include file="user_form.tpl"}
				{*<tr>
					<td class='user_item'>{!without_fee!}</td>
					<td  class='user_value'><input type='checkbox' class='checkbox' name='no_fee' value='1'></td>
				</tr>
				<tr>
					<td class='user_item'>{!pos_freeticket!}s</td>
					<td  class='user_value'><input type='checkbox' class='checkbox' name='no_cost' value='1'></td>
				</tr>
				<tr>
					<td colspan='2' align='center'>
		  			<input type='hidden' name='handling' value='{$smarty.post.handling}'>
		  			<input type='hidden' name='action' value='submit_info'>
		  			<input type='submit' name='submit_info' value='{#continue#}'>
					</td>
		  		</tr> *}
			</table>
  	</td>
  	<td >
  	  &nbsp;
  	</td>
  	<td valign='top' align="right">
    	  <table border=1 width='90%' cellpadding="5" bgcolor='white'>
      		<tr>
      		  <td colspan='3' class='title' align='left'>{!handlings!}</td>
      		</tr>
      		{assign var=min_date value=$cart->min_date_f()}
      		{handling www='on' event_date=$min_date }
        		<tr class="{cycle name='payments' values='TblHigher,TblLower'}">
        		  <td class='payment_form'>
          		  <input checked="checked" type='radio' id='{$shop_handling.handling_id}_check' class='checkbox_dark' name='handling_id' value='{$shop_handling.handling_id}'>
        		  </td>
        		  <td class='payment_form'>
        		  	<label for='{$shop_handling.handling_id}_check'>
          		  	{!payment!}: {eval var=$shop_handling.handling_text_payment}<br>
          		  	{!shipment!}: {eval var=$shop_handling.handling_text_shipment}
        		  	</label>
        		  </td>
        		  <td class='payment_form' align='right'>
           			{assign var=fee value="`$total*$shop_handling.handling_fee_percent/100.00+$shop_handling.handling_fee_fix`"}
           			{if  $fee}
                 + {gui->valuta value=$fee|string_format:"%.2f"}
                {/if}&nbsp;
        		  </td>
        		</tr>
          {/handling}
      		{if $update_view.currentres}
        		<tr class="{cycle values='TblHigher,TblLower'}">
        			<td colspan="3">
         			  {*$update_view.maxres*}
         			  {!limit!}
        			</td>
        		</tr>
      		{/if}
    		</table>

  	</td>
  </tr>
</table>
    		<br >
          	    <input type='submit' name='submit' value='{!order_it!}'>
  {if !$update->is_demo()}
  		</form>
  {else}
     <div class='error'><br> For safety issues we have disabled the order button. </div>
  {/if}
  		{* update->view event_date=$min_date user=user->user_id *}
  		{if $update_view.can_reserve }
      {if !$update->is_demo()}
        <form action='' method='post' name='handling' onsubmit='this.submit.disabled=true;return true;'>
        <input type='hidden' name='action' value='reserve'>
        {ShowFormToken name='ReservHandling'}
        {/if}
    			<input type='submit' name='submit_reserve' value='{!reserve!}'>
  {if !$update->is_demo()}
    		</form>
  {/if}
  		{/if}
                 <br> <br>
{include file="footer.tpl"}