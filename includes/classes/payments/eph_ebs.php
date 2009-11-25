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
if (!defined('ft_check')) {die('System intrusion ');}

require_once('classes/Payment.php');

class EPH_ebs extends payment{
  public $extras = array('pm_ebs_accountid', 'pm_ebs_seckey','pm_ebs_test');
  public $mandatory = array('pm_ebs_accountid', 'pm_ebs_seckey');


	function admin_view (){
    return "{gui->view name='pm_ebs_accountid'}".
           "{gui->view name='pm_ebs_seckey'}".    
           "{gui->view name='pm_paypal_test'}";
	}
	
  function admin_form (){
    return "{gui->input name='pm_ebs_accountid'}".
           "{gui->input name='pm_ebs_seckey'}".
           "{gui->checkbox name='pm_ebs_test'}";
	}

	function admin_init (){
    $this->handling_text_payment    = "ebs";
    $this->handling_text_payment_alt= "ebs";
    $this->handling_html_template  .= "";
    $this->pm_ebs_test  = true;
	}

	function on_confirm(&$order) {
    global $_SHOP;
    if (!$this->pm_ebs_test) {
      $ebs_mode= 'LIVE';
    } else {
      $ebs_mode= 'TEST';
    }
    return "
      <form  method='post' action='https://secure.ebs.in/pg/ma/sale/pay/' name='frmTransaction' id='frmTransaction'>
        <input name='account_id' type='hidden' value='{$this->pm_ebs_accountid}' />
        <input name='return_url' type='hidden' value='".$_SHOP->root_secured. 'checkout_accept.php?'.$order->EncodeSecureCode()."&DR={DR}' />
        <input name='mode' type='hidden' value='{$ebs_mode}'  />
        <input name='reference_no' type='hidden' value='{$order->order_id}'  />
        <input name='amount' type='hidden' value='".sprintf("%01.2F", ($order->order_total_price))."' />
        <input name='description' type='hidden' value='{$order->order_description()}'  /> 
        <input name='name' type='hidden' value='{$order->user_firstname} {$order->user_lastname}'  />
        <input name='city' type='hidden'  value='{$order->user_city}' />    
        <input name='state' type='hidden' value='{$order->user_state}'  />
        <input name='postal_code' type='hidden'  value='{$order->user_zip}' />
        <input name='country' type='hidden'  value='IND' /> <!-- {$order->user_country} -->
        <input name='email' type='hidden'  value='{$order->user_email}' />
        <input name='phone' type='hidden'  value='{$order->user_phone}' />
        <input name='ship_name' type='hidden'  value='{$order->user_firstname} {$order->user_lastname}' />
        <input name='ship_address' type='hidden'  value='{$order->user_address}' />
        <input name='ship_city' type='hidden' value='{$order->user_city}'  />
        <input name='ship_state' type='hidden'  value='{$order->user_state}' />    
        <input name='ship_postal_code' type='hidden'  value='{$order->user_zip}' />    
        <input name='ship_country'  type='hidden'  value='IND' > <!-- {$order->user_country} -->
        <input name='ship_phone' type='hidden' value='{$order->user_phone}'/>
        
        <div align='right'>
          <input type='submit' value='{!pay!}' name='submitted' alt='{!paypal_pay!}' >
        </div>
      </form>";
  }

  function on_return(&$order, $result){

    if(isset($_GET['DR'])) {
      require('.\ebs\Rc43.php');
      $DR = preg_replace("/\s/","+",$_GET['DR']);
      $rc4 = new Crypt_RC4($this->pm_ebs_seckey);
      $QueryString = base64_decode($DR);
      $rc4->decrypt($QueryString);
      $QueryString = split('&',$QueryString);

      $response = array();
      foreach($QueryString as $param){
        $param = split('=',$param);
        $response[$param[0]] = urldecode($param[1]);
      }
    }
  
    if ($response['PaymentID']) {
      Order::set_payment_id($order->order_id,'paypal:'.$response['PaymentID']);
    }
    if ($response['ResponceCode']===0) {
      $order->set_payment_status('paid');
      return array('approved'=>true,
                   'transaction_id'=>$response['PaymentID'],
                   'response'=> $response['ResponceMessage']);
    } else {
      return array('approved'=>false,
                   'transaction_id'=>$response['PaymentID'],
                   'response'=> $response['ResponceCode'].') '. $response['ResponceMessage']);
    }
  }
}
?>