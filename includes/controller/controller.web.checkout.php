<?php
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2010 Christopher Jenkins, Niels, Lou. All rights reserved.
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

require_once ("controller.web.shop.php");



class ctrlWebCheckout extends ctrlWebShop {

  public function __construct($context='web') {
    parent::__construct($context);
    $this->checkSSL();
  }

  public function draw($page, $action, $isAjax= false) {
    GLOBAL $_SHOP;
    if (!$action) {$action = 'index';}
    ShopDB::begin('Running the Checkout pages'); //Buggy
    if (isset($_REQUEST['sor']) || isset($_REQUEST['cbr'])) {
    	if (is_callable(array($this,'action'.$action)) and ($fond = call_user_func_array(array($this,'action'.$action),array()))) {
    		$this->smarty->display($fond . '.tpl');
    	}
    } elseif ($this->__MyCart->can_checkout_f() or isset($_SESSION['_SHOP_order']) ) { //or isset($_SESSION['order'])
      if ( !$_REQUEST['pos'] and
           (!$this->__User->logged and ($this->__User->user_status!=3)) and
  		     $action !== 'register' and
        	 $action !== 'login' ) {
        $this->smarty->display('user_register.tpl');
     	} elseif (is_callable(array($this,'action'.$action)) and ($fond = call_user_func_array(array($this,'action'.$action),array()))) {

     	  $this->smarty->display($fond . '.tpl');
      } else {
        echo "!!did is not good!!";
      }

    } else {
      if ($action == 'useredit') {
        $array = array('status'=>false,'msg'=>con('checkout_expired'));
        echo json_encode($array);
      } elseif(!$_REQUEST['pos']) {
      	redirect($_SHOP->root."index.php?action=cart_view",403);
      } else {
        addWarning('noting_checkout'); echo 'bummer';
      }
    }
    if (ShopDB::isTxn()) { //Commit allready does this check!
      ShopDB::commit('Checkout page rendered.'); //Never Committs!
    }
    orphanCheck();
    trace("End of shop \n\n\r");
  }

  Function actionLogin (){
    if (!$user->logged) {
  	  If (! $this->__User->login_f($_POST['username'], $_POST['password'], $errors)) {
  	    $this->assign('login_error',$errors);
  	    return "user_register";
      }
    }
    return "checkout_preview";
  }

  Function actionUseredit (){
    $this->assign('usekasse',true);
    if (isset($_POST['submit_update'])) {
      if ($this->__User->update_f($_POST, $errors)) {
        $array = array('saved'=>true,'msg'=>con('user_details_saved_successfully'));
        echo json_encode($array);
        myExit();
      }
      $array = array('saved'=>false,'msg'=>printMsg('__Errors__', null, false).printMsg('__Warning__', null, false));
      echo json_encode($array);
      myExit();
    } else {
      $this->assign('user_data',   $this->__User->asarray());
    }
    return "user_update";
  }

  Function actionUseraddress (){
    $this->assign('title', true);
    $this->assign('user_data',   $this->__User->asarray());
    return "user_address";
  }

  /**
   * registerAction()
   *
   * @return String : SmartyPage
   */
  function actionRegister (){

    //if registerasmemeber field is not set, the user doenst want to be a member
    $type = is($_POST['ismember'],false);

    //Try and Register
    $user_id = $this->__User->register_f($type, $_POST, $errors, 0, 'user_nospam');
    //If errors return to user registration.
    if (!$user_id  ) {
      $this->assign('user_data',   $_POST);
      $this->assign('reg_type',    $type);
      return "user_register";
    } else {
      $this->assign('newuser_id', $user_id);
    }
    return "checkout_preview";
  }


  function actionIndex () {
    unset( $_SESSION['_SHOP_order']);
    return "checkout_preview";
  }

  function actionSubmit () {
    return $this->_submit();
  }

  protected function  _submit() {
    $myorder = Order::DecodeSecureCode($this->getsecurecode(), true);
    if(is_numeric($myorder)) {
      ShopDB::dblogging("submit error ($myorder).");
      return;
    }
    $myorder->lock();
    $this->setordervalues($myorder);
    $hand= $myorder->handling;
    $pm_return = $hand->on_submit($myorder);
    if (empty($pm_return)) {
      return false;
    } elseif (is_string($pm_return)) {
      $this->__Order->obj = $myorder;
      $this->smarty->assign('confirmtext', $pm_return);
      return "checkout_confirm";
    } else  {
      $this->smarty->assign('pm_return', $pm_return);
      if(!$pm_return['approved']){
       	Order::delete($myorder->order_id,'payment_not_approved' );
      }
      unset( $_SESSION['_SHOP_order']);
      return "checkout_result";
    }
  }


  function actionPrint () {
    $myorder = Order::DecodeSecureCode($this->getsecurecode(), true);
    if(is_numeric($myorder)) {
      ShopDB::dblogging("Print error ($myorder).");
      return;
    }
    $mode = (int)$_REQUEST['mode'];
    If (!$mode) $mode =2;
    Order::printOrder($myorder->order_id, '', 'stream', false, $mode );
    return;
  }

  function actionAccept () {
    $myorder = Order::DecodeSecureCode($this->getsecurecode(), true);
    if(is_numeric($myorder)) {
      echo "accept error ($myorder).\n";
      return;
    }
    $myorder->lock();

    $hand=$myorder->handling;
    $this->setordervalues($myorder);

    $pm_return = $hand->on_return($myorder, true);
    If (!$pm_return['approved']) {
       Order::delete($myorder->order_id,'payment_not_approved' );
       $pm_return['response'] .= "<div class='error'>".con('orderdeleted')."</div>";

    }
    $this->assign('pm_return',$pm_return);
    unset( $_SESSION['_SHOP_order']);
    return "checkout_result";
  }

  function actionPosCancel () {
 		$this->__MyCart->destroy_f(); // destroy cart
    $myorder = Order::DecodeSecureCode($this->getsecurecode(), true);
    if ($myorder) {
       Order::delete($myorder->order_id,'pos_manual_canceled' );
    }
  }

  function actionCancel () {
    $myorder = Order::DecodeSecureCode($this->getsecurecode(), true);
    if(is_numeric($myorder)) {
      echo "Cancel error ($myorder).\n";
      return;
    }
    $myorder->lock();

    $this->setordervalues($myorder);
    $hand=$myorder->handling;
    Order::delete($myorder->order_id,'order_canceled_will_paying' );
    $pm_return = $hand->on_return($myorder, false );
    $pm_return['response'] .= "<div class='error'>".con('orderdeleted')."</div>";
    $this->assign('pm_return',$pm_return);
    unset( $_SESSION['_SHOP_order']);
    return "checkout_result";
  }

	function actionNotify ($type="sor") {
		if($type == "sor"){
      $myorder = Order::DecodeSecureCode($this->getsecurecode(), true);
      if(is_numeric($myorder)) {
		   		header('HTTP/1.1 502 Action not allowed', true, 502);
		   		ShopDB::dblogging("notify error ($test): $myorder->order_id\n". print_r($myorder, true));
		   		return;
			}
      $myorder->lock();
			ShopDB::dblogging("notify  ($test): $myorder->order_id.\n");

			$hand= $myorder->handling;
			$hand->on_notify($myorder);
		}elseif($type == "cbr"){
			$hand = Handling::decodeEPHCallback($this->getsecurecode($type), true);
			if($hand == null){
				header('HTTP/1.1 502 Action not allowed', true, 502);
				ShopDB::dblogging("notify error : ($hand)\n". print_r($hand, true));
				return;
			}
			$order = null;
			$hand->on_notify($order);
		}
 	}

  function actionPayment (){
    $myorder = Order::DecodeSecureCode($this->getsecurecode(), true);
    if(is_numeric($myorder)) {
      echo "Cancel error ($myorder).\n";
      return;
    }
    return $this->_payment($myorder);
  }
  /**
   * Checkout::paymentAction()
   *
   * For the recheckout methods with show just the payment method that you would see from
   * just checking out.
   *
   * @param object $order
   * @return boolean
   */
  protected function _payment($orderInput){

    if(!$orderInput){
      addWarning('invalid_order');
      return false;
    }
    if(is_numeric($orderInput)){
      $orderInput = Order::load($orderInput, true);
      if(!is_object($orderInput)){ addWarning('invalid_order'); return false;}
    }
    $orderInput->lock();

    $this->setordervalues($orderInput); //assign order vars
    $hand = $orderInput->handling; // get the payment handling object
    $confirmtext = $hand->on_confirm($orderInput); // get the payment button/method...

    if (is_array($confirmtext)) {
      $this->assign('pm_return',$confirmtext);
      if(!$confirmtext['approved']) {
        $orderInput->delete($orderInput->order_id,'payment_not_approved' );
      }
  		unset( $_SESSION['_SHOP_order']);
      return "checkout_result";
    } else {
      if ($hand->is_eph()) {
        $_SESSION['_SHOP_order'] = true;
 			}
      $this->assign('confirmtext', $confirmtext);
   		return "checkout_confirm";
    }
  }


  function actionReserve ($origin='www',$user_id=null) {
    return $this->_reserve($origin, $user_id);
  }

  protected function _reserve($origin='www',$user_id=null) {
    $myorder = $this->__Order->make_f(1, $origin, NULL, $user_id);
    $myorder->lock();
    if (!$myorder) {
      return "checkout_preview";
    } else {
      $this->setordervalues($myorder);
      $this->__MyCart->destroy_f();
      $this->assign('pm_return',array('approved'=>TRUE));
      return "checkout_result";
    }
  }


  function actionConfirm ( $origin="www", $user_id=0, $no_fee=0) {
    return $this->_confirm($origin, $user_id, $no_fee);
  }

  protected function _confirm($origin="www",$user_id=0, $no_fee=0, $no_cost=0) {
    if (!isset($_SESSION['_SHOP_order'])) {
      $myorder = $this->__Order->make_f($_POST['handling_id'], $origin, $no_cost, $user_id, $no_fee);
 	  } else {
      $myorder = Order::DecodeSecureCode($this->getsecurecode(), true);
      if(is_numeric($myorder)) {
        echo "Cancel error ($myorder).\n";
        return;
      }
    }
    if (!$myorder) {
      addwarning('order_not_found_or_created');
      return "checkout_preview";
    } else {
      $myorder->lock(); //Lock created order
      $this->setordervalues($myorder); //assign order vars
      $this->__MyCart->destroy_f(); // destroy cart
      if(!$myorder->handling){
        $myorder->handling = Handling::load($myorder->order_handling_id);
      }
    	$hand = $myorder->handling; // get the payment handling object
      $confirmtext = $hand->on_confirm($myorder); // get the payment button/method...

   //    ShopDB::commit('UnLock Created Order'); // Dont commit within the processes  will be done at the end.
      if (is_array($confirmtext)) {
    	 $this->assign('pm_return',$confirmtext);
        if(!$confirmtext['approved']) {
          $myorder->delete($myorder->order_id,'payment_not_approved' );
        }
     		unset( $_SESSION['_SHOP_order']);
      	return "checkout_result";
      } else {
 			  if ($hand->is_eph()) {
    		  $_SESSION['_SHOP_order'] = true;
   			}
    		$this->__Order->obj = $myorder;
      	$this->assign('confirmtext', $confirmtext);
   			return "checkout_confirm";
      }
    }
  }

	function getsecurecode($type='sor') {
		if (isset($_POST[$type])) {
     		$return = urldecode( $_POST[$type]);
	 	} elseif (isset($_GET[$type])) {
     	$return = $_GET[$type];
    } elseif (strlen( $_SERVER["PATH_INFO"])>1) {
     	$return = substr($action, 1);
    } else {
     	print_r($_REQUEST); Print_r($_SERVER);
     	$return ='';
    }
    //  echo $return;
    	return $return;
  }

  /**
	 * @name SetOrderValues
	 *
	 * Used to set the order values using the smarty assign methods, which can then be used
	 * by the plugable payments.
	 *
	 * @author Niels
	 * @since 1.0
	 * @uses Smarty, Smarty_Order
	 * @param aorder : Order Object [required]
	 * @return null loads the values to smarty vars
	 */
  function setordervalues($aOrder){
    $this->__Order->obj = $aOrder;

    if (!is_a  ( $aOrder, 'Order')) return;
    if (is_array($aOrder->places)) {
      foreach($aorder->places as $ticket){
    		$seats[$ticket->id]=TRUE;
      }
    } else {
      print_r($aOrder);
    }
    $this->smarty->assign('order_success',true);
    $this->smarty->assign('order_id',$aOrder->order_id);
    $this->smarty->assign('order_fee',$aOrder->order_fee);
    $this->smarty->assign('order_total_price',$aOrder->order_total_price);
    $this->smarty->assign('order_partial_price',$aOrder->order_partial_price);
    $this->smarty->assign('order_discount_price',$aOrder->order_discount_price);
    $this->smarty->assign('order_tickets_nr',$aOrder->size());
    $this->smarty->assign('order_shipment_mode',$aOrder->order_shipment_mode);
    $this->smarty->assign('order_payment_mode',$aOrder->order_payment_mode);

    $this->smarty->assign('shop_handling', (array)$aOrder->handling);
    $this->smarty->assign('shop_order', (array)$aOrder);

    $this->smarty->assign('order_seats_id',$seats);
  }
}
//session_write_close();
?>