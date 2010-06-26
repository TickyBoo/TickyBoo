<?php
/**
%%%copyright%%%
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
 */
if (!defined('ft_check')) {die('System intrusion ');}
require_once('classes/class.payment.php');
class EPH_invoice extends payment{
  public $extras = array();

 	function admin_view (){

	}

  function admin_form (){
	}

	function admin_init (){
    global $_SHOP;

	}


  function on_confirm(&$order, $alreadypayed=0.0) {
    global $_SHOP;
    if (!isset($_POST['cc_name'])) {
      $user = User::load($_SESSION['_SHOP_USER']);  //'user'
      $_POST['cc_name'] = "{$user['user_firstname']} {$user['user_lastname']}";
    }
		$order_id= $order->order_id;
    $alreadypayed=(float) $alreadypayed;//title=\"".con('eph_cash_confirm')."\"
    return "{gui->StartForm  width='100%' id='payment-confirm-form' action='{$_SHOP->root_secured}checkout.php' method='POST' onsubmit='this.submit.disabled=true;return true;'}
              <input type='hidden' name='action' value='submit'>
              <input type='hidden' name='sor' value='{$order->EncodeSecureCode('')}'>
              <input type='hidden' name='order_id' value='{$order_id}'>
              <input type='hidden' name='alreadypayed' value='{$alreadypayed}'>
              {gui->valuta value='{$alreadypayed}' assign=test}
              ".(($alreadypayed)?"{gui->view name='order_payed_already' value=$"."test}":"")."
              {gui->input name='order_payed_total' value='".valuta(($order->order_total_price -$alreadypayed),' ')."'}
            {gui->EndForm title=!pay! noreset=true}
            ";
  }

  function on_submit(&$order){
    $payed = (float) ((float)$_POST['alreadypayed'] + (float) $_POST['order_payed_total']);
    if ((float)$order->order_total_price == $payed ) {
      $order->set_payment_status('payed');
		  return array('approved'=>TRUE);
    } else {
      return self::on_confirm($order, $payed);
    }
	}


}
?>