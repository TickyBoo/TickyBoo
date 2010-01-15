<?php

class Checkout {


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
      return "checkout_preview";
    } else {
      Checkout::setordervalues($myorder, $smarty); //assign order vars
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


}


?>