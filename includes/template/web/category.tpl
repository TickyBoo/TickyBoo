{*                  %%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2010 Christopher Jenkins, Niels, Lou. All rights reserved.
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
{assign var="category_id" value=$smarty.request.category_id}

{category category_id=$category_id event='on' placemap='on'}

  {if $shop_category.category_numbering neq 'none'}
    {include file="header.tpl" name=!select_seat!}
    <form name='f' action='index.php' method='post'>
      {ShowFormToken name='categorys'}
      <input type='hidden' name='category_id' value='{$shop_category.category_id}'>
      <input type='hidden' name='event_id' value='{$shop_category.category_event_id}'>
      <input type='hidden' name='action' value='addtocart'>
      <table class='pm_info'>
        <tr><td class='title' align='center'>{$shop_category.event_name}</td></tr>
        <tr>
          <td class='title' align='center'>
            {$shop_category.event_date|date_format:!date_format!} - {$shop_category.event_time|date_format:!time_format!}  {* "%A %e %B %Y" *}
            {$shop_category.pm_name} - {$shop_category.category_name} ({valuta value=$shop_category.category_price})
          </td>
        </tr>
        {if $shop_category.category_numbering neq 'none'}
          <tr>
            <td  align='center'>
         	    {!select_seat!}
              {!click_on_reserve!}
            </td>
          </tr>
        {/if}
        {if $shop_category.event_order_limit}
          <tr>
            <td  align='center'>
              {!order_limit!}
              {$shop_category.event_order_limit}
            </td>
          </tr>
        {/if}

        {if $shop_category.category_numbering eq 'none'}
          </table><br />
          <center>
            <table border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td class='event_data'>
                  {!number_seats!}
                </td>
                <td class='title'>
                  <input type='text' name='place' size='4' maxlength='4' align='right' />
                  <input type='hidden' name='numbering' value='none' />
                </td>
              </tr>
            </table>
          </center>
        {else}
            {if $shop_category.category_numbering eq "rows"}
              <tr>
                <td class='choice_info' align='center'>
                  <b>{!only_rows_numbered!}</b>
                </td>
              </tr>
            {/if}
          </table>
<style type="text/css">
{literal}
.seatmapimage {
   width: 16px;
   height:16px;
}
</style>
{/literal}
          <div style='overflow: auto; height: 350px; width:595px; border: 1px solid #DDDDDD;background-color: #fcfcfc' align='center' valign='middle'>
            {placemap  category=$shop_category}
          </div>
          <center>{!placemap_image_explanation!}</center>
          {literal}
          <script type="text/javascript">
            jQuery(document).ready(function(){
              jQuery(".pm_free").mouseleave(function(e){
                if(e.shiftKey){
                  jQuery('img.pm_check',this).attr('checked', true);
                }
                if(e.ctrlKey){
                  jQuery('img.pm_check',this).attr('checked', false);
                }

              });
            });
          </script>
          {/literal}
        {/if}
      <br />
      <center>
         <input type='submit' name='submit' value='{!reserve!}' />
  	  </center>
    </form>
  {else}
    {include file="event.tpl" event_id=$smarty.request.event_id}
  {/if}
{/category}
