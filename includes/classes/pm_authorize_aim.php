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

class pm_authorize_aim{
		
  function on_cc_submit(&$pay,&$smarty){
		
    require_once('functions/connect_func.php');
    require_once('classes/Order.php');

		global $_SHOP;
		global $HTTP_SERVER_VARS;
		 
		if($pay['extra']['pm_authorize_aim_test']){
			$url="https://certification.authorize.net/gateway/transact.dll";
		}else{
			$url="https://secure.authorize.net/gateway/transact.dll";
		} 
		
		$order_a=$smarty->get_template_vars('shop_order');
		$order_id=$order_a['order_id'];
		$order_total=$order_a['order_total_price'];
	
    $post['x_login']=$pay['extra']['pm_authorize_aim_login'];
    $post['x_tran_key']=$pay['extra']['pm_authorize_aim_txnkey'];
		if($pay['extra']['pm_authorize_aim_test']){
		  $post['x_test_request']='TRUE';
		}
		
		$post['x_delim_data']='TRUE';
		$post['x_delim_char']=',';
		$post['x_encap_char']='';
		$post['x_relay_response']='FALSE';
		
		$post['x_invoice_num']=$order_id;
    $post['x_description']=order." ".$order_id;

		$post['x_amount'] = $order_total;
    $post['x_currency_code'] = $smarty->get_template_vars('organizer_currency');

		$post['x_card_num'] = $_POST['cc_number'];
		$post['x_exp_date'] = $_POST['cc_month'].$_POST['cc_year'];
		$post['x_card_code'] = $_POST['cc_code'];
		
		$post['x_cust_id'] = $order_a['user_id'];
		$post['x_first_name'] = $order_a['user_firstname'];
		$post['x_last_name'] = $order_a['user_lastname'];
		$post['x_address'] = $order_a['user_address'].' '.$order_a['user_address1'];
		$post['x_city'] = $order_a['user_city'];
		$post['x_zip'] = $order_a['user_zip'];
		$post['x_country'] = $order_a['user_country'];
		$post['x_phone'] = $order_a['user_phone'];
		$post['x_fax'] = $order_a['user_fax'];
		$post['x_email'] = $order_a['user_email'];

		$post['x_customer_ip'] = $HTTP_SERVER_VARS['REMOTE_ADDR'];

		//echo "<pre>";		print_r($post); echo "</pre>";
		
		$res=url_post($url,$post);
		
		if(!empty($res)){	
			$res=explode(",",$res);

			$response_code=$res[0];
			$transaction_id=$res[6];
			$order_id=$res[7];
			$md5_hash=$res[37];

			$res['response_code']=$res[0];
			$res['response_subcode']=$res[1];
			$res['response_reason_code']=$res[2];
			$res['response_reason_text']=$res[3];
			$res['transaction_id']=$res[6];

			$transaction_id=$res[6];
			$order_id=$res[7];
			$md5_hash=$res[37];
			
			
			if($response_code==1){					
				if($order=Order::load_ext($order_id)){
					if($this->_check_order($order,$res,$pay)){

						$order->order_payment_id=$transaction_id;
						$order->set_payment_status('payed');
						return array('approved'=>TRUE,'details'=>$res);
						
					}else{
						return array('details'=>array(3=>"phpMyTicket ERROR: Order $order_id check failed!"));
					}	
				}else{
				  return array('details'=>array(3=>"phpMyTicket ERROR: Order $order_id not found!"));
				}
			}
			return array('details'=>$res);
		}
	}

	function _check_order(&$order,&$res,&$pay){
	  $check=TRUE;
		
		//echo "{$order->order_total_price}=={$res[9]} ";
		
		//commented because it is not clear how the x_amount is formatted
	  //for some currency 1.00 or 1,00, 1'000.00 or 1000.00?	
		//$check = ($check and ($order->order_total_price==$res[9]));

		//echo "<br>{$order->order_user_id}=={$res[12]} ";

		$check = ($check and ($order->order_user_id==$res[12]));
		
		//echo "<br>";
		
		if($h_val = $pay['extra']['pm_authorize_aim_hash']){
      
			$md5 = strtoupper(md5($h_val.$pay['extra']['pm_authorize_aim_login'].$res[6].$res[9]));
			
			//echo "$h_val:{$pay['extra']['pm_authorize_aim_login']}:{$res[6]}:{$res[9]}<br>";
			//echo strtoupper($res[37])."<br>";
			//echo $md5;
			
			$check = ($check and (strtoupper($res[37])==$md5));
		}
		
		return $check;
	}

}
?>