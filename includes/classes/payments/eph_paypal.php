<?php
/*
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
 
 */


class EPH_paypal extends payment{
  public $extras = array('pm_paypal_business', 'pm_paypal_test');
  public $mandatory = array('pm_paypal_business');


	function admin_view (){
    return "{gui->view name='pm_paypal_business'}".
           "{gui->view name='pm_paypal_test'}";
	}
	
  function admin_form (){
    return "{gui->input name='pm_paypal_business'}".
           "{gui->checkbox name='pm_paypal_test'}";
	}

	function init (){
  	$this->handling_text_payment    = "PayPal";
		$this->handling_text_payment_alt= "PayPal";
    $this->handling_html_template  .= "";
		$this->pm_paypal_test          = true;
	}

	function on_confirm(&$order) {
    if (!$this->pm_paypal_test) {
      $pm_paypal_url= 'https://www.paypal.com/cgi-bin/webscr';
    } else {
      $pm_paypal_url= 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    }
//    <input type='hidden' name='image_url' value='https://www.paypal.com/images/x-click-but23.gif'>

    return "
      <form name='PayPal' action='{$pm_paypal_url}' method='post' onsubmit='this.submit.disabled=true;return true;'>
        <input type='hidden' name='cmd' value='_xclick'>
        <input type='hidden' name='business' value='{$this->pm_paypal_business}'>
        <input type='hidden' name='item_name' value='".$order->order_description()."'>
        <input type='hidden' name='item_number' value='{$order->order_id}'>
        <input type='hidden' name='amount' value='".($order->order_total_price-$order->order_fee)."'>
        <input type='hidden' name='handling' value='".($order->order_fee)."'>
        <input type='hidden' name='return' value='".makeurl('approved/'.$order->order_id)."'>
        <input type='hidden' name='notify_url' value='".makeurl('notify/'.$order->order_id )."'>
        <input type='hidden' name='cancel_return' value='".makeurl('canceled/'.$order->order_id)."'>
        <input type='hidden' name='currency_code' value='{\$organizer_currency}'>
        <input type='hidden' name='undefined_quantity' value='0'>
        <input type='hidden' name='no_shipping' value='1'>
        <input type='hidden' name='no_note' value='1'>
        <input type='hidden' name='rm' value='2'>
        <input type='hidden' name='invoice' value='{$order->order_id}'>
        <div align='right'>
        <input type='submit' value='{!pay!}' name='submit2' alt='{!paypal_pay!}' >
        </div>
      </form>";
	}
	
  function on_notify(&$order){
//    require_once('classes/Order.php');
    global $_SHOP;
    if (!$this->pm_paypal_test) {
      $url= 'https://www.paypal.com/cgi-bin/webscr';
    } else {
      $url= 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    }
//     $url=$this->pm_paypal_url;
    $receiver_email=$this->pm_paypal_business;
    if (!is_numeric($_POST['item_number']) and ($_POST['item_number']<>$order->order_id)) {
      ShopDB::dblogging(print_r($_POST, true));
      return;
    }
    $debug ="date: ".date('r')."\n";
    $debug .="url: $url\n";

    $order_id    = $_POST['item_number'];
    $order_total = $order->order_total_price;

    $debug.="order_id : $order_id\n";
    $debug.="bedrag   : $order_total\n";
    
    $_POST["cmd"]="_notify-validate";

    $result=$this->url_post($url,$_POST);

    $debug.=print_r($_POST,true);

    $debug.="res : $result\n";

    $return = false;
  	if(eregi("VERIFIED",$result)===false) {
        $debug.="NOT OK\n";
    } elseif($_POST["payment_status"]!="Completed") {
         $debug.=$_POST["payment_status"]."\n";
    } elseif($_POST["receiver_email"]!=$receiver_email) {
         $debug.="wrong receiver_email\n";
    } elseif($_POST["mc_gross"]<$order_total) {
         $debug.="Invalid payment\n";
    } else {
    $debug.="OK\n";
        $return =true;
    	  $order->order_payment_id=$_POST['txn_id'];
        $order->set_payment_status('payed');
    }
    $handle=fopen(INC."tmp".DS."paypal.log","a");
    fwrite($handle,$debug);
    fclose($handle);
    return $return;
  }

  function on_submit(&$order, $result){
    If ($result) {
      return array('approved'=>$result,
                   'transaction_id'=>$_REQUEST['txn_id'],
                   'response'=> '');
    } else {
      return array('approved'=>$result,
                   'transaction_id'=>false,
                   'response'=> '');
    }
  }
}
?>