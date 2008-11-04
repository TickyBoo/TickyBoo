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

require_once("classes/MyCart.php");
require_once ("classes/Seat.php");
require_once ("page_classes/CartView.php");
require_once ("classes/Event.php");
require_once ("classes/Category.php");


class CartUpdate {

  var $event_id;
  var $category_id;
  var $places;
  var $reserved;
  var $unnum;

  function CartUpdate ($event_id,$category_id,$places,$mode='mode_web',$reserved){
    $this->event_id=$event_id;
    $this->category_id=$category_id;
    $this->places=$places;
    $this->mode=$mode;
	$this->reserved=$reserved;
  }

  function check (){
  	
  	// Loads event details
    if(!$event=Event::load($this->event_id)){
      return FALSE;
    } 
    // Loads cat details
    if(!$category=Category::load($this->category_id)){
      return FALSE;
    }

    //checks the seating numbering.
    if($category->category_numbering=='none'){
      if(!($this->places>0)){
        $this->error=places_empty; 
        return FALSE;
      }
      $newp = $this->places; 
    }else if($category->category_numbering=='rows' or 
             $category->category_numbering=='both' or
	     $category->category_numbering=='seat')
    {
      if(!is_array($this->places) or empty($this->places)){
        $this->error=places_empty; 
        return FALSE;
      }
      $newp = count($this->places); 
    }else{
      user_error("unknown: category_numbering '{$category->category_numbering}' category_id '{$category->category_id}'");
      return FALSE;
    }  

    $max=$event->event_order_limit; 
    //Checks if event is close. To see if alt payment should be used.
    
	//$event->use_alt=$update->check_event($event->event_date); // moved to MyCart.php
    

    $cart=$_SESSION['cart'];

    //echo "max $max, mode {$this->mode}, has ".$cart->total_places($this->event_id).", newp $newp";
    if($this->mode=='mode_web' and $max){
      if(isset($cart)){

        $has = $cart->total_places($this->event_id);
        if(($has+$newp)>$max){
          $this->error = event_order_limit_exceeded;
	  return FALSE;
	}
      }else if($newp>$max){
        $this->error = event_order_limit_exceeded;
        return FALSE;
      }
    }
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////      
    if($places_id=Seat::reservate(session_id(),$this->event_id,$this->category_id,$this->places,$category->category_numbering,$this->reserved)){
	
	  //if cart empty create new cart
      if(!isset($cart)){
        $cart=new Cart();
      } 
  
      // add place in cart.
      $res=$cart->add_place($this->event_id,$this->category_id,$places_id);

    //  if($this->mode=='mode_kasse'){
        $cart->load_info();
    //  }

      $_SESSION['cart']=$cart;  
      $this->cart=&$cart;

      $this->status=TRUE;



      return $res;
    }else{
      global $_SHOP;

      if(is_array($_SHOP->place_error)){
        switch($_SHOP->place_error['errno']){
	  case PLACE_ERR_OCCUPIED: 
	    $this->error=places_occupied; 
	    break;
	  case PLACE_ERR_TOOMUCH: 
	    $this->error=places_toomuch; 
	    if($this->mode=='mode_kasse'){
	      $this->error.=places_remains.": ".$_SHOP->place_error['remains'];
	    }
	    break;
	    
	  case PLACE_ERR_INTERNAL: 
	  default:  
	    $this->error=internal_error.' ['.$_SHOP->place_error['place'].'] '. $_SHOP->db_error;
	    break;
	}
      }else{
        $this->error=internal_error.' ['.$_SHOP->place_error['errno'].']'. print_r($_SHOP->place_error, true);
      }

      return FALSE;
    }
  } 


  function draw (){
    if($this->status){

      echo "<center><div class='add_cart'>".cart_updated."</div></center><br>";
      
      $this->cart->load_info();
      if($this->mode=='mode_web'){
        CartView::print_cart ($this->cart);
      }else if($this->mode=='mode_kasse'){
        CartView::print_kasse ($this->cart);
      
      }
      echo "<br><center><a class='shop_link' href='kasse.php'>".go_pay."</a><br>
            <a class='shop_link' href='{$_SERVER['PHP_SELF']}'>".back_shop."</a>
            </center>";
    }else{

      echo "<center><div class='error'>{$this->error}</div>
            <div class='not_add_cart'>".cart_not_updated."</div>
	    </center><br>";

//            <div class='not_add_cart'>
//            <a href='{$_SERVER['PHP_SELF']}?event_id={$this->event_id}'>".cart_try_again."</a>
//	    </div>
    }
  }
}
?>