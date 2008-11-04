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

require_once("classes/ShopDB.php");
require_once("admin/AdminView.php");

class pm_yp_View extends AdminView{
	
	
	function pm_view ( &$data ){
    global $_SHOP;
		$this->dyn_load("lang/pm_yp_{$_SHOP->lang}.inc");
		
		$extra = $data['extra'];
	  $this->print_field('pm_yp_shop_id',$extra);
	  $this->print_field('pm_yp_url',$extra);
	}
	
	function pm_init ( &$hand, &$data ){ 
    global $_SHOP;
		
		$ha='$shop_handling';
		
		$form1= 
"
<form action='{\$shop_handling.extra.pm_yp_url}' method='POST'>		
<input type='hidden' name='txtShopId' 
	value='{\$shop_handling.extra.pm_yp_shop_id}'>

<input type='hidden' name='txtLangVersion' value='{lang}'>
<input type='hidden' name='txtArtCurrency' value='CHF'>
<input type='hidden' name='txtOrderTotal' value='{\$order_total_price}'>
<input type='hidden' name='DeliveryPaymentType' value='deferred'>
<input type='hidden' name='txtOrderIDShop' 
			 value='{\$organizer_id}-{\$order_id}'>
<input type='hidden' name='txtShopPara' 
	value='organizer_id={\$organizer_id}&lang={lang}&order_id={\$order_id}'>

<input type='hidden' name='txtBTitle' value=''>

<input type='hidden' name='txtBLastName' value='{user->user_lastname}'>
<input type='hidden' name='txtBFirstName' value='{user->user_firstname}'>
<input type='hidden' name='txtBAddr1' value='{user->user_address}'>
<input type='hidden' name='txtBAddr2' value='{user->user_address1}'>
<input type='hidden' name='txtBAddr3' value=''>
<input type='hidden' name='txtBCity' value='{user->user_city}'>
<input type='hidden' name='txtBCountry' value='{user->user_country}'>
<input type='hidden' name='txtBEmail' value='{user->user_email}'>
<input type='hidden' name='txtBPostBox' value=''>
<input type='hidden' name='txtBTel' value='{user->user_phone}'>
<input type='hidden' name='txtBTitle' value=''>
<input type='hidden' name='txtBZipCode' value='{user->user_zip}'>

<input type='submit' name='submit' 
value='{fr}Payer{/fr}{en}Pay{/en}{de}Bezahlen{/de}{it}Pagare{/it}'>
</form>";	


    $hand->handling_html_template .= $form1;
    $extra=$hand->extra;
		$extra['pm_yp_url']='https://yellowpaytest.postfinance.ch/checkout/Yellowpay.aspx?userctrl=Invisible';
		$hand->extra=$extra;
    //<input type='hidden' name='txtOrderID' value=''>
//    <input type='hidden' name='txtTransactionID' value=''>
//    <input type='hidden' name='txtUseDynPM' value='true'>
//    <input type='hidden' name='txtPM_DebitDirect_Status' value='true'>
//    <input type='hidden' name='txtPM_yellownet_Status' value='false'>
//
//    <input type='hidden' name='txtPM_Master_Status' value='false'>
//    <input type='hidden' name='txtPM_Visa_Status' value='false'>
//    <input type='hidden' name='txtPM_Amex_Status' value='false'>
//    <input type='hidden' name='txtPM_Diners_Status' value='false'>   
	}

	function pm_fill ( &$hand, &$data ){ 
    $extra = $hand->extra;
		
		$extra['pm_yp_shop_id']=$data['pm_yp_shop_id'];
		$extra['pm_yp_url']=$data['pm_yp_url'];

		$hand->extra = $extra;
	}

	
	function pm_check ( &$data, &$err ){
	  $data['extra']['pm_yp_shop_id']=$data['pm_yp_shop_id'];
		return TRUE;
	}
	
  function pm_form ( &$data, &$err ){
		global $_SHOP;
		
		$this->dyn_load("lang/pm_yp_{$_SHOP->lang}.inc");
		
		$docs=array('pm_yp_site'=>'<a class="link" href="https://www.postfinance.ch/FinancePortal/handle/evtCategory.do?category=payingbizdebitepayment" target="_blank">PostFinance</a>',
                'pm_yp_docs'=>'<a class="link" href="https://www.postfinance.ch/FinancePortal/handle/Mediastore/downloads/konz_yellowpay_'.$_SHOP->lang.'.pdf" target="_blank">API Docs(PDF)</a>');
		
		$this->print_input('pm_yp_shop_id',$data['extra'],$err);	
		$this->print_input('pm_yp_url',$data['extra'],$err);	
    $this->print_field('pm_yp_site',$docs);
    $this->print_field('pm_yp_docs',$docs);
	}
	
}
?>