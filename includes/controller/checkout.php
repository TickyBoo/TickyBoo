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
$fond = null;

if($_REQUEST['pos']) {
  require_once ( 'pos_template.php');
} else {
  require_once ( 'template.php');
}
require_once ("classes/class.checkout.php");

GLOBAL $_SHOP;

//var_dump($_SHOP);
//print_r($_SERVER);
//echo strtoupper(substr($_SHOP->root_secured, 0, 8)), '<br>';
if ($_SHOP->secure_site) {
  $url = $_SHOP->root_secured.basename($_SERVER['REQUEST_URI']);
  if($_SERVER['SERVER_PORT'] != 443 || $_SERVER['HTTPS'] !== "on") {
   //echo $url;
    header("Location: $url");
    echo "<script>window.location.href='$url';</script>";
    exit;
  }
/*    //remove the www. to stop certificate errors.
  if(("https://".$_SERVER['SERVER_NAME']."/") != ($_SHOP->root_secured)) {
   // header("Location: $url");
   // echo "<script>window.location.href='$url';</script>";
    exit;
  }  */
} else {
  addWarning('This_page_is_not_secure');
}

if (!$action) {$action = 'index';}

if (isset($_REQUEST['sor'])) {
	if (is_callable($action.'action') and ($fond = call_user_func_array($action.'action',array($smarty,"sor")))) {
		$smarty->display($fond . '.tpl');
	}
 	myExit();
}elseif(isset($_REQUEST['cbr'])){
	if (is_callable($action.'action') and ($fond = call_user_func_array($action.'action',array($smarty,"cbr")))) {
		$smarty->display($fond . '.tpl');
	}
 	myExit();
} elseif ($cart->can_checkout_f() or isset($_SESSION['_SHOP_order']) ) { //or isset($_SESSION['order'])
  if ( !$_REQUEST['pos'] and
         !$user->logged and
		     $action !== 'register' and
      	 $action !== 'login' ) {
    $smarty->display('user_register.tpl');
 	} elseif (is_callable($action.'action') and ($fond = call_user_func_array($action.'action',array($smarty)))) {
 	  $smarty->display($fond . '.tpl');
  }
  myExit();
}

if ($action == 'useredit') {
  $array = array('status'=>false,'msg'=>con('checkout_expired'));
  echo json_encode($array);
} elseif(!$_REQUEST['pos']) {
	redirect("index.php?action=cart_view",403);
} else {
  addWarning('noting_checkout');
}

myExit();

  function myExit(){
    orphanCheck();
    trace("End of checkout \n");
    exit;
  }


  Function loginAction ($smarty){
    global $user;
    if (!$user->logged) {
  	  If (! $user->login_f($_POST['username'], $_POST['password'], $errors)) {
  	    $smarty->assign('login_error',$errors);
  	    return "user_register";
      }
    }
    return "checkout_preview";
  }

  Function usereditAction ($smarty){
    global $user;
    $smarty->assign('usekasse',true);
    if (isset($_POST['submit_update'])) {
      if ($user->update_f($_POST, $errors)) {
        $array = array('saved'=>true,'msg'=>con('user_details_saved_successfully'));
        echo json_encode($array);
        myExit();
      }
      $array = array('saved'=>false,'msg'=>printMsg('__Errors__',null,false).printMsg('__Warning__',null,false));
      echo json_encode($array);
      myExit();
    } else {
      $smarty->assign('user_data',   $user->asarray());
    }
    return "user_update";
  }
  
  Function useraddressAction ($smarty){
    global $user;
    $smarty->assign('title',   true);
    $smarty->assign('user_data',   $user->asarray());
    return "user_address";
  }

  /**
   * registerAction()
   *
   * @return String : SmartyPage
   */
  function registerAction ($smarty){
    global $user;

    //if registerasmemeber field is not set, the user doenst want to be a member
    $type = is($_POST['ismember'],false);

    //Try and Register
    $user_id = $user->register_f($type, $_POST, $errors, 0, 'user_nospam');

    //If errors return to user registration.
    if (!$user_id || hasErrors() ) {
      $smarty->assign('user_data',   $_POST);
      $smarty->assign('reg_type',    $type);
      return "user_register";
    } else {
      $smarty->assign('newuser_id', $user_id);
    }
    return "checkout_preview";
  }


  function indexAction($smarty) {
    unset( $_SESSION['_SHOP_order']);
    return "checkout_preview";
  }

  function  submitAction($smarty) {
    return checkout::submitAction($smarty,$myorder );
  }

  function  printAction($smarty) {
    Global $order;
    $myorder = is($_SESSION['_SHOP_order'],null);
    $test = Order::DecodeSecureCode($myorder, checkout::getsecurecode());
    if($test < 1) {
      header('HTTP/1.1 502 '.con('OrderNotFound'), true, 502);
      ShopDB::dblogging("print error ($test): $myorder->order_id\n". print_r($myorder, true));
      echo "print error $test" ; print_r($myorder);
      unset( $_SESSION['_SHOP_order']);
      return;
    }
    $mode = (int)$_REQUEST['mode'];
    If (!$mode) $mode =2;
    Order::printOrder($myorder->order_id, '', 'stream', false, $mode );
    return;
  }

  function acceptAction($smarty) {
    $myorder = is($_SESSION['_SHOP_order'],nil);
    $test = Order::DecodeSecureCode($myorder, checkout::getsecurecode());
    if($test < 1) {
      echo "accept error ($test): $myorder->order_id\n". print_r($myorder, true);
      //header('HTTP/1.1 502 '.con('OrderNotFound'), true, 502);
      ShopDB::dblogging("accept error ($test): $myorder->order_id\n". print_r($myorder, true));
      unset( $_SESSION['_SHOP_order']);
      return;
    }
 //   echo "accept ok ($test): $myorder->order_id\n". print_r($myorder, true);
    $hand=$myorder->order_handling;
    Checkout::setordervalues($myorder, $smarty);

    $pm_return = $hand->on_return($myorder, true);
    $smarty->assign('pm_return',$pm_return);
    If (!$pm_return['approved']) {
       Order::delete($myorder->order_id,'payment_not_approved' );
       $pm_return['response'] .= "<div class='error'>".con('orderdeleted')."</div>";

    }
    unset( $_SESSION['_SHOP_order']);
    return "checkout_result";
  }

  function  PosCancelaction($smarty) {
  	global $cart;

 		$cart->destroy_f(); // destroy cart
    $myorder = is($_SESSION['_SHOP_order'],null);
    if ($myorder) {
       Order::delete($myorder->order_id,'pos_manual_canceled' );
    }
  }

  function  cancelAction($smarty) {
    $myorder = is($_SESSION['_SHOP_order'],null);
    $test = Order::DecodeSecureCode($myorder, checkout::getsecurecode());
    if($test < 1) {
      header('HTTP/1.1 502 '.con('OrderNotFound'), true, 502);
      ShopDB::dblogging("cancel error ($test): $myorder->order_id\n". print_r($myorder, true));
      unset( $_SESSION['_SHOP_order']);
      return;
    }
    setordervalues($myorder, $smarty);
    $hand=$myorder->order_handling;
    Order::delete($myorder->order_id,'order_canceled_will_paying' );
    $pm_return = $hand->on_return($myorder, false );
    $pm_return['response'] .= "<div class='error'>".con('orderdeleted')."</div>";
    $smarty->assign('pm_return',$pm_return);
    unset( $_SESSION['_SHOP_order']);
    return "checkout_result";
  }

	function  notifyAction($smarty, $type="sor") {
		if($type == "sor"){
			$myorder = is($_SESSION['_SHOP_order'], null);
			$test = Order::DecodeSecureCode($myorder, checkout::getsecurecode($type), true);
			if($test < 1) {
		   		header('HTTP/1.1 502 Action not allowed', true, 502);
		   		ShopDB::dblogging("notify error ($test): $myorder->order_id\n". print_r($myorder, true));
		   		return;
			}
			ShopDB::dblogging("notify action ($test): $myorder->order_id.\n");
			$hand=$myorder->order_handling;
			$hand->on_notify($myorder);
		}elseif($type == "cbr"){
			$hand = Handling::decodeEPHCallback(checkout::getsecurecode($type), true);
			if($hand == null){
				header('HTTP/1.1 502 Action not allowed', true, 502);
				ShopDB::dblogging("notify error : ($hand)\n". print_r($hand, true));
				return;
			}
			$order = null;
			$hand->on_notify($order);
		}
 	}

  function paymentAction($smarty){
    $myorder = is($_SESSION['_SHOP_order'], null);
    $test = Order::DecodeSecureCode($myorder, checkout::getsecurecode());
    if($test < 1) {
      header('HTTP/1.1 502 '.con('OrderNotFound'), true, 502);
      ShopDB::dblogging("payment error ($test): $myorder->order_id\n". print_r($myorder, true));
      unset( $_SESSION['_SHOP_order']);
      return;
    }
    return Checkout::paymentAction($myorder, $smarty);
  }

  function reserveAction($smarty,$origin='www',$user_id=null) {
    return Checkout::reserveAction($smarty,$origin,$user_id);
  }

  function confirmAction($smarty,$origin="www",$user_id=0, $no_fee=0) {
    return Checkout::confirmAction($smarty,$origin,$user_id, $no_fee);
  }

//session_write_close();
?>