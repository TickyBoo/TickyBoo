<?php

class Checkout {

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
  	public static function setordervalues($aorder, &$smarty){
      global $order;
	    $order->obj = $aorder;

    	if (!is_a  ( $aorder,'Order')) exit;
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


  public static function reserveAction(&$smarty,$origin='www',$user_id=null) {
    global $order, $cart;
    $myorder = $order->make_f(1, $origin, NULL, $user_id);

    if (!$myorder) {
      return "checkout_preview";
    } else {
      Checkout::setordervalues($myorder, $smarty);
      $cart->destroy_f();
      $smarty->assign('pm_return',array('approved'=>TRUE));
      return "checkout_result";
    }
  }

  public static function confirmAction(&$smarty,$origin="www",$user_id=0, $no_fee=0) {
    global $order, $cart;

    if (!isset($_SESSION['_SHOP_order'])) {
      $myorder = $order->make_f($_POST['handling_id'], $origin, 0, $user_id, $no_fee);
 	  } else {
		  $myorder = $_SESSION['_SHOP_order'];
    }
    if (!$myorder) {
      addwarning('order_not_found_or_created');
      return "checkout_preview";
    } else {
      Checkout::setordervalues($myorder, $smarty); //assign order vars
      $cart->destroy_f(); // destroy cart
    	$hand = $myorder->order_handling; // get the payment handling object
      $confirmtext = $hand->on_confirm($myorder); // get the payment button/method...
      if (is_array($confirmtext)) {
    	 $smarty->assign('pm_return',$confirmtext);
        if(!$confirmtext['approved']) {
          $myorder->delete($myorder->order_id,'payment_not_approved' );
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
  
  /**
   * Checkout::paymentAction()
   * 
   * For the recheckout methods with show just the payment method that you would see from 
   * just checking out.
   * 
   * @param object $order 
   * @param object $smarty
   * @return boolean
   */
  public static function paymentAction($orderInput, $smarty){
    global $order;
    if(!$orderInput){
      addWarning('invalid_order');
      return false;
    }
    if(is_numeric($orderInput)){
      $orderInput = Order::load($orderInput,true);
      if(!is_object($orderInput)){ addWarning('invalid_order'); return false;}
    }
    Checkout::setordervalues($orderInput, $smarty); //assign order vars
    $hand = $orderInput->order_handling; // get the payment handling object
    $confirmtext = $hand->on_confirm($orderInput); // get the payment button/method...
    
    if (is_array($confirmtext)) {
      $smarty->assign('pm_return',$confirmtext);
      if(!$confirmtext['approved']) {
        $orderInput->delete($orderInput->order_id,'payment_not_approved' );
      }
  		unset( $_SESSION['_SHOP_order']);
      return "checkout_result";
    } else {
      if ($hand->is_eph()) {
        $_SESSION['_SHOP_order'] = $orderInput;
 			}
    	$order->obj = $orderInput;
      $smarty->assign('confirmtext', $confirmtext);
   		return "checkout_confirm";
    }
  }
  
  function  submitAction($smarty) {
    $myorder = is($_SESSION['_SHOP_order'],null);
    $test = Order::DecodeSecureCode($myorder, checkout::getsecurecode());
    if($test < 1) {
  //    header('HTTP/1.1 404 '.con('OrderNotFound'), true, 404);
      ShopDB::dblogging("submit error ($test): $myorder->order_id\n". print_r($myorder, true));

      unset( $_SESSION['_SHOP_order']);
      return;
    }
    Checkout::setordervalues($myorder, $smarty);
    $hand= $myorder->order_handling;
    $pm_return = $hand->on_submit($myorder);
    if (empty($pm_return)) {
      return false;
    } elseif (is_string($pm_return)) {
      $order->obj = $myorder;
      $smarty->assign('confirmtext', $pm_return);
      return "checkout_confirm";
    } else  {
      $smarty->assign('pm_return',$pm_return);
      if(!$pm_return['approved']){
       	Order::delete($myorder->order_id,'payment_not_approved' );
      }
      unset( $_SESSION['_SHOP_order']);
      return "checkout_result";
    }
  }
}


?>