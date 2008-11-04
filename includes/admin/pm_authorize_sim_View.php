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

class pm_authorize_sim_View extends AdminView{
	
	
	function pm_view ( &$data ){
    global $_SHOP;
		$this->dyn_load("lang/pm_authorize_sim_{$_SHOP->lang}.inc");
		
		$extra = $data['extra'];
	  $this->print_field('pm_authorize_sim_login',$extra);
	  $this->print_field('pm_authorize_sim_txnkey',$extra);
	  $this->print_field('pm_authorize_sim_test',$extra);
	}
	
	function pm_init ( &$hand, &$data ){ 
    global $_SHOP;
				
		$form1= 
"
<center>
<FORM action='https://certification.authorize.net/gateway/transact.dll' method='POST'>

<input type='hidden' name='x_fp_sequence' value='{\$pm_return.sequence}'>
<input type='hidden' name='x_fp_timestamp' value='{\$pm_return.timestamp}'>
<input type='hidden' name='x_fp_hash' value='{\$pm_return.fingerprint}'>

<input type='hidden' name='x_description' value='Order {\$order_id}'>
<input type='hidden' name='x_invoice_num' value='{\$order_id}'>

<input type='hidden' name='x_login' 
  value='{\$shop_handling.extra.pm_authorize_sim_login}'>

<input type='hidden' name='x_amount' value='{\$order_total_price}'>
<INPUT type='hidden' name='x_currency_code' value='{\$organizer_currency}'>
<INPUT type='hidden' name='x_show_form' value='PAYMENT_FORM'>
{if \$shop_handling.extra.pm_authorize_sim_test }
<INPUT type='hidden' name='x_test_request' value='TRUE'>
{/if}

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
</FORM>
</form></center>";	


    $hand->handling_html_template .= $form1;
    $extra=$hand->extra;
		$extra['pm_authorize_sim_test']=TRUE;
		$hand->extra=$extra;
	}

	function pm_fill ( &$hand, &$data ){ 
    $extra = $hand->extra;
		
		$extra['pm_authorize_sim_login']=$data['pm_authorize_sim_login'];
		$extra['pm_authorize_sim_txnkey']=$data['pm_authorize_sim_txnkey'];
		$extra['pm_authorize_sim_test']=$data['pm_authorize_sim_test'];

		$hand->extra = $extra;
	}

	
	function pm_check ( &$data, &$err ){
		
		
	  $data['extra']['pm_authorize_sim_login']=$data['pm_authorize_sim_login'];
	  $data['extra']['pm_authorize_sim_txnkey']=$data['pm_authorize_sim_txnkey'];
	  $data['extra']['pm_authorize_sim_test']=$data['pm_authorize_sim_test'];

		return TRUE;
	}
	
  function pm_form ( &$data, &$err ){
		global $_SHOP;
		
		$this->dyn_load("lang/pm_authorize_sim_{$_SHOP->lang}.inc");
		
		//$docs=array('pm_authorize_sim_site'=>'<a class="link" href="https://www.authorize_sim.com/" target="_blank">PayPal</a>');

		$this->print_input('pm_authorize_sim_login',$data['extra'],$err);	
		$this->print_input('pm_authorize_sim_txnkey',$data['extra'],$err);	
    $this->print_checkbox('pm_authorize_sim_test',$data['extra'],$err);
//    $this->print_field('pm_yp_docs',$docs);
	}
	
}
?>