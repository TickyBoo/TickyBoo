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

class pm_authorize_sim{

  function on_order_confirm(&$pay,&$smarty){
		
		$tstamp = time ();
    $sequence = rand(1, 1000);
		$txnkey = $pay['extra']['pm_authorize_sim_txnkey'];
		$loginid = $pay['extra']['pm_authorize_sim_login'];
    $amount = $smarty->get_template_vars('order_total_price');
    $currency = $smarty->get_template_vars('organizer_currency');
		$fingerprint = $this->hmac ($txnkey, $loginid . "^" . $sequence . "^" . $tstamp . "^" . $amount . "^" . $currency);

		$result=array(
			'timestamp'=>$tstamp, 
			'sequence'=>$sequence, 
			'fingerprint'=>$fingerprint);

		return $result;	
	}
	
	
  function on_notify(&$pay,&$smarty){
    require_once('classes/Order.php');
		global $_SHOP;
		
		$order_a=$smarty->get_template_vars('shop_order');
		$order_id=$order_a['order_id'];
		
		if($_POST['x_response_code']==1){
 		  if($order=Order::load_ext($order_id)){
			  $order->order_payment_id=$_POST['x_trans_id'];
			  $order->set_payment_status('payed');
				return array('success'=>TRUE);
		  }
		}else{
		  $order->delete($order_id, $_SHOP->organizer_id);
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

function hmac ($key, $data)
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