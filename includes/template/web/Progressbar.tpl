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
 *}{literal}
<style type="text/css">
.pagination{
  background-color: #99d9ea;
  TEXT-ALIGN: center;    
}
.done{
  background-color: #42729a;
  color: #FFFFFF;
  TEXT-ALIGN: center;  
  border-left: 2px solid #5EA3DB;
}
.current{
  background-repeat: no-repeat;
  background-color: #BdC9D5;
   font-weight: bold;
   color: #000000;
}
.next{
  TEXT-ALIGN: center;
  border-right: 2px solid #9FE1F2;
}

</style> {/literal}
  <br>

  <table border="0" class="pagination" width="100%"  cellpadding="0" cellspacing="0" >
    <tr>
      {if $name==!shop! and $shop_event.event_pm_id}
        <td class='current'> Order </td>
        <td width='25'><img src='{$_SHOP_images}trans_12_11_r.png' height='20'></td>
        <td class='next'>Review Order</td>
        {if !$user->logged}
          <td class='next'>
            Log-in or Register
          </td>
        {/if}
        <td class='next'>Select Payment</td>
        <td class="next">Complete Order</td>          
      {elseif $name==!select_seat!}
        <td class='done'>Order </td>
        <td width='11'><img src='{$_SHOP_images}trans_12_11_b.png' width='11' height='20'></td>
        <td class='current'>Select seat </td>
        <td width='25'><img src='{$_SHOP_images}trans_12_11_r.png' height='20'></td>
        <td class='next'>Review Order</td>
        {if !$user->logged}
          <td class='next'>
            Log-in or Register
          </td>
        {/if}
        <td class='next'>Select Payment</td>
        <td class="next">Complete Order</td>          
      {elseif $name==!discounts!}
        <td class='done'>Order </td>
        <td width='11'><img src='{$_SHOP_images}trans_12_11_b.png' width='11' height='20'></td>
        <td class='current'>Select discounts</td>
        <td width='25'><img src='{$_SHOP_images}trans_12_11_r.png' height='20'></td>
        <td class='next'>Review Order</td>
        {if !$user->logged}
          <td class='next'>
            Log-in or Register
          </td>
        {/if}
        <td class='next'>Select Payment</td>
        <td class="next">Complete Order</td>       
      {elseif $name==!shopping_cart!}
        <td class='done'>Order </td>
        <td width='11'><img src='{$_SHOP_images}trans_12_11_b.png' width='11' height='20'></td>
        <td class='current'>Review Order </td>
        <td width='25'><img src='{$_SHOP_images}trans_12_11_r.png' height='20'></td>
        {if !$user->logged}
          <td class='next'>
            Log-in or Register
          </td>
        {/if}
        <td class='next'>Select Payment</td>
        <td class="next">Complete Order</td>       
      {elseif $name==!pers_info!}
        <td class='done'>Order </td>
        <td class='done'>Review Order </td>
        <td width='11'><img src='{$_SHOP_images}trans_12_11_b.png' width='11' height='20'></td>
        <td class='current'>Log-in or Register </td>
        <td width='25'><img src='{$_SHOP_images}trans_12_11_r.png' height='20'></td>
        <td class='next'>Select Payment</td>
        <td class="next">Complete Order</td>       
      {elseif $name==!shopping_cart_check_out!}
        <td class='done'>Order </td>
        <td class='done'>Review Order </td>
        <td class='done'>Log-in or Register</td>
        <td width='11'><img src='{$_SHOP_images}trans_12_11_b.png' width='11' height='20'></td>
        <td class='current'>Select Payment </td>
        <td width='25'><img src='{$_SHOP_images}trans_12_11_r.png' height='20'></td>
        <td class="next">Complete Order</td>
      {elseif $name==!order_reg!}
        <td class='done'>Order </td>
        <td class='done'>Review Order </td>
        <td class='done'>Log-in or Register</td>
        <td class='done'>Select Payment </td>
        <td width='11'><img src='{$_SHOP_images}trans_12_11_b.png' width='11' height='20'></td>
        <td class="current">Complete Order</td>
        <td width='25' ><img src='{$_SHOP_images}trans_12_11_r.png' height='20'></td>
      {elseif $name==!pay_accept! or $name==!pay_refused!}
        <td class='done'>Order </td>
        <td class='done'>Review Order </td>
        <td class='done'>Log-in or Register</td>
        <td class='done'>Select Payment </td>
        <td width='11'><img src='{$_SHOP_images}trans_12_11_b.png' height='20'></td>
        <td class="current">Complete Order</td>
      {/if}
    </tr>
  </table>