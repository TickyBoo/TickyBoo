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

{if $cart_error}
  {include file="header.tpl" name=!shopping_cart!}
  <div align='center' class='error'>{$cart_error}</div>
{else}
  {include file="header.tpl" name=!shopping_cart! header=!cart_cont_mess!}
  {include file="cart_content.tpl" }
{/if}
<br>
<table class="table_midtone" width='100%'>
  <tr>
    <td width="50%" align="left">
      <form method='post' action="index.php">
        {if $event_id}
           <input type='hidden' name='event_id' value='{$event_id}' />
        {/if}
        <input name="go_home" value="{!order_more_tickets!}" type="submit">
      </form>
    </td>
    <td align="right">
      {if $cart->can_checkout_f()}
        <form action="checkout.php" method='post' >
          <input name="go_pay" value="{!checkout!}" type="submit">
        </form>
      {/if}
    </td>
  </tr>
</table>