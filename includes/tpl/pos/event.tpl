{*
%%%copyright%%%
 * phpMyTicket - ticket reservation system
 * Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of phpMyTicket.
 *
 * This file may be distributed and/or modified under the terms of the
 * "GNU General Public License" version 2 as published by the Free
 * Software Foundation and appearing in the file LICENSE included in
 * the packaging of this file.
 *
 * Licencees holding a valid "phpmyticket professional licence" version 1
 * may use this file in accordance with the "phpmyticket professional licence"
 * version 1 Agreement provided with the Software.
 *
 * This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
 * THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE.
 *
 * The "phpmyticket professional licence" version 1 is available at
 * http://www.phpmyticket.com/ and in the file
 * PROFESSIONAL_LICENCE included in the packaging of this file.
 * For pricing of this licence please contact us via e-mail to 
 * info@phpmyticket.com.
 * Further contact information is available at http://www.phpmyticket.com/
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@phpmyticket.com if any conditions of this licencing isn't 
 * clear to you.
 
 *}
 {literal}
 <script type="text/javascript">
 
 </script>
 {/literal}
 
{event event_id=$smarty.get.event_id ort='on'}

<table width='100%' border='0' cellpadding='5' class='event_details'>
	<tr>
		<td class='title'>
			<a class="link" href="index.php?event_id={$shop_event.event_id}"><h1>{$shop_event.event_name}</h1></a>  
			{if $shop_event.event_mp3}
				<a style='color:#996633;text-decoration:none;' href='{$shop_event.event_mp3}'>[<img src='images/audio-small.png' border='0' />]</a> 
			{/if}
		</td>
	</tr>
	<tr>
		<td class="help">
			{!pos_checkdate!}
		</td>
	</tr>
	<tr>
		<td  class='event_info_item' >  
			{$shop_event.event_date|date_format:"%a %e %b %Y"}  
			{$shop_event.ort_name} 
			{if $shop_event.ort_phone}
				<br /> 
				{!phone!}: {$shop_event.ort_phone}
			{/if}
			{if $shop_event.ort_url}
				<br />
    			{!homepage!}: <a class='event_url' href='{$shop_event.ort_url}'>{$shop_event.ort_url}</a>
			{/if}
		</td>
	</tr>
	<tr>
		<td  class='event_info_item' > 
    		{!doors_open!}: {$shop_event.event_open|date_format:" %Hh%M"}<br />
			{!event_start!}: {$shop_event.event_time|date_format:" %Hh%M"}
		</td>
	</tr>
	<tr>
		<td class='event_description_big'>{$shop_event.event_text}
		</td>
	</tr>
</table>
<br />

<table width='100%' border='0' cellpadding='5' class='cat_details'>
<tr>
<td colspan='5' class='title'>
    {!categories_and_prices!}
</td></tr>


{category event_id=$shop_event.event_id stats="on"}
  {counter assign="cat_num" print=false}

  {if ($cat_num-1) is div by 4}
    <tr><td class='cat_info_item'>
    {!tickets!}
</td>
    {capture assign=prices_row}
      <tr><td class='cat_info_value'>
    {!price!}
</td>
    {/capture} 
  {/if}
     
  <td class='cat_info_item'>{$shop_category.category_name}</td>
  {capture assign=price}
    <td class='cat_info_value'>
    {if $shop_category.cs_free>0}
      {$shop_category.category_price}&nbsp;&nbsp;
          {if $shop_category.cs_free/$shop_category.cs_total ge 0.2}
             <img src='images/green.png' /> {$shop_category.cs_free}/{$shop_category.cs_total}
          {else}
             <img src='images/orange.png' /> {$shop_category.cs_free}/{$shop_category.cs_total}
          {/if}
    {else}
      <img src='images/red.png' />    
      {!category_sold!}
    {/if}  
    </td>
  {/capture}   
  {assign var=prices_row value="$prices_row $price"}
  {if $cat_num is div by 4}
    </tr>
    {$prices_row}
    </tr>
  {/if}  
  

  {if $shop_category.cs_free>0}
    {assign var=js_array value="$js_array unnum_cats[unnum_cats.length]='`$shop_category.category_numbering`';"}
    {capture assign=opt}
      <option value='{$shop_category.category_id}'>
          {$shop_category.category_name} - 
					{$organizer_currency} 
					{$shop_category.category_price}
	  
      </option>
    {/capture}  
    {assign var=opt_array value="$opt_array $opt"}
  {/if}
{/category}

{if $cat_num is not div by 4}
    </tr>
    {$prices_row}
    </tr>
{/if}    
<tr>
<td colspan='5' align='right' class='note'>    
    {!prices_in!}&nbsp;{$organizer_currency}

</td></tr>
</table><br />

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

<form id="form-cat-select" class="form" name='catselect' method='get' action='index.php'>
	<table  class='cat_choice' cellpadding='5' width='100%' border='0'>
		<tr>
			<td class='title' colspan='3' >
		   		{!select_category!}
			</td>
		</tr>
		<tr>
			<td width='50%' align='right'>
				<select name='category_id' onchange='setQtyShown()' id='cat_select'>
   				{$opt_array}
				</select>
			</td>
			<td class='category_item' align='left'>
				<div id='qqq'  align='left'>x&nbsp;<input type='text' name='qty' size='4' maxlength='2' /></div>
			</td>
			<td  align='left' class='category_value'>
				<input type='submit' name='submit_cat' value='{!continue!}' />
			</td>
		</tr>
	</table>
	<input type='hidden' name='event_id' value='{$smarty.get.event_id}' />
</form>
<br />
<script>
<!-- 
  setQtyShown();
-->
</script>
{/event}
