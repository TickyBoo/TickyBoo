<?php
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2009 Christopher Jenkins, Niels, Lou. All rights reserved.
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

$fond = null;

if($_REQUEST['pos']) {
  require_once ( 'pos_template.php');
} else {
  require_once ( 'template.php');
}


if (!$action) {$action = 'index';}

if (isset($_REQUEST['sor'])) {
	if (is_callable($action.'action') and ($fond = call_user_func_array($action.'action',array($smarty,"sor")))) {
		$smarty->display($fond . '.tpl');
	}
 	exit();
}elseif(isset($_REQUEST['cbr'])){
	if (is_callable($action.'action') and ($fond = call_user_func_array($action.'action',array($smarty,"cbr")))) {
		$smarty->display($fond . '.tpl');
	}
 	exit();
} elseif ($cart->can_checkout_f() or isset($_SESSION['_SHOP_order']) ) { //or isset($_SESSION['order'])
  	if ( !$_REQUEST['pos'] and
         !$user->logged and
		     $action !== 'register' and
      	 $action !== 'login' ) {
    	$smarty->display('user_register.tpl');
    	exit();
	  }
  	if (is_callable($action.'action') and ($fond = call_user_func_array($action.'action',array($smarty)))) {
    	$smarty->display($fond . '.tpl');
  	}
  	exit();
}

if ($action == 'useredit') {
	echo "<script>window.close();</script>";
	echo 'closeme';
} else {
	redirect("index.php?action=cart_view",403);
}
die();

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
  	 * @param smarty : Smarty Object [required]
  	 * @return null loads the values to smarty vars
  	 */
  	function setordervalues($aorder, $smarty){
    	if (!is_object($aorder)) exit;
    	if (isset($aorder) and isset($aorder->places)) {
      		foreach($aorder->places as $ticket){
        		$seats[$ticket->id]=TRUE;
      		}
    	}
	    $smarty->assign('order_success',true);
	    $smarty->assign('order_id',$aorder->order_id);
	    $smarty->assign('order_fee',$aorder->order_fee);
	    $smarty->assign('order_total_price',$aorder->order_total_price);
	    $smarty->assign('order_partial_price',$aorder->order_partial_price);
	    $smarty->assign('order_tickets_nr',$aorder->size());
	    $smarty->assign('order_shipment_mode',$aorder->order_shipment_mode);
	    $smarty->assign('order_payment_mode',$aorder->order_payment_mode);
	
	    $smarty->assign('shop_handling', (array)$aorder->order_handling);
	    $smarty->assign('shop_order', (array)$aorder);
	
	    $smarty->assign('order_seats_id',$seats);
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
        echo "
          <script>
             window.opener.location.href = window.opener.location.href;
             window.close();
          </script>";
        die('The End');
      }
      $smarty->assign('user_errors', $errors);
      $smarty->assign('user_data',   $_POST);
    } else {
      $smarty->assign('user_data',   $user->asarray());
    }
    return "user_update";
  }

  Function registerAction ($smarty){
    global $user;
    $type = is($_POST['ismember'],false);
    $user_id = $user->register_f($type, $_POST, $errors, 0, 'user_nospam');
    If (!$user_id ) {
      $smarty->assign('user_data',   $_POST);
      $smarty->assign('reg_type',    $type);
      $smarty->assign('user_errors', $errors);
      return "user_register";
    } else
      $smarty->assign('newuser_id', $user_id);
    return "checkout_preview";
  }


  function indexaction($smarty) {
    unset( $_SESSION['_SHOP_order']);
    return "checkout_preview";
  }

  function reserveaction($smarty) {
    global $order;
    $myorder = $order->make_f(1,"www");
    If (!$myorder) {
      return "checkout_preview";
    } else {
      setordervalues($myorder, $smarty);
      $Mycart->destroy();
      $smarty->assign('pm_return',array('approved'=>TRUE));
      return "checkout_result";
    }
  }
  
  function PosConfimAction($smarty) {
    global $user;
    if ($_POST['user_id']==-2) {
        return "order";
    } elseif ($_POST['user_id']==-1) {
       $user_id = $_SESSION['_SHOP_POS_USER']['user_id'];
       $user->load_f($user_id);
    } elseif ($_POST['user_id']==0) {
      $user_id = $user->register_f($type, $_POST, $errors, 0, 'user_nospam');
      If (!$user_id ) {
        $smarty->assign('user_data',   $_POST);
        $smarty->assign('user_errors', $errors);
        return "order";
      } else {
        $smarty->assign('newuser_id', $user_id);
      }
    } else {
       $user_id = $_POST['user_id'];
    }
    $return = $this->confirmaction($smarty,'pos', $user_id, $_POST['no_fee']  );
    return ($return == 'checkout_preview')?'order':$return;
  }
  
	function confirmaction($smarty,$origin="www",$user_id=0, $no_fee=0) {
  	global $order, $cart;
  	if (!isset($_SESSION['_SHOP_order'])) {
    	$myorder = $order->make_f($_POST['handling_id'], $origin,$user_id, $no_fee);
  	} else {
		  $myorder = $_SESSION['_SHOP_order'];
	  }
	
  	if (!$myorder) {
    		$smarty->assign('order_error', $order->error);
    		return "checkout_preview";
  	} else {
    		setordervalues($myorder, $smarty); //assign order vars
    		$cart->destroy_f(); // destroy cart
    		$hand = $myorder->order_handling; // get the payment handling object
    		$confirmtext = $hand->on_confirm($myorder); // get the payment button/method... 

    		if (is_array($confirmtext)) {

      		$smarty->assign('pm_return',$confirmtext);
      		if(!$confirmtext['approved']) {
         			$myorder->order_delete($myorder->order_id,'payment_not_approved' );
          }
     			unset( $_SESSION['_SHOP_order']);
      		return "checkout_result";
    		} else {
    			if ($hand->is_eph()) {
      			$_SESSION['_SHOP_order'] = $myorder;
    			}
    			$order->obj = $myorder;
      		$smarty->assign('confirmtext', $confirmtext);
    			return "checkout_confirm";
  		}
		}
	}

  function  submitaction($smarty) {
    $myorder = is($_SESSION['_SHOP_order'],null);
    $test = Order::DecodeSecureCode($myorder, getsecurecode());
    if($test < 1) {
  //    header('HTTP/1.1 404 '.con('OrderNotFound'), true, 404);
      ShopDB::dblogging("submit error ($test): $myorder->order_id\n". print_r($myorder, true));

      unset( $_SESSION['_SHOP_order']);
      return;
    }
    setordervalues($myorder, $smarty);
    $hand= $myorder->order_handling;
    $pm_return = $hand->on_submit($myorder,$errors);
    $smarty->assign('errors', $errors);
    if (empty($pm_return)) {
      return '';
    } elseif (is_string($pm_return)) {
      $order->obj = $myorder;
      $smarty->assign('confirmtext', $pm_return);
      return "checkout_confirm";
    } else  {
      $smarty->assign('pm_return',$pm_return);
      if(!$pm_return['approved'])
           			$myorder->order_delete($myorder->order_id,'payment_not_approved' );
      unset( $_SESSION['_SHOP_order']);
      return "checkout_result";
    }
  }

  function  printaction($smarty) {
    Global $order;
    $myorder = is($_SESSION['_SHOP_order'],null);
    $test = Order::DecodeSecureCode($myorder, getsecurecode());
    if($test < 1) {
      header('HTTP/1.1 502 '.con('OrderNotFound'), true, 502);
      ShopDB::dblogging("print error ($test): $myorder->order_id\n". print_r($myorder, true));
      echo "print error $test" ; print_r($myorder);
      unset( $_SESSION['_SHOP_order']);
      return;
    }

    Order::print_order($myorder->order_id, '', 'stream', false, 2 );
    return;
  }

  function acceptaction($smarty) {
    $myorder = is($_SESSION['_SHOP_order'],nil);
    $test = Order::DecodeSecureCode($myorder, getsecurecode());
    if($test < 1) {
      echo "accept error ($test): $myorder->order_id\n". print_r($myorder, true);
      //header('HTTP/1.1 502 '.con('OrderNotFound'), true, 502);
      ShopDB::dblogging("accept error ($test): $myorder->order_id\n". print_r($myorder, true));
      unset( $_SESSION['_SHOP_order']);
      return;
    }
 //   echo "accept ok ($test): $myorder->order_id\n". print_r($myorder, true);
    $hand=$myorder->order_handling;
    setordervalues($myorder, $smarty);

    $pm_return = $hand->on_return($myorder, true);
    $smarty->assign('pm_return',$pm_return);
    If (!$pm_return['approved']) {
       $myorder->order_delete($myorder->order_id,'payment_not_approved' );
       $pm_return['response'] .= "<div class='error'>".con('orderdeleted')."</div>";
       
    }
    unset( $_SESSION['_SHOP_order']);
    return "checkout_result";
  }

  function  cancelaction($smarty) {
    $myorder = is($_SESSION['_SHOP_order'],null);
    $test = Order::DecodeSecureCode($myorder, getsecurecode());
    if($test < 1) {
      header('HTTP/1.1 502 '.con('OrderNotFound'), true, 502);
      ShopDB::dblogging("cancel error ($test): $myorder->order_id\n". print_r($myorder, true));
      unset( $_SESSION['_SHOP_order']);
      return;
    }
    setordervalues($myorder, $smarty);
    $hand=$myorder->order_handling;
    $myorder->order_delete($myorder->order_id,'order_canceled_will_paying' );
    $pm_return = $hand->on_return($myorder, false );
    $pm_return['response'] .= "<div class='error'>".con('orderdeleted')."</div>";
    $smarty->assign('pm_return',$pm_return);
    unset( $_SESSION['_SHOP_order']);
    return "checkout_result";
  }

	function  notifyaction($smarty, $type="sor") {
		if($type == "sor"){
			$myorder = is($_SESSION['_SHOP_order'], null);
			$test = Order::DecodeSecureCode($myorder, getsecurecode($type), true);
			if($test < 1) {
		   		header('HTTP/1.1 502 Action not allowed', true, 502);
		   		ShopDB::dblogging("notify error ($test): $myorder->order_id\n". print_r($myorder, true));
		   		return;
			}
			ShopDB::dblogging("notify action ($test): $myorder->order_id.\n");
			$hand=$myorder->order_handling;
			$hand->on_notify($myorder);
		}elseif($type == "cbr"){
			require_once('classes/Handling.php');
			$hand = Handling::decodeEPHCallback(getsecurecode($type), true);
			if($hand == null){
				header('HTTP/1.1 502 Action not allowed', true, 502);
				ShopDB::dblogging("notify error : ($hand)\n". print_r($hand, true));
				return;
			}
			$order = null;
			$hand->on_notify($order);
		}
  	}
//session_write_close();
?>