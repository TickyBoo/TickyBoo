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


class EPH_authorize_sim extends AdminView{
  public $extras = array('pm_authorize_sim_login', 'pm_authorize_sim_txnkey',
                         'pm_authorize_sim_test');
  public $mandatory = array('pm_authorize_sim_login', 'pm_authorize_sim_txnkey');

	function admin_view (){
    return "{gui->view name='pm_authorize_sim_login'} ".
        	 "{gui->view name='pm_authorize_sim_txnkey'} ".
       	   "{gui->view name='pm_authorize_sim_test'} ";
	}

  function admin_form (){
    return "{gui->input name='pm_authorize_sim_login'} ".
		       "{gui->input name='pm_authorize_sim_txnkey'} ".
           "{gui->checkbox name='pm_authorize_sim_test'} ";
	}

	function init( ){
    $this->handling_html_template .= '';
		$this->pm_authorize_sim_test=TRUE;
	}

  function on_confirm(&$order){

		$tstamp   = time ();
    $sequence = rand(1, 1000);
		$txnkey   = $this->pm_authorize_sim_txnkey;
		$loginid  = $this->pm_authorize_sim_login;
	  $order_id = $order->order_id;
    $amount   = $order->order_total_price;
    $currency = $order->organizer_currency;
		$fingerprint = $this->hmac ($txnkey, $loginid . "^" . $sequence . "^" . $tstamp . "^" . $amount . "^" . $currency);

  	$return = " <center>
      <FORM action='https://certification.authorize.net/gateway/transact.dll' method='POST'>

      <input type='hidden' name='x_fp_sequence' value='{$sequence}'>
      <input type='hidden' name='x_fp_timestamp' value='{$tstamp}'>
      <input type='hidden' name='x_fp_hash' value='{$fingerprint}'>

      <input type='hidden' name='x_description' value='Order {$order_id}'>
      <input type='hidden' name='x_invoice_num' value='{$order_id}'>

      <input type='hidden' name='x_login' value='{$loginid}'>

      <input type='hidden' name='x_amount' value='{$amount}'>
      <INPUT type='hidden' name='x_currency_code' value='{$currency}'>
      <INPUT type='hidden' name='x_show_form' value='PAYMENT_FORM'>";
    if ($this->pm_authorize_sim_test) {
      $return .= "<INPUT type='hidden' name='x_test_request' value='TRUE'>";
    }
    $return .= "
      <input type='hidden' name='x_last_name' value='{user->user_lastname}'>
      <input type='hidden' name='x_first_name' value='{user->user_firstname}'>
      <input type='hidden' name='x_address' value='{user->user_address} {user->user_address1}'>

      <input type='hidden' name='x_city' value='{user->user_city}'>
      <input type='hidden' name='x_country' value='{user->user_country}'>
      <input type='hidden' name='x_email' value='{user->user_email}'>
      <input type='hidden' name='x_phone' value='{user->user_phone}'>
      <input type='hidden' name='x_zip' value='{user->user_zip}'>

      <input type='hidden' name='pmt_lang' value='{lang}'>

      <INPUT type='submit' value='Accept Order'>
      </form></center>";
    return $return;
	}


  function on_submit(&$order, $subaction){
		$order_id=$order->order_id;

		if($_POST['x_response_code']==1){
		  $order->order_payment_id=$_POST['x_trans_id'];
		  $order->set_payment_status('payed');
    }else{
			return array('approved'=>false, 'responce'=>$$_POST['x_response_text']);
		}
	}

  //AUTHORIZE.NET PROVIDE THIS CODE:

  // DISCLAIMER:
  //     This code is distributed in the hope that it will be useful, but without any warranty;
  //     without even the implied warranty of merchantability or fitness for a particular purpose.

  // Main Interfaces:
  //
  // function InsertFP ($loginid, $txnkey, $amount, $sequence) - Insert HTML form elements required for SIM
  // function CalculateFP ($loginid, $txnkey, $amount, $sequence, $tstamp) - Returns Fingerprint.


  // compute HMAC-MD5
  // Uses PHP mhash extension. Pl sure to enable the extension
  //function hmac ($key, $data)
  //{
  //return (bin2hex (mhash(MHASH_MD5, $data, $key)));
  //}

  private function hmac ($key, $data)
  {
     // RFC 2104 HMAC implementation for php.
     // Creates an md5 HMAC.
     // Eliminates the need to install mhash to compute a HMAC
     // Hacked by Lance Rushing

     $b = 64; // byte length for md5
     if (strlen($key) > $b) {
         $key = pack("H*",md5($key));
     }
     $key  = str_pad($key, $b, chr(0x00));
     $ipad = str_pad('', $b, chr(0x36));
     $opad = str_pad('', $b, chr(0x5c));
     $k_ipad = $key ^ $ipad ;
     $k_opad = $key ^ $opad;

     return md5($k_opad  . pack("H*",md5($k_ipad . $data)));
  }

  // Calculate and return fingerprint
  // Use when you need control on the HTML output
  //function CalculateFP ($loginid, $txnkey, $amount, $sequence, $tstamp, $currency = "")
  //{
  //return (hmac ($txnkey, $loginid . "^" . $sequence . "^" . $tstamp . "^" . $amount . "^" . $currency));
  //}


  // Inserts the hidden variables in the HTML FORM required for SIM
  // Invokes hmac function to calculate fingerprint.

  //function InsertFP ($loginid, $txnkey, $amount, $sequence, $currency = "")
  //{

  //$tstamp = time ();

  //$fingerprint = hmac ($txnkey, $loginid . "^" . $sequence . "^" . $tstamp . "^" . $amount . "^" . $currency);

  //echo ('<input type="hidden" name="x_fp_sequence" value="' . $sequence . '">' );
  //echo ('<input type="hidden" name="x_fp_timestamp" value="' . $tstamp . '">' );
  //echo ('<input type="hidden" name="x_fp_hash" value="' . $fingerprint . '">' );


  //return (0);

  //}

}
?>