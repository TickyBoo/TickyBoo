<?php
/*
 * %%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 * Copyright (C) 2007-2009 Christopher Jenkins. All rights reserved.
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
 */
 
// Include all the required files

require_once('google/library/googlecart.php');
require_once('google/library/googleitem.php');
require_once('google/library/googleshipping.php');
require_once('google/library/googletax.php');

require_once('classes/Payment.php');
	
class EPH_google extends Payment{
	
	public $extras = array('pm_google_merchant_id', 'pm_google_merchant_key','pm_google_sandbox','pm_google_callback_link');
  	public $mandatory = array('pm_google_merchant_id', 'pm_google_merchant_key'); // is only used in project vazant.
  	 	
 	public function init (){
  		$this->handling_text_payment    = "Google Checkout";
		$this->handling_text_payment_alt= "Google Checkout";
		//$this->handling_html_template  .= "";
		$this->pm_google_sandbox  = true;
		$this->pm_google_callback_link = $_SHOP->root_secured. 'checkout_notify.php?'.$this->encodeCallback();
	}
	
	public function admin_form (){
		global $_SHOP;
		
		$form = "{gui->input name='pm_google_merchant_id'}".
           "{gui->input name='pm_google_merchant_key'}".
           "{gui->checkbox name='pm_google_sandbox'}".
		   "{gui->view name='pm_google_callback_link'}";
           
           return $form;
	}
	
	public function admin_view (){
    	return "{gui->view name='pm_google_merchant_id'}".
           "{gui->view name='pm_google_sandbox'}";
	}
	
	public function admin_check(&$data, &$err){
		global $_SHOP;
		$data['pm_google_callback_link'] = $_SHOP->root_secured. 'checkout_notify.php?'.$this->encodeCallback();
		
		return true;
	}
	
	public function on_confirm(&$order){
		global $_SHOP;
		
		$merchant_id = $this->pm_google_merchant_id;  // Your Merchant ID
      	$merchant_key = $this->pm_google_merchant_key;  // Your Merchant Key
      	$server_type = "sandbox";
      	$currency = $_SHOP->organizer_data->organizer_currency;
      	
      	if($this->pm_google_sandbox){
			$server_type = "sandbox";
		}else{
			$server_type = "live";
		}
		
      	$googleCart = new GoogleCart($merchant_id, $merchant_key, $server_type,$currency);
      	
      	$total_count = 1;
      
      	$googleItem = new GoogleItem($_SHOP->organizer_data->organizer_name,      // Item name
                               $order->order_description(), // Item      description
                               $total_count, // Quantity
                               $order->order_total_price); // Unit price
		$googleCart->AddItem($googleItem);
		
		// Add shipping options
		$ship_1 = new GoogleFlatRateShipping("See Box Office", 0.0);
		
      	$Gfilter = new GoogleShippingFilters();
      	$Gfilter->SetAllowedCountryArea('ALL');
      	$Gfilter->SetAllowedWorldArea(true);
      
      	$ship_1->AddShippingRestrictions($Gfilter);
      
      	$googleCart->AddShipping($ship_1);
      
      	// Add tax rules
      	$tax_rule = new GoogleDefaultTaxRule(0.00);
      	$tax_rule->SetWorldArea(true);
      	
      	$googleCart->AddDefaultTaxRules($tax_rule);
      
      	// Specify <edit-cart-url>
      	$googleCart->SetEditCartUrl("".$_SHOP->root_secured.'checkout.php');
		
		// Specify "Return to xyz" link
		$googleCart->SetContinueShoppingUrl("".$_SHOP->root_secured. 'checkout_accept.php?'.$order->EncodeSecureCode()."");
		
		// Request buyer's phone number
		$googleCart->SetRequestBuyerPhone(true);
		
		$ftData = array('order-id'=>$order->order_id);
		
		$merchantPrivateData = new MerchantPrivateData($ftData);
		
		$googleCart->SetMerchantPrivateData($merchantPrivateData);
		
		
		// Display Google Checkout button
		return $googleCart->CheckoutButtonCode("SMALL");
	}
	
	public function encodeCallback(){
    	$md5 = $this->pm_google_merchant_id;
    	$code = md5($md5, true);
    	
    	return $this->encodeEPHCallback($code);
	}
	
	public function decodeCallback(){
		
	}
	
}
?>