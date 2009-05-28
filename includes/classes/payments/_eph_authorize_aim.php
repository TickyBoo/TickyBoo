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
require_once('classes/Payment.php');
class EPH_authorize_aim Extends Payment{
  public $extras = array ('pm_authorize_aim_login', 'pm_authorize_aim_txnkey',
                          'pm_authorize_aim_hash',  'pm_authorize_aim_test');
  public $mandatory = array ('pm_authorize_aim_login', 'pm_authorize_aim_txnkey',
                             'pm_authorize_aim_hash');

	function admin_view (){
	  return "{gui->view name='pm_authorize_aim_login'}".
	         "{gui->view name='pm_authorize_aim_txnkey'}".
	         "{gui->view name='pm_authorize_aim_hash'}".
	         "{gui->view name='pm_authorize_aim_test'}";
	}
	
  function admin_form (){
		//$docs=array('pm_authorize_aim_site'=>'<a class="link" href="https://www.authorize_aim.com/" target="_blank">PayPal</a>');
    return  "{gui->input name='pm_authorize_aim_login'}".
        		"{gui->input name='pm_authorize_aim_txnkey'}".
        		"{gui->input name='pm_authorize_aim_hash'}".
            "{gui->checkbox name='pm_authorize_aim_test'}";
//    $this->print_field('pm_yp_docs',$docs);
	}

	function admin_init (){
		$form1= '<div class="cc_div">
              To validate your order please introduce your payment information and
              click on "Pay". <br> At once that your payment is completed, you receive
              your tickets by e-mail. <br>If we cannot record cashing during 12 next hours,
              your order is cancelled automatically. <br><br>
             </div>';


    $this->handling_html_template   .= $form1;
		$this->handling_text_payment     = 'Credit Card';
		$this->handling_text_payment_alt = 'Credit Card';
		$this->pm_authorize_aim_test     = TRUE;
	}


  function on_confirm(&$order ) {
    Global $_SHOP;
    if (!isset($_POST['cc_name'])) {
      $_POST['cc_name'] = "{$order->user_firstname} {$order->user_lastname}";
    }
		$order_id=$order->order_id;
    return "<form action='".$_SHOP->root_secured."checkout.php?".$order->EncodeSecureCode()."' method='POST' onsubmit='this.submit.disabled=true;return true;'>
            <table class='cc_form' cellpadding='5'>
            <input type='hidden' name='action' value='submit'>
            {gui->input name='cc_name'}
            {gui->input name='cc_number'}
            {gui->inputdate type='MY' name=cc_exp}
            {gui->input name='cc_code' size='4' lenght='4'}
            </table>
            <INPUT type='submit' name='submit' value='{!pay!}' >
            </form>";
  }
  
  
  function on_submit(&$order, &$err){

		global $_SHOP;

		$date = getdate();
		if($_POST['cc_exp_y']<($date['year']-2000) or
		($_POST['cc_exp_y']==($date['year']-2000) and $_POST['cc_exp_m']<$date['mon'])){
			$err['cc_exp']= con('invalid_date');
		}

//verify by mod10 formula
		if(empty($_POST['cc_number']) or !$this->ccval($_POST['cc_number'])){
			$err['cc_number']= con('invalid_number');
		}
//verify...

/*		if(strlen(trim($cc_name))==0){
			$err['cc_name']=1;
		}
	*/


		if(!empty($err)){
			return $this->on_confirm($order);
		}

		if($this->pm_authorize_aim_test){
			$url="https://certification.authorize.net/gateway/transact.dll";
		}else{
			$url="https://secure.authorize.net/gateway/transact.dll";
		}

		$order_id=$order->order_id;
		$order_total=$order->order_total_price;

    $post['x_login']   =$this->pm_authorize_aim_login;
    $post['x_tran_key']=$this->pm_authorize_aim_txnkey;
		if($this->pm_authorize_aim_test){
		  $post['x_test_request']='TRUE';
		}

		$post['x_delim_data']='TRUE';
		$post['x_delim_char']=',';
		$post['x_encap_char']='';
		$post['x_relay_response']='FALSE';

		$post['x_invoice_num']=$order_id;
    $post['x_description']=con('order')." ".$order_id;

		$post['x_amount'] = $order_total;
    $post['x_currency_code'] = $order->organizer_currency;

		$post['x_card_num'] = $_POST['cc_number'];
		$post['x_exp_date'] = $_POST['cc_exp'];
		$post['x_card_code'] = $_POST['cc_code'];

		$post['x_cust_id'] = $order->user_id;
		$post['x_first_name'] = $order->user_firstname;
		$post['x_last_name'] = $order->user_lastname;
		$post['x_address'] = $order->user_address.' '.$order->user_address1;
		$post['x_city'] = $order->user_city;
		$post['x_zip'] = $order->user_zip;
		$post['x_country'] = $order->user_country;
		$post['x_phone'] = $order->user_phone;
		$post['x_fax'] = $order->user_fax;
		$post['x_email'] = $order->user_email;

		$post['x_customer_ip'] = $_SERVER['REMOTE_ADDR'];

		//echo "<pre>";		print_r($post); echo "</pre>";

		$res=$this->url_post($url,$post);

		if(!empty($res)){
			$res=explode(",",$res);

			$response_code              =$res[0];
			$transaction_id             =$res[6];
			$order_id                   =$res[7];
			$md5_hash                   =$res[37];

			$return['response']         = "( {$res[0]} / {$res[1]} / {$res[2]}  ) {$res[3]}";
			$return['transaction_id']      =$res[6];
  	  $return['approved']=false;
  	  
			if($response_code==1){
				if($order=Order::load_ext($order_id)){
					if($this->_check_order($order,$res)){

						$order->order_payment_id=$transaction_id;
      	    Order::set_payment_id('auth_aim:'.$order->order_id,$transactionID)
						$order->set_payment_status('payed');
						$return['approved']=TRUE;

					}else{
						$return['response']="Payment Error: Order $order_id check failed!";
					}
				}else{
				  $return['response']="Payment Error: Order $order_id not found!";
				}
			}
		}else{
			$return['response']="Payment Error: Order $order_id can't be valided, no responce from Authorize.net!";
    }
		return $return ;
	}

	private function _check_order(&$order, &$res){
	  $check=TRUE;

		//echo "{$order->order_total_price}=={$res[9]} ";

		//commented because it is not clear how the x_amount is formatted
	  //for some currency 1.00 or 1,00, 1'000.00 or 1000.00?
		//$check = ($check and ($order->order_total_price==$res[9]));

		//echo "<br>{$order->order_user_id}=={$res[12]} ";

		$check = ($check and ($order->order_user_id==$res[12]));

		//echo "<br>";

		if($h_val = $this->pm_authorize_aim_hash){

			$md5 = strtoupper(md5($h_val.$this->pm_authorize_aim_login.$res[6].$res[9]));

			//echo "$h_val:{$this->pm_authorize_aim_login}:{$res[6]}:{$res[9]}<br>";
			//echo strtoupper($res[37])."<br>";
			//echo $md5;

			$check = ($check and (strtoupper($res[37])==$md5));
		}

		return $check;
	}

}
?>