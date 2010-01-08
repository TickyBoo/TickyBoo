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


if (!defined('ft_check')) {die('System intrusion ');}
require_once("classes/class.mycart.php");

class MyCart_Smarty {

  function MyCart_Smarty (&$smarty){
    $smarty->register_object("cart",$this,null,true,array("items"));
    $smarty->assign_by_ref("cart",$this);
  }


  function is_empty_f () {
    $cart=$_SESSION['_SMART_cart'];
    return !isset($cart) or $cart->is_empty();
  }

  function is_empty ($params,&$smarty) {
    return $this->is_empty_f();
  }



  function total_seats_f ($event_id,$category_id,$only_valid){
    $cart=$_SESSION['_SMART_cart'];

    if($cart){
      return $cart->total_places($event_id,$category_id,$only_valid);
    }else{
      return 0;
    }
  }

  function total_seats ($params,&$smarty){
    return $this->total_seats_f($params['event_id'],$params['category_id'],$params['only_valid']);
  }


  function add_item ($params, &$smarty){
    $this->add_item_f($params['event_id'],$params['category_id'],$params['seats'],$params['mode']);
  }

	/**
	* @name add item function
	*
	* Used to add seats to the cart. Will check if the selected seats are free.
	*
	* @param event_id : required
	* @param category_id : required
	* @param seats : int[] (array) or int : required
	* @param mode : where the order is being made options('mode_web'|'mode_kasse')
	* @param reserved : set to true if you want to reserve only.
	* @param discount_id
	* @return boolean : will return true if that many seats are avalible.
	*/
	function add_item_f ($event_id, $category_id, $seats, $mode='mode_web', $reserved=false, $discount_id=0,$force=false){
    if(!$mode){
    	$mode='mode_web';
		}
    $res=$this->CartCheck($event_id,$category_id,$seats,$mode,$reserved,$discount_id,$force);
    if($res){
    	return $res;
    }else{
    	return FALSE;
    }
	}

  function remove_item ($params, &$smarty){
    $this->remove_item_f($params['event_id'],$params['category_id'],$params['item_id']);
  }

  function remove_item_f ($event_id, $cat_id, $item_id){
    if($cart=$_SESSION['_SMART_cart']){

      if($places=$cart->remove_place($event_id,$cat_id,$item_id)){
        Seat::free(session_id(),$event_id,$cat_id,$places);
      }

      $_SESSION['_SMART_cart']=$cart;
    }
  }

  function order_to_cart($order_id,$mode='mode_web'){
    if(is_numeric($order_id) && $order_id > 0){
      $order = Order::load($order_id);
      $tickets = $order->loadTickets();
      //print_r($order);
      //print_r($tickets);
    }
    //We need eventid, catid, seats, mode,reserved, discount.
    if($order && $tickets){
      if($order->order_payment_status <> 'none'){
        addWarning("Order Currently being paid for");
      }
      foreach($tickets as $ticket){
        $res[] = $this->add_item_f($ticket['event_id'],$ticket['category_id'], array($ticket['seat_id']),$mode,false,$ticket['discount_id'],true);
      }
      print_r($res);
    }

  }

  function total_price ($params, &$smarty){
    return $this->total_price_f();
  }

  function total_price_f (){
    if($cart=$_SESSION['_SMART_cart']){
      return $cart->total_price();
    }
  }

  	function use_alt ($params, &$smarty){
    	return $this->use_alt_f();
  	}

  	function use_alt_f (){
    	if($cart=$_SESSION['_SMART_cart']){
      		return $cart->use_alt();
    	}
  	}
  	function min_date_f (){
		if($cart=$_SESSION['_SMART_cart']){
      		return $cart->min_date();
    	}
  	}

  function can_checkout ($params, &$smarty){
    return $this->can_checkout_f();
  }

  function can_checkout_f (){
    if($cart=$_SESSION['_SMART_cart']){
      return $cart->can_checkout();
    }
  }

  function overview ($params, &$smarty){
    return $this->overview_f();
  }

  function overview_f (){
    if($cart=$_SESSION['_SMART_cart']){
      return $cart->overview();
    }
  }

  function items ($params, $content, &$smarty, &$repeat){
    if($repeat){
      $cart=$_SESSION['_SMART_cart'];

      if(!$cart or $cart->is_empty()){
        $repeat=FALSE;
        return;
      }else{
        $cart->load_info();
      }

      $this->cart_list=array();
      $this->cart_index=0;

      $cart->iterate(array(&$this,'_pre_items'),$this->cart_list);

    }

    if($cart_row=&$this->cart_list[$this->cart_index++]){
      $smarty->assign_by_ref("event_item",$cart_row[0]);


      $smarty->assign_by_ref("category_item",$cart_row[1]);



      $seat_item=$cart_row[2];

      $smarty->assign_by_ref("seat_item",$seat_item);
      $smarty->assign("seat_item_id",$seat_item->id);
      $smarty->assign("seats_id",$seat_item->places_id);
      $smarty->assign("seats_nr",$seat_item->places_nr);

      $cat= $cart_row[1];
      if($cat->cat_numbering=='rows'){
        $rcount=array();
        foreach($seat_item->places_nr as $places_nr){
          $rcount[$places_nr[0]]++;
	}
        $smarty->assign("seat_item_rows_count",$rcount);
      }

      $repeat=TRUE;

    }else{
      $repeat=FALSE;
    }

    return $content;
  }

  function _pre_items (&$event_item,&$cat_item,&$place_item,&$data){
    $data[]=array($event_item,$cat_item,$place_item);
  }


  function destroy_f (){
    unset($_SESSION['_SMART_cart']);
  }

  function destroy ($params,&$smarty){
    unset($_SESSION['_SMART_cart']);
  }

  function set_discounts ($params,&$smarty){

    $this->set_discounts_f($params['event_id'],$params['category_id'],$params['item_id'],$params['discounts']);
  }

  function set_discounts_f ($event_id,$category_id,$item_id,$discounts){
    if(!$cart=$_SESSION['_SMART_cart']){return;}

    foreach($discounts as $disc_id){
      if($disc_id>0){
        if(!isset($dcache[$disc_id])){
          $dcache[$disc_id]=Discount::load($disc_id);
        }
        $discs[]=$dcache[$disc_id];
	      $has=1;
      }else{
        $discs[]=0;
      }
    }

    if($has){
      if($cart->set_discounts($event_id,$category_id,$item_id,$discs)){
        $_SESSION['_SMART_cart']=$cart;
	      return TRUE;
      }
    }

  }


  /**
   * MyCart_Smarty::CartCheck()
   *
   * @param mixed $event_id
   * @param mixed $category_id
   * @param mixed $places
   * @param string $mode
   * @param mixed $reserved
   * @param integer $discount_id
   * @param bool $force - Used to force current orders with thouse seats to be added to the cart. (Will currently only work on reservations)
   * @return
   */
  function CartCheck ($event_id,$category_id,$places,$mode='mode_web',$reserved,$discount_id = 0, $force=false){
  	// Loads event details
    if(!$event=Event::load($event_id)){
      addWarning('event_order_limit_exceeded');
      return FALSE;
    }
    // Loads cat details
    if(!$category_numbering = PlaceMapCategory::getCategoryNumbering($category_id)){
      addWarning('error_missingcategorytype');
      return FALSE;
    }
    //Load Discount if not 0.
    if($discount_id > 0){
		  $discount = Discount::load($discount_id);
		  if(!$discount){
        addWarning('error_discountnotfound');
			 return false;
		  }
	 }

    //checks the seating numbering.
    if($category_numbering=='none'){
      if(!($places>0)){
        addWarning('places_empty');
        return FALSE;
      }
      $newp = $this->places;
    }else if($category_numbering=='rows' or
             $category_numbering=='both' or
	           $category_numbering=='seat') {
      if(!is_array($places) or empty($places)){
        addWarning('places_empty');
        return FALSE;
      }
      $newp = count($places);
    }else{
      addWarning("unknown: category_numbering '{$category_numbering}' category_id '{$category_id}'");
      return FALSE;
    }


    $max=$event->event_order_limit;

    $cart=$_SESSION['_SMART_cart'];

    if($mode=='mode_web' and $max){
      if(isset($cart)){

        $has = $cart->total_places($this->event_id);
        if(($has+$newp)>$max){
          addWarning('event_order_limit_exceeded');
      	  return FALSE;
      	}
      }else if($newp>$max){
        addWarning('event_order_limit_exceeded');
        return FALSE;
      }
    }

   // print_r($places);
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if($places_id=Seat::reservate(session_id(), $event_id, $category_id, $places, $category_numbering, $reserved, $force)){
	  //if cart empty create new cart
      if(!isset($cart)){
        $cart = new Cart();
      }
      // add place in cart.
      $res=$cart->add_place($event_id, $category_id, $places_id);
      If ($discount_id >0) {
        $discounts = array_fill(0,count($places_id),$discount);
        $res->set_discounts(0,0,0,$discounts);
      }
      $cart->load_info();

      $_SESSION['_SMART_cart']=$cart;
      return $res;
    }
  }

}

?>