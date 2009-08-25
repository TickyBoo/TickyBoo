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

require_once("classes/Order.php");
require_once("classes/Ticket.php");


class Order_Smarty {

  var $user_auth_id;
  var $error;
  
  function Order_Smarty (&$smarty){
    global $_SHOP;
    $smarty->register_object("order",$this,null,true,array("order_list","tickets"));
    $smarty->assign_by_ref("order",$this);
   
    if(isset($_SESSION['_SHOP_USER_AUTH']['user_id'])) {
      $this->user_auth_id=$_SESSION['_SHOP_USER_AUTH']['user_id'];
    }
  }

  function make_f ($handling, $place, $no_cost=0, $user_id =0 , $no_fee = 0){
  
    global $_SHOP;

    if(!$user_id){
      $user_id=$_SESSION['_SHOP_USER'];//['user_id'];
    }

    $cart=$_SESSION['_SMART_cart'];

    if(!$handling or !$user_id or !$cart or !$cart->can_checkout()){
      $this->error = con('reservate_failed');
      return;
    }
     //compile order (order and tickets) from the shopping cart in order_func.php

    $order = new Order($user_id, session_id(), $handling, 0, $no_fee, $no_cost, $place);

    //begin the transaction
    if(!ShopDB::begin('Make order')){
      $this->error =con('cant_start transaction');
      return; 
    }

    $cart->iterate('_collect', $order);

    //put the order into database     
    if(!$order_id=$order->save()){
      $this->error = con('save_failed');
      ShopDB::rollback('save_failed');
      $cart->iterate('_reset', $order);

      return; 
    }

  	$no_tickets=$order->size();
  	if($handling==1){
  		$set = "SET user_order_total=user_order_total+1,
                  user_current_tickets=user_current_tickets+{$no_tickets},
                  user_total_tickets=user_total_tickets+{$no_tickets} ";
  	}else{
  		$set = "SET user_order_total=user_order_total+1,
                  user_total_tickets=user_total_tickets+{$no_tickets} ";
  	}
      $query="UPDATE `User`
      		$set
  			WHERE user_id=".ShopDB::quote($user_id);
  	if(!$res=ShopDB::query($query)){
  		$this->error =con('user_failed');
  		ShopDB::rollback('user_failed');
  	}

    //commit the transaction
    ShopDB::commit('Order created');
    return $order;
  }


 	function res_to_order($params,&$smarty){
  		$order_id=$params['order_id'];
  		$handling_id=$params['handling_id'];
  		//if(($order_id=$this->secure_url_param($params['order_id']))<=1){return;}
  		//if(($handling_id=$this->secure_url_param($params['handling_id']))<=1){return;}
  		if($params['no_cost']===true){$no_cost=true;}
  		if($params['no_fee']===true){$no_fee=true;}
  		if($params['place']!='pos'){$place='www';}else{$place='pos';}
		
		$this->res_to_order_f($order_id,$handling_id,$no_fee,$no_cost,$place);
		
		$smarty->assign('order_success',true);
	}
	
	function res_to_order_f($order_id,$handling_id,$no_fee,$no_cost,$place){
		global $_SHOP;
		return Order::reserve_to_order($order_id,$handling_id,$no_fee,$no_cost,$place);
	}
    
    
  function cancel ($params,&$smarty){
    $this->cancel_f($params['order_id'],$params['reason']);
  }
  
  function cancel_f ($order_id, $reason = null ){
    global $_SHOP;
    return Order::order_delete($order_id, is($reason,'order_canceled_by_user'),$this->user_auth_id);
  }
  
  function delete_ticket ($params, &$smarty){
    $this->delete_ticket_f($params['order_id'],$params['ticket_id']); 
  }
  
  function delete_ticket_f ($order_id,$ticket_id){
    global $_SHOP;
    return Order::order_delete_ticket($order_id,$ticket_id,0,$this->user_auth_id);
  }

  function order_list ($params, $content, &$smarty,&$repeat){
    
    
    if ($repeat) {
			
      $from='FROM `Order`';
      
		if($params['user_id']){
			$user_id=$this->secure_url_param($params['user_id']);
			$where="where order_user_id='{$user_id}' AND Order.order_status!='trash'";
		}/*else if($this->user_auth_id){
   			$where="where order_user_id='{$this->user_auth_id}' AND Order.order_status!='trash'";
		}*/else{
			if($params['status']) {
				$status=$this->secure_url_param(false,$params['status']);
				if($params['status']=="payed"){
					$where .="WHERE order_status NOT IN ('trash','cancel') AND Order.order_payment_status='{$params['status']}'";
				}elseif($params['status']=="send"){
					$where .="WHERE order_status NOT IN ('trash','cancel') AND Order.order_shipment_status='{$params['status']}'";
				}else{
					$where .="WHERE Order.order_status='{$params['status']}'";
				}
			}else{	
			  $where="WHERE 1 AND Order.order_status!='trash'";
			}
		}
			
	  if($params['user']){
		  $from.=',User';
			$where.=' and order_user_id=user_id';
		}
	if($params['place']) {
	  $where .=" and order_place='{$params['place']}'";
	}
	
	if($params['not_sent']){
		$where .=" AND order_shipment_status != 'send' ";
	}
	
	if($params['not_status']){
      	$types=explode(",",$params['not_status']);
      	if(count($types) <= 1){
			if($params['not_status']=="payed" and $params['status']!="payed"){
				$where .=" AND order_payment_status!='{$params['not_status']}' ";
			}elseif($params['not_status']=="send" and $params['status']!="send"){
				$where .=" AND order_shipment_status!='{$params['not_status']}' ";
			}else{
				$where .="AND order_status!='{$params['not_status']}' ";
			}
      	}else{
      		$first=true;
      		foreach($types as $type){
      			$type=_esc($type);
      			if($first){
      				$notIn .= $type;
					  $first = false;	
      			}else{
      				$notIn .= ",".$type;
      			}
			}
      		$where .= " AND order_status NOT IN ( {$notIn} )";
      	}
 	}	  	
	  	
      if($params['order']){
        $order_by="order by Shop::{$params['order']}";
      }
    
      if($params['order_id']){
		$order_id=$this->secure_url_param($params['order_id']);
    	$where .= " and order_id='{$order_id}'";
      }
      if($params['order_by_date']){
		$order_by .= " ORDER BY order_date {$params['order_by_date']}";
	  }

      if($params['start_date']){
        $where .= " and order_date>='{$params['start_date']}'";      
      }
    
      if($params['end_date']){
        $where .= " and order_date<='{$params['end_date']}'";      
      }
    
      if($params['first']){
      	$first=$this->secure_url_param($params['first']);
        $limit='limit '.$first;
	    
		if($params['length']){
	      $limit.=','.$params['length'];
		}
      }else if($params['length']){
        $limit='limit 0,'.$params['length'];
      }
    
  
      $query="SELECT * $from $where $order_by $limit";
      $res=ShopDB::query($query);
    
      $order=shopDB::fetch_array($res);
    
    }else{
      $res=array_pop($smarty->_SHOP_db_res);
      if(isset($res)){
	  	$order=shopDB::fetch_array($res);
	  }
    }

	if($params['all']){

      if(!empty($order)){
      	$c=1;
       	$orders[]=$order;
       	while($order=shopDB::fetch_array($res)){
          $orders[]=$order;$c++;
		} 
		$smarty->assign("shop_orders",$orders);  
    	$smarty->assign("shop_orders_count",$c);  
	  }
     
     $repeat=FALSE;
     return $content;
    
    }else{

      $repeat=!empty($order);

      if($order){
        $smarty->assign("shop_order",$order);  
        $smarty->_SHOP_db_res[]=$res;
        
		$query="SELECT * FROM User WHERE user_id={$order['order_user_id']}";
		$res=ShopDB::query($query);
		$user=shopDB::fetch_array($res);
		if($user){
	  	  $smarty->assign("user_order",$user);
		}
      }
    }
    return $content;
  }
  
  function tickets ($params, $content, &$smarty,&$repeat){
    
    
    if ($repeat) {
      if(!$params['order_id']){
        $repeat=FALSE;
				return;
      }
      $order_id=$this->secure_url_param($params['order_id']);
				
      $from='FROM Seat LEFT JOIN Discount ON seat_discount_id=discount_id 
	  					LEFT JOIN Event ON seat_event_id=event_id 
						LEFT JOIN Category ON seat_category_id= category_id
						LEFT JOIN PlaceMapZone ON Seat.seat_zone_id=pmz_id';
      $where=" where seat_order_id='{$order_id}'";
	  
	  if($params['user_id']) {
		$where.=" and seat_user_id='{$this->user_auth_id}'";
	  }
	  if($params['place']) {
	  	$where .=" and order_place='{$params['place']}'";
	  }
      if($params['order']){
        $order_by="order by {$params['order']}";
      } 
    
      if($params['limit']){
      	$length=$this->secure_url_param($params['limit']);
        $limit='limit '.$length;
      }
    
      $query="select * $from $where $order_by $limit";
    
      $res=ShopDB::query($query);
    
      $ticket=shopDB::fetch_array($res);
    
    }else{
      $res=array_pop($smarty->_SHOP_db_res);
      $ticket=shopDB::fetch_array($res);
    }

    if($params['all']){
	 
	 $repeat=!empty($ticket);
     if($ticket){
       $c=1;
       $tickets[]=$ticket;
       while($ticket=shopDB::fetch_array($res)){
         $tickets[]=$ticket;$c++;
       } 
     	
       $smarty->assign("shop_tickets",$tickets);  
       $smarty->assign("shop_tickets_count",$c);  
     }
	 return $content;  
    }elseif($params['min_date']){
		$repeat=!empty($ticket);
		if($ticket){
	    	$c=1;
	    	$min_date=true;
	       	while($ticket=shopDB::fetch_array($res)){
	        	$c++;
	        	$min_date=min($ticket['event_date'],$min_date);
			} 
	    $smarty->assign("shop_tickets_count",$c);
		$smarty->assign("shop_ticket_min_date",$min_date);
	    }
	    return $content;
    }else{

      $repeat=!empty($ticket);

      if($ticket){
        $smarty->assign("shop_ticket",$ticket);  
        //print_r($ticket);
		$smarty->_SHOP_db_res[]=$res; 
      
      }
    }
    return $content;
    
  }
  //Added v1.3.4 For Processing Menu PoS process.tpl
  function set_status_f($order_id,$status){
	return Order::set_status_order($order_id,$status);
  }
  
  function set_send_f($order_id){
  	global $_SHOP;
	return Order::set_send($order_id, 0, $this->user_auth_id);
  }
  
  
  function set_reserved ($params,&$smarty){
    $this->set_reserved_f($params['order_id']);
  }
  
  function set_reserved_f ($order_id){
    global $_SHOP;
    return Order::set_reserved($order_id, 0, $this->user_auth_id);
  }
  
	// added for manual Pepper system - Legacy

	function save_order_note($params,&$smarty){		
		$ret=Order::save_order_note($params['order_id'],$params['note']);
		$smarty->assign('order_note',$ret);
	}
  
  function set_payed ($params,&$smarty){
    $this->set_payed_f($params['order_id']);
  }
  
  function set_payed_f ($order_id){
    global $_SHOP;
    return Order::set_payed($order_id, 0, $this->user_auth_id);
  }
  
  function order_print ($params,&$smarty){

   if($params['print_prefs']=='pdf'){
      $print=FALSE;
    }else{
      $print=TRUE;
    }
    
    Order::print_order($params['order_id'],'','stream',$print);
  }
  
  function secure_url_param($num=FALSE, $nonum=FALSE) 
  { 
	if ($num) {
	  $correct = is_numeric($num); 
      if( $correct ) { return $num; } 
      elseif(!$correct ){ 
	    	echo "No Such ID";
      	//$num = cleanNUM($num);
		    $num="1";
      	return $num; 
      }
    }
    if ($nonum) {
	  $correct = preg_match('/^[a-z0-9_]*$/i', $nonum);
      //can also use ctype if you wish instead of preg_match 
      //$correct = ctype_alnum($nonum);          
      if($correct) { return $nonum; } 
      elseif(!$correct) { 
	  	echo "No Such Varible";
      	$nonum="This";
      	return $nonum;
	  } 
    } 
  }
  function EncodeSecureCode($order){
    return $order->EncodeSecureCode();
  }
}

function _collect(&$event_item,&$cat_item,&$place_item,&$order){
  if(!$place_item->is_expired()){

    $i=0;
    $discounts=$place_item->discounts;
    foreach($place_item->places_id as $place_id){
      if($discounts[$i]){
        $order->add_seat($event_item->event_id,$cat_item->cat_id,$place_id,$cat_item->cat_price,$discounts[$i]);
      }else{
        $order->add_seat($event_item->event_id,$cat_item->cat_id,$place_id,$cat_item->cat_price);
      }
      $i++;
    }
    $place_item->ordered =  $order;

    if(!isset($order->place_items)){
      $order->place_items=array();
    }
    array_push($order->place_items,array($event_item->event_id,$cat_item->id,$place_item->id));
  }
  return 1;
}

function _reset(&$event_item,&$cat_item,&$place_item,&$order){
  if ($place_item->ordered ==  $order) {
    unset($place_item->ordered);
  }
  return 1;
}

?>