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
{*
* Old Paypal button creates issues with payments.. The new one is created in the recommended order by paypal them selfs.
* Need to keep uptodate, with paypal.
<form action='{$url}https://www.sandbox.paypal.com/cgi-bin/webscr' method='post'>
<input type='hidden' name='site_url' value='{$_SHOP_root}'/>
<input type='image' name='submit' src='http://images.paypal.com/images/x-click-but26.gif' border='0' alt="Make payments with PayPal, it's fast, free, and secure!">
<input type='hidden' name='cmd' value='_xclick' />
<input type='hidden' name='business' value='{$shop_handling.extra.pm_paypal_business}' />
<input type='hidden' name='item_name' value='Pepper Tickets - Order No. {$id}' />
<input type='hidden' name='item_number' value='{$id}' />
<input type='hidden' name='amount' value='{$price}' />
<input type='hidden' name='currency_code' value='GBP' />
<input type='hidden' name='return' value='{$_SHOP_root_secured}paypal_return.php' />
<input type='hidden' name='notify_url' value='{$_SHOP_root_secured}paypal_notify.php' />
<input type='hidden' name='cancel_return' value='{$_SHOP_root_secured}paypal_cancel.php' />
<input type='hidden' name='no_shipping' value='1' />
<input type='hidden' name='no_note' value='1' />
</form>
*}

<form name="PayPal" action="{$url}" method="post">
  <input type="hidden" name="cmd" value="_xclick">
  <input type="hidden" name="business" value="{$shop_handling.extra.pm_paypal_business}">
  <input type="hidden" name="item_name" value="Pepper Tickets - Order No. {$id}">
  <input type="hidden" name="item_number" value="{$id}">
  <input type="hidden" name="amount" value="{$price}">
  <input type="hidden" name="image_url" value="https://www.paypal.com/images/x-click-but23.gif">
  <input type="hidden" name="return" value="{$_SHOP_root_secured}paypal_return.php">
  <input type='hidden' name='notify_url' value='{$_SHOP_root_secured}paypal_notify.php'>
  <input type="hidden" name="cancel_return" value="{$_SHOP_root_secured}paypal_cancel.php">
  <input type="hidden" name="currency_code" value="GBP">
  <input type='hidden' name='undefined_quantity' value='0'>
  <input type="hidden" name="no_shipping" value="1">
  <input type='hidden' name='no_note' value='1'>
  <input type="image" src="https://www.paypal.com/images/x-click-but23.gif" name="submit2" alt="Make payments with PayPal - it's fast, free and secure!" >
</form>