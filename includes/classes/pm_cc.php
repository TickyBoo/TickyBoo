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
class pm_cc{
		
  function on_cc_submit(&$pay,&$smarty){
		
		require_once('functions/crypt_func.php');
    require_once('functions/connect_func.php');
    require_once('functions/ccval.php');

		global $_SHOP;
		global $HTTP_SERVER_VARS;
		 
		$order=$smarty->get_template_vars('shop_order');

		$order_id=$order['order_id'];
		$order_total_price=$order['order_total_price'];
		$cc_pubkey=$pay['extra']['pm_cc_pubkey'];		

		$currency = $smarty->get_template_vars('organizer_currency');		

		$cc_name = $_POST['cc_name'];
		$cc_number = $_POST['cc_number'];
		$cc_month = $_POST['cc_month'];
		$cc_year = $_POST['cc_year'];
		$cc_code = $_POST['cc_code'];
		
//verify...		
		
/*		if(strlen(trim($cc_name))==0){
			$err['cc_name']=1;
		}
	*/	
		$date = getdate();
		if($cc_year<($date['year']-2000) or 
		($cc_year==($date['year']-2000) and $cc_month<$date['mon'])){
			$err['cc_date']=1;
		}

//verify by mod10 formula		
		if(empty($cc_number) or !ccval($cc_number)){
			$err['cc_number']=1;
		}
		
		if(!empty($err)){
			return array('approved'=>FALSE,'err'=>$err);				
		}

//store

		$cc_info = '"'.$order_id.'","'.
			$order_total_price.'","'.
			$currency.'","'.
			$cc_name.'","'.
			$cc_number.'","'.
			$cc_month.'","'.
			$cc_year.'","'.
			$cc_code.'"';

		
			
		if($cinfo = ssl_crypt($cc_info,$cc_pubkey)){
			if($this->_store($order_id, $cinfo)){
				return array('approved'=>TRUE);
			}
			return array('approved'=>FALSE,'err'=>array('cc_store'=>1));
		}
		
		return array('approved'=>FALSE,'err'=>array('cc_seal'=>1));				
	}

	
	function _store($order_id,$cinfo){
		global $_SHOP;
		require_once('classes/ShopDB.php');
				
		//echo "_store($order_id,$sealed64,$ekey64)";
		
		$query="insert into CC_Info set cc_info_order_id='$order_id',
		cc_info_data='$cinfo', cc_info_organizer_id='{$_SHOP->organizer_id}'";
		return ShopDB::query($query);		
	}

	function handle($order,$new_status,$old_status,$field){
		global $_SHOP;
		require_once('classes/ShopDB.php');

		if($order->order_id){
			if($field=='order_payment_status' and $new_status=='payed'){
				$query="DELETE from CC_Info where cc_info_order_id='{$order->order_id}'
				and cc_info_organizer_id='{$_SHOP->organizer_id}'";

				ShopDB::query($query);
			}
		}
	}	

	function on_order_delete($order_id){
		global $_SHOP;
		require_once('classes/ShopDB.php');

		if($order_id){
				$query="DELETE from CC_Info where cc_info_order_id='$order_id'
				and cc_info_organizer_id='{$_SHOP->organizer_id}'";

				ShopDB::query($query);
		}
	}	

}
?>