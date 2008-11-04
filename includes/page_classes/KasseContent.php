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

require_once("functions/user_func.php");
require_once("classes/MyCart.php");
require_once("functions/print_func.php");
require_once("functions/payment_func.php");
require_once("page_classes/AUIComponent.php");
require_once("page_classes/CartView.php");
class KasseContent extends AUIComponent {

function draw (){
global $_SHOP;

include_once("check_login.php");

$cart=$_SESSION['cart'];
if(!isset($cart) or !$cart->can_checkout()){
  echo "<center>".cart_empty."<br>";
  echo "<a class='shop_link' href='shop.php'>".back_shop."</a></center>";
  return 0;
}

$user=$_SESSION['SHOP_USER_ID']; 

if(!$user){
  if(isset($_POST["submit_login"])){
    $user=login($_POST["username"],$_POST["password"]);
  }else if(isset($_POST["submit_info"])){
    $user=create_guest($_POST,$err);
    $_SESSION['SHOP_GUEST_ID']=$user; 
    unset($_SESSION['SHOP_USER_ID']); 
  }else if(isset($_POST["submit_register"])){
    if($user=create_member($_POST,$err)){
      $_SESSION['SHOP_USER_ID']=$user;
    }
  }  
}


if($_POST['action']=='pay' and ($user or $_SESSION['SHOP_GUEST_ID'])){
  if($_POST['payment']=='cc'){
    if(check_cc($_POST,$pay_err)){
      if($user){
        $uu = $user;
      }else{
        $uu=$_SESSION['SHOP_GUEST_ID'];
      }
      
//      echo "Pay by card - receive by e-mail<br>";
      require_once("functions/order_func.php");

     $order= cart_to_order($cart,$uu,session_id(),'email'); 
     
     if(!ShopDB::begin()){
       echo "<div class='error'>".reservate_failed."</div>";
       return 0; 
     }
      
     if(!command($order,session_id(),$uu,FALSE)){
        echo "<div class='error'>".reservate_failed."</div>";
	ShopDB::rollback();
	return 0;
      } 
           
      if(!$order_id=$order->save()){
        echo "<div class='error'>".save_failed."</div>";
	ShopDB::rollback();
        return 0; 
      }
      
      ShopDB::commit();
      
      if(!$res=&print_order($order_id)){
	echo "<div class='error'>".pdf_failed." $order_id</div>";
	//return 0;
      }else{ 
      
        $res['data']['pdf_data']=$res['pdf'];


        if(!email_order($res['data'])){
          echo "<div class='error'>".email_failed." $order_id</div>";
	  //return 0;
        }  
      }
      
 
      if($user_data=load_user($uu)){
        echo "<table width='520'><tr><td width='200' align='left'>";
        user_address($user_data,200);
	echo "</td><td align='right'>";
	$this->druck();
	echo"</td></tr></table>";
      }else{
        echo "<div class='error'>".error_loading_user."</div>";
      }
      $cart->load_info();
      echo "";
      $this->print_order($order,$cart);
      echo "<center><a class='shop_link' href='shop.php'>".back_shop."</a></center>";
      unset($_SESSION['cart']);
      unset($cart);
      return 1;
    }else{
      echo "<div class='error'>".cc_error."</div>";
    }    
  }else


  //process order to sent by post

  if($_POST['payment']=='post'){

     //load user or guest
     if($user){
       $uu = $user;
     }else{
       $uu=$_SESSION['SHOP_GUEST_ID'];
     }
      
     require_once("functions/order_func.php");

     
     //compile order (order and tickets) from the shopping cart
     $order= cart_to_order($cart,$uu,session_id(),'post'); 
      
     //begin the transaction 
     if(!ShopDB::begin()){
       echo "<div class='error'>".reservate_failed."</div>";
       return 0; 
     }

     //move places from reserved to ordered state
     if(!command($order,session_id(),$uu)){
        echo "<div class='error'>".reservate_failed."</div>";
        ShopDB::rollback();
	return 0;
      } 
      
      //put the order into database     
      if(!$order_id=$order->save()){
        echo "<div class='error'>".save_failed."</div>";
	ShopDB::rollback();
        return 0; 
      }
      
      //send confirmation email
      if(!email_confirm($order_id)){
        echo "<div class='error'>".email_failed." $order_id</div>";
	ShopDB::rollback();
	return 0;
      }  

      
      //print user address and a little "printr" icon
      if($user_data=load_user($uu)){
        echo "<table width='520'><tr><td width='200' align='left'>";
        user_address($user_data,200);
	
	echo "</td><td align='right'>";
	$this->druck();
	echo"</td></tr></table>";
      }else{
        echo "<div class='error'>".error_loading_user."</div>";
	ShopDB::rollback();
	return 0;
      }

      //commit the transaction      
      ShopDB::commit();

      //print the order
      $cart->load_info();
      echo "<br>";
      $this->print_order($order,$cart);
      echo "<br><center><a class='shop_link' href='shop.php'>".back_shop."</a></center>";

      //delete the cart from the session
      unset($_SESSION['cart']);
      unset($cart);
      
      //notify success
      return 1;

  }else{
    echo "<div class='error'>".pay_error."</div>";
  }  
}



/*if($user or $pay_err){
  if($user){
    $uu = $user;
  }else{
    $uu=$_SESSION['SHOP_GUEST_ID'];
  }

  if($user_data=load_user($uu)){
*/  
if($user){
  if($user_data=load_user($user)){    
    user_address($user_data);
    echo "<br>";
    
    $cart->load_info();
    
    CartView::print_cart($cart,TRUE,TRUE);
    echo "<br>";
    payment_form($_POST,$pay_err,$_SHOP->allow_cc);
  }else{
    echo "<div class='error'>".error_loading_user."</div>";
  }
  
}else{
 login_form();
 user_subscribe($_POST,$err); 
}     
}

function print_order ($order,$cart){
  
  global $_SHOP;
  $order_id=$order->getID();
  echo "<table class='order' cellpadding='5' width='500'>". 
       "<tr><td class='order_big' colspan='4' align='center'>".order_nr." $order_id </td></tr>".
       "<tr><td class='order_title'>".ev_name."</td>".
       "<td class='order_title'>".ev_datum."</td>".
       "<td class='order_title'>".ev_ticket."</td>".
       "<td class='order_title'>".cat_total."</td>".
       "</tr>";
      
  foreach($cart->event_items as $event){     
    foreach($event->cat_items as $cat){
      foreach($cat->place_items as $item_id=>$item){
        if($item->is_expired()){continue;}
        $places_qty=$item->count();
	$etime=formatTime($event->event_time);
	$edate=formatDate($event->event_date);
        echo "<tr>";
        echo "<td class='order_td'>".$event->event_name."</td><td class='view_cart_td'>
        $edate - $etime <br>".$event->event_ort_name."</td>
        <td class='order_td'>$places_qty x ".sprintf("%1.2f",$cat->cat_price)." ".$cat->cat_name;

        if((!$cat->cat_numbering) or $cat->cat_numbering=='both'){
   	  foreach($item->places_nr as $places_nr){
	    echo "<li>".$places_nr[0]."-".$places_nr[1];
	  }
	}else if($cat->cat_numbering=='rows'){
	  $rcount=array();
   	  foreach($item->places_nr as $places_nr){
            $rcount[$places_nr[0]]++;
	  }
	  foreach($rcount as $row=>$pcount){
	    echo "<li>{$pcount} x ".place_row." $row"; 
	  }
	}  


//        echo " ".implode(", ",$item->places_nr);
	
        echo "</td> <td class='order_td'>".sprintf("%1.2f",$item->total_price($cat->cat_price))."</td></tr>";
        
      }
    }
  }   
  echo "<tr><td class='order_ptotal' colspan='3'>".parzial_price."</td>
        <td class='order_ptotal'>".$_SHOP->currency." ".sprintf("%1.2f",$cart->total_price())."</td></tr>";
  echo "<tr><td class='order_shipment' colspan='3'>".shipment_price."</td>
        <td class='order_shipment'>".$_SHOP->currency." ".sprintf("%1.2f",$order->getShipmentPrice())."</td></tr>";
  echo "<tr><td class='order_total' colspan='3'>".order_total."</td>
        <td class='order_total'> ".$_SHOP->currency." ".sprintf("%1.2f",$order->total())."</td></tr>";
  
  echo "</table>";
  
}

function druck (){
   global $_SHOP;
  // echo "<table width='500' border='0'><tr><td align='right'>";
   echo "<a href='javascript:window.print()'><img border='0' src='images/printer.png'></a>";
  // echo "</td></tr></table>";
}
}
?>