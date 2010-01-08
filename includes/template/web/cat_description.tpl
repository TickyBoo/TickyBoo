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
{if $shop_event.event_pm_id}
  <br>
  <table border=0 class='table_midtone'>
    <tr>
      <td colspan='3' class="title2">
        {!cat_description!}
      </td>
    </tr>
    {category event_id=$shop_event.event_id stats="on"}
      <tr class="{cycle name='events' values="TblHigher,TblLower"}">
        <td><b>{$shop_category.category_name}</b></td>
        <td align='right'>
          {valuta value=$shop_category.category_price|string_format:"%.2f"}
        </td>
        <td align='left' width='25%'>
          {if $shop_category.cs_free>0}
            {if $shop_category.cs_free/$shop_category.cs_total ge 0.2}
              <font>{!tickets_available!} {$shop_category.cs_free}</font>
            {else}
              <font color='Yellow'>{!tickets_available!} {$shop_category.cs_free}</font>
            {/if}
          {else}
            <span class='error'>{!category_sold!}</span>
          {/if}
        </td>
      </tr>
      {if $shop_category.cs_free>0}
        {assign var=js_array value="$js_array unnum_cats[unnum_cats.length]='`$shop_category.category_numbering`';"}
        {capture assign=opt}
          <option value='{$shop_category.category_id}' {if $shop_category.category_id eq $smarty.request.category_id}selected{/if} />
             {$shop_category.category_name} - {valuta value=$shop_category.category_price|string_format:"%.2f"}
          </option>
        {/capture}
        {assign var=opt_array value="$opt_array $opt"}
      {/if}
    {/category}
    <tr>
      <td colspan='3'>
        {discount event_id=$shop_event.event_id cat_price=$shop_category.category_price}
          &nbsp;
          <span class='note'>
            {!Discount_for!} {$shop_discount.discount_name}:
              {valuta value=$shop_discount.discount_price|string_format:"%.2f"}
          </span><br>
        {/discount}
      </td>
    </tr>
     <tr>
      <td colspan='3' align='left' class='note'>
        {!prices_in!} {$organizer_currency}
      </td>
    </tr>
  </table>
  <br>

  <script><!--
  var unnum_cats=new Array;
  {$js_array}

  {literal}

  function getElement(id){
       if(document.all) {return document.all(id);}
       if(document.getElementById) {return document.getElementById(id);}
  }

  function setQtyShown(){
        if(cat_select_e=getElement('cat_select')){
           if(qty_e=getElement('qqq')){
             if(unnum_cats[cat_select_e.selectedIndex]=='none'){
               qty_e.style.display='block';
             }else{
               qty_e.style.display='none';
             }
           }
         }
       }
   -->
  </script>
  {/literal}
  {if $user->mode() eq '-1' and !$user->logged}
    	<table class='table_dark' cellpadding='5' bgcolor='white' width='100%'>
      	<tr>
    			<td class='TblLower'>
          			{!Please_login!}
          </td>
			</tr>
		</table> <br/>   <br/>
  {elseif $shop_event.event_date ge $smarty.now|date_format:"%Y-%m-%d"}
    <form name='catselect' method='post' action='index.php'>
      {ShowFormToken}
      <table  class='table_midtone'>
        <tr>
          <td class='title2' colspan='3' >
            {!select_category!}
          </td>
        </tr>
        <tr>

          {if $shop_event.pm_image}
            <td colspan='3'>
              <img src="files/{$shop_event.pm_image}"  border='0'  usemap="#ort_map">
              <map name="ort_map">
                {category event_id=$shop_event.event_id stats="on"}
                  {if $shop_category.cs_free gt 0}
                    <area href="index.php?category_id={$shop_category.category_id}&event_id={$smarty.get.event_id}" {$shop_category.category_data} />
                  {/if}
                {/category}
              </map>
            </td>
          {else}
            <td width='50%' align='left'>
              <select name='category_id' onchange='setQtyShown()' id='cat_select' style="float:right;" class="select">
                 {$opt_array}
              </select>
            </td>
            <td  align='left'>
              <div id='qqq'  align='left' style='font-size:9px; float:left;'>x
{*			  	      <input style="float:none;" type='text' name='qty' size='4' maxlength='2' />*}
                {assign var=limit value=14}
                {if $shop_event.event_order_limit>0}
                   {assign var=limit value=$shop_event.event_order_limit}
                {/if}

                <select style="float:none;"  name='qty' >
                  {section name="myLoop" start=0 loop=$limit+1}
                    <option value='{$smarty.section.myLoop.index}' > {$smarty.section.myLoop.index} </option>
                  {/section}
                </select>
                {if $shop_event.event_order_limit>0}
                   ({!order_limit!} {$shop_event.event_order_limit})
                {/if}
              </div>
            </td>
            <td  align='left'>
              <input type='submit' name='submit_cat' value='{!continue!}'>
              <input type='hidden' name='event_id' value='{$smarty.request.event_id}'>
            </td>
          {/if}
        </tr>
      </table>
    </form><br>
    <script><!--
    setQtyShown();
    --></script>
  {else}
    	<table class='table_dark' cellpadding='5' bgcolor='white' width='100%'>
      	<tr>
    			<td class='TblLower'>
          			{!old_event!}
          </td>
			</tr>
		</table> <br/> <br/>

  {/if}
{/if}