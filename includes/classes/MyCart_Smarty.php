<?php
/*
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 * Copyright (C) 2007-2008 Christopher Jenkins. All rights reserved.
 *
 * Original Design:
 *	phpMyTicket - ticket reservation system
 * 	Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of fusionTicket.
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
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@noctem.co.uk if any conditions of this licencing isn't 
 * clear to you.
 
 */

require_once("classes/MyCart.php");

class MyCart_Smarty {
  var $error='';
  
  function MyCart_Smarty (&$smarty){
    $smarty->register_object("cart",$this,null,true,array("items"));
    $smarty->assign_by_ref("cart",$this);
    
  }
  
  
  function is_empty_f () {
    $cart=$_SESSION['cart'];
    return !isset($cart) or $cart->is_empty();
  }

  function is_empty ($params,&$smarty) {
    return $this->is_empty_f();
  }



  function total_seats_f ($event_id,$category_id,$only_valid){
    $cart=$_SESSION['cart'];
    
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
    if (!$this->add_item_f($params['event_id'],$params['category_id'],$params['seats'],$params['mode'])){
      //echo "smarty assign ".$this->up->error;
      $smarty->assign("cart_error",$this->up->error);
    }
  }

  function add_item_f ($event_id,$category_id,$seats,$mode='mode_web',$reserved=false){
    require_once("page_classes/CartUpdate.php");

    if(!$mode){
      $mode='mode_web';
    }

    $up=new CartUpdate($event_id,$category_id,$seats,$mode,$reserved);
    $res=$up->check();
    $this->up=$up;
    if($res){
      return $res;
    }else{
      $this->error = $this->up->error;
      return FALSE;
    }
  }


  function remove_item ($params, &$smarty){
    $this->remove_item_f($params['event_id'],$params['category_id'],$params['item_id']);
  }

  function remove_item_f ($event_id, $cat_id, $item_id){
    if($cart=$_SESSION['cart']){

      if($places=$cart->remove_place($event_id,$cat_id,$item_id)){
        require_once('classes/Seat.php');
        Seat::free(session_id(),$event_id,$cat_id,$places);
      }
      
      $_SESSION['cart']=$cart;
    }  
  }
  
  function total_price ($params, &$smarty){
    return $this->total_price_f();
  }

  function total_price_f (){
    if($cart=$_SESSION['cart']){
      return $cart->total_price();
    }
  }
  
  	function use_alt ($params, &$smarty){
    	return $this->use_alt_f();
  	}

  	function use_alt_f (){
    	if($cart=$_SESSION['cart']){
      		return $cart->use_alt();
    	}
  	}
  	function min_date_f (){
		if($cart=$_SESSION['cart']){
      		return $cart->min_date();
    	}
  	}
  
  function can_checkout ($params, &$smarty){
    return $this->can_checkout_f();
  }

  function can_checkout_f (){
    if($cart=$_SESSION['cart']){
      return $cart->can_checkout();
    }
  }

  function overview ($params, &$smarty){
    return $this->overview_f();
  }

  function overview_f (){
    if($cart=$_SESSION['cart']){
      return $cart->overview();
    }
  }


  
  function items ($params, $content, &$smarty, &$repeat){
    if($repeat){
      $cart=$_SESSION['cart'];
      //print_r($cart);

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
    unset($_SESSION['cart']);  
  }

  function destroy ($params,&$smarty){
    unset($_SESSION['cart']);  
  }

  function set_discounts ($params,&$smarty){

    $this->set_discounts_f($params['event_id'],$params['category_id'],$params['item_id'],$params['discounts']);
  }
  
  function set_discounts_f ($event_id,$category_id,$item_id,$discounts){
    if(!$cart=$_SESSION['cart']){return;}

    require_once("classes/Discount.php");
        
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
        $_SESSION['cart']=$cart;
	      return TRUE;
      }
    }  

  }



}

?>