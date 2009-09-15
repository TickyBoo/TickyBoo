<?php
/**
%%%copyright%%%
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
 */

require_once('classes/Payment.php');
class EPH_cash extends payment{
  public $extras = array();

 	function admin_view (){

	}
	
  function admin_form (){
	}

	function admin_init (){
    global $_SHOP;
				
	}

	
  function on_confirm(&$order) {
    if (!isset($_POST['cc_name'])) {
      $user = User::load_user($_SESSION['_SHOP_USER']);  //'user'
      $_POST['cc_name'] = "{$user['user_firstname']} {$user['user_lastname']}";
    }
		$order_id= $order->order_id;
    return "<form action='".$_SHOP->root_secured."checkout.php?".$order->EncodeSecureCode()."' method='POST' onsubmit='this.submit.disabled=true;return true;'>
            <input type='hidden' name='action' value='submit'>
            ".eph_cash_confirm."<br>
            <div align='right'>
              <INPUT type='submit' name='submit' value='{!pay!}' >
              <input type='hidden' name='order_id' value='{$order_id}'>
            </div>
            </form>";
  }

  function on_submit(&$order, &$err){
    $order->set_payment_status('payed');
		return array('approved'=>TRUE);
	}


}
?>