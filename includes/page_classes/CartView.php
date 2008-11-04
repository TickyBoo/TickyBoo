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
require_once("functions/datetime_func.php");

class CartView {

function CartView ($cart,$mode='mode_web'){
 $this->cart=$cart;
 $this->mode=$mode;
}
function draw (){ 
if($this->mode=='mode_web'){  
  include_once("check_login.php");
}
$cart=$this->cart;
if(isset($cart) and !$cart->is_empty()){

  $cart->load_info();
  if($this->mode=='mode_web'){  
    $this->print_cart($cart);
  echo "<br><center><a class='shop_link' href='kasse.php'>".go_pay."</a><br>
        <a class='shop_link' href='shop.php'>".back_shop."</a>
        </center>";
  }else if($this->mode=='mode_kasse'){
    $this->print_kasse ($cart);
  echo "<br><center>
        <form action='kasse.php' method=post>
	<input type='submit' name='submit_payment' value='".go_pay."'>
	<input type='submit' name='submit_adress' value='".by_post."'>
	<input type='hidden' name='action' value='order_tickets'>
        </form>
	<br>
        <a class='shop_link' href='shop.php'>".back_shop."</a>
        </center>";
 }
  //print_cart_summary($cart);

  $_SESSION['cart']=$cart;

}else{
  if($this->mode=='mode_web'){  
    echo "<table class='view_cart' cellpadding='5' width='500'>". 
       "<tr><td class='view_cart_big' align='center'>".warenkorb."</td></tr>";
    echo "<tr><td align='center'>".cart_empty."</td></tr></table>\n";
  }else if($this->mode=='mode_kasse'){
    echo "<table class='view_cart' cellpadding='5' width='500'>". 
       "<tr><td class='view_cart_big' align='center'>".kassecontent."</td></tr>";
    echo "<tr><td align='center'>".kasse_empty."</td></tr></table>\n";
  
  } 
  echo "<br><center><a class='shop_link' href='shop.php'>".back_shop."</a>
        </center>";
  
}


}

function print_cart ($cart,$noexpired=FALSE,$noremove=FALSE){
  global $_SHOP;
  
  echo "<table class='view_cart' cellpadding='5' width='500'>". 
       "<tr><td class='view_cart_big' colspan='5' align='center' valign='top'>".warenkorb."</td></tr>".
       "<tr><td class='view_cart_title' valign='top'>".ev_name."</td>".
       "<td class='view_cart_title' valign='top'>".ev_datum."</td>".
       "<td class='view_cart_title' valign='top'>".ev_ticket."</td>".
       "<td class='view_cart_title' align='right' valign='top'>".cat_total."</td>".
       "<td class='view_cart_title' valign='top'>".exp_rm."</td>".
       "</tr>";

      $rowNr=0;
      
  foreach($cart->event_items as $event){     
    foreach($event->cat_items as $cat){
      foreach($cat->place_items as $item_id=>$item){
        if($item->is_expired() and $noexpired){continue;}
        $places_qty=$item->count();
	$etime=formatTime($event->event_time);
	$edate=formatDate($event->event_date);
        echo "<tr class='view_cart_tr$rowNr'>";
        $rowNr = ($rowNr+1)%2;
        echo "<td class='view_cart_td' valign='top'>".$event->event_name."</td>
	<td class='view_cart_td' valign='top'>
        $edate - $etime <br>".$event->event_ort_name."</td>
        <td class='view_cart_td' valign='top'>$places_qty x ".$cat->cat_price." ".$cat->cat_name;

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
		
        echo "</td> <td class='view_cart_td' align='right' valign='top'>";
	if(($cat_price=$item->total_price($cat->cat_price))>0){
  	  printf("%1.2f",$cat_price);
	}
	  
	echo "</td>";
        if($item->is_expired()){
          $str= "<font color='red'>".expired."</font>";
        }else{
          $str=$item->ttl()." min.";
        }
        
	echo "<td class='view_cart_td'  valign='top'> $str"; 
    
        if(!$noremove){
          echo "<br> <a href='view_cart.php?action=remove&event_id=".
	      $event->event_id."&cat_id=".$cat->cat_id."&item=$item_id'>".remove."</a>";
        }
	echo "</td></tr>";
        
      }
    }
  }   
  echo "<tr><td class='view_cart_total' colspan='5'>".total_price." ".$_SHOP->currency." ";
  printf("%.2f",$cart->total_price());
  echo "</td></tr></table>";
  
}

function print_kasse ($cart,$noexpired=FALSE,$noremove=FALSE){
  global $_SHOP;
  
  echo "<table class='view_cart' cellpadding='5' width='600'>". 
       "<tr><td class='view_cart_big' colspan='4' align='center' valign='top'>".kasse."</td></tr>".
       "<tr><td class='view_cart_title' valign='top'>".ev_name."</td>".
       "<td class='view_cart_title' valign='top'>".ev_ticket."</td>".
       "<td class='view_cart_title' align='right' valign='top'>".cat_total."</td>".
       "<td class='view_cart_title' valign='top'>".exp_rm."</td>".
       "</tr>";

      $rowNr=0;
      
  foreach($cart->event_items as $event){     
    foreach($event->cat_items as $cat){
      foreach($cat->place_items as $item_id=>$item){
        if($item->is_expired() and $noexpired){continue;}
        $places_qty=$item->count();
	$etime=formatTime($event->event_time);
	$edate=formatDate($event->event_date);
        echo "<tr class='view_cart_tr$rowNr'>";
        $rowNr = ($rowNr+1)%2;
        echo "<td class='view_cart_td' valign='top'>".$event->event_name."<br>$edate - $etime <br>".
	$event->event_ort_name."<br>".$cat->cat_name." ".$cat->cat_price." ".$_SHOP->currency."<br>";

/*
        if((!$cat->cat_numbering) or $cat->cat_numbering=='both'){
   	  foreach($item->places_nr as $places_nr){
	    echo " ".$places_nr[0]."-".$places_nr[1];
	  }
	}else if($cat->cat_numbering=='rows'){
   	  foreach($item->places_nr as $places_nr){
            $rcount[$places_nr[0]]++;
	  }
	  foreach($rcount as $row=>$pcount){
	    echo " {$pcount}x".place_row." $row "; 
//	    echo place_row." {$row}x$pcount"; 
	  }
	}  
	*/
        echo  "</td>
        <td class='view_cart_td' valign='top' width='200'>";
	
	  $i=0;
	  echo "<table border='0' width='200'>";
          foreach ($item->places_nr as $place_nr){
	    $disc=$item->discounts[$i++];
	    
            if((!$cat->cat_numbering) or $cat->cat_numbering=='both'){
  	      $place=$place_nr[0]."-".$place_nr[1];
	    }else if($cat->cat_numbering=='rows'){
  	      $place=place_row." ".$place_nr[0]; 
	    }else{
	      $place='';
	    }
	    
	    if($disc){
	    //print_r($disc);
  	      $name=$disc->discount_name;
	      $price=sprintf("%1.2f",$disc->apply_to($cat->cat_price)); 
	    }else{
	      $name=normal;
	      $price=$cat->cat_price;
	    }
	    echo "<tr><td class='view_cart_td' valign='top' width='60'>$place</td>
	              <td class='view_cart_td' valign='top' width='100'>$name</td>
	              <td class='view_cart_td' valign='top' width='40' align='right'>$price</td></tr>";

	  }
	
	  echo "</table>";

		
        echo "</td> <td class='view_cart_td' align='right' valign='bottom'>";
	if(($cat_price=$item->total_price($cat->cat_price))>0){
  	  printf("%1.2f",$cat_price);
	}
	  
	echo "</td>";
        if($item->is_expired()){
          $str= "<font color='red'>".expired."</font>";
        }else{
          $str=$item->ttl()." min.";
        }
        
	echo "<td class='view_cart_td'  valign='top'> $str"; 
    
        if(!$noremove){
          echo "<br> <a href='view_cart.php?action=remove&event_id=".
	      $event->event_id."&cat_id=".$cat->cat_id."&item=$item_id'>".remove."</a>";
        }
	echo "</td></tr>";
        
      }
    }
  }   
  echo "<tr><td class='view_cart_total' colspan='5'>".total_price." ".$_SHOP->currency." ";
  printf("%.2f",$cart->total_price());
  echo "</td></tr></table>";
  
}


}
?>