<?php
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2010 Christopher Jenkins, Niels, Lou. All rights reserved.
 *
 * Original Design:
 *  phpMyTicket - ticket reservation system
 *   Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
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

class Order_Smarty {

  var $user_auth_id;

  function Order_Smarty (&$smarty){
    global $_SHOP;
    $smarty->register_object("order",$this,null,true,array("order_list","tickets"));
    $smarty->assign_by_ref("order",$this);

    if(isset($_SESSION['_SHOP_USER_AUTH']['user_id'])) {
      $this->user_auth_id=$_SESSION['_SHOP_USER_AUTH']['user_id'];
    }
  }

  function can_freeTicketCode() {
  	global $_SHOP; //print_r( $_SHOP  );
    return  !empty($_SHOP->freeTicketCode) || Discount::hasGlobals();
  }

  function make_f ($handling, $place, $no_cost=0, $user_id =0 , $no_fee = 0){
    global $_SHOP;

    if(!$user_id){
      $user_id=$_SESSION['_SHOP_USER'];//['user_id'];
    }

    $cart=$_SESSION['_SMART_cart'];
//
    if(!$cart || !$cart->can_checkout()){
      addWarning('cart_empty_or_invalid');
      return false;
    }

    if(!$handling or !$user_id or !$cart or !$cart->can_checkout()){
      addWarning('reservate_failed');
      return;
    }

    // this code is Rain_ to allow people to get tickets for free.
    if (isset($_SHOP->freeTicketCode) and !empty($_POST['FreeTicketCode']) and
        $_SHOP->freeTicketCode == $_POST['FreeTicketCode']) {
      $no_cost = true;
    }


    //compile order (order and tickets) from the shopping cart in order_func.php

    $order = Order::create($user_id, session_id(), $handling, 0, $no_fee, $no_cost, $place);

    //begin the transaction
    if(ShopDB::begin('Make order')){

      // apply Global discount over the total price.
      if (!empty($_POST['FreeTicketCode'])) {
        if (!($order->discount =Discount::LoadGlobal($_POST['FreeTicketCode']))) {
          addWarning('FreeTicketCode_notfound');
          ShopDB::rollback('FreeTicketCode_notfound');
          return;
        }
      }

      $cart->iterate('_collect', $order);


      //put the order into database
      if(!$order_id=$order->save()){
        addWarning('create_order_failed');
        ShopDB::rollback('create_order_failed');
        $cart->iterate('_reset', $order);
        return;
      }

      //commit the transaction
      return (ShopDB::commit('Order created'))? $order: false;
    } else {
      addWarning('cant_start_transaction');
      return;
    }

  }

  function res_to_order($params,&$smarty){
      $order_id=$params['order_id'];
      $handling_id=$params['handling_id'];

      if(empty($order_id) || empty($handling_id)){
        return;
      }

      //if(($order_id=$this->secure_url_param($params['order_id']))<=1){return;}
      //if(($handling_id=$this->secure_url_param($params['handling_id']))<=1){return;}
      if($params['no_cost']===true){$no_cost=true;}
      if($params['no_fee']===true){$no_fee=true;}
      if($params['place']!='pos'){$place='www';}else{$place='pos';}

    if($this->res_to_order_f($order_id,$handling_id,$no_fee,$no_cost,$place)){
      $smarty->assign('order_success',true);
    }
  }

  function res_to_order_f($order_id,$handling_id,$no_fee,$no_cost,$place){
    //global $_SHOP; // no need for this?
    return Order::reserve_to_order($order_id,$handling_id,$no_fee,$no_cost,$place);
  }


  function cancel ($params,&$smarty){
    $this->cancel_f($params['order_id'],$params['reason']);
  }

  function cancel_f ($order_id, $reason = null ){
    if ($order = Order::load($order_id)) {
      return Order::delete($order_id, is($reason,'order_canceled_by_user'), $this->user_auth_id);
    }
  }

  function delete_ticket ($params, &$smarty){
    $this->delete_ticket_f($params['order_id'],$params['ticket_id']);
  }

  function delete_ticket_f ($order_id,$ticket_id){
    if ($order = Order::load($order_id)) {
      return Order::delete_ticket($order_id,$ticket_id,0,$this->user_auth_id);
    }
  }


  /**
   * Order_Smarty::order_list()
   *
   * @param mixed $params
   * @param mixed $content
   * @param mixed $smarty
   * @param mixed $repeat
   * #Passable Params:
   * 	status =
   * 	user = id
   * 	place =
   * 	not_sent = bool
   * 	not_status = status
   * 	order = comma delimied order by
   * 	order_id = id
   */
  function order_list ($params, $content, &$smarty,&$repeat){

    if ($repeat) {
      $from='FROM `Order`';

      if($params['user_id']){
        $user_id=$this->secure_url_param($params['user_id']);
        $where="where order_user_id='{$user_id}' AND Order.order_status!='trash'";
  /*    }else if($this->user_auth_id){
           $where="where order_user_id='{$this->user_auth_id}' AND Order.order_status!='trash'";
  */
      }else{
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

      if($params['handling'] || $params['not_hand_payment'] || $params['not_hand_shipment'] || $params['hand_shipment'] || $params['hand_payment']){
      	$from.=',Handling ';
      	$where.=' AND handling_id = order_handling_id';
      	if($params['not_hand_payment']){
      		$types=explode(",",$params['not_hand_payment']);
      		foreach($types as $type){
      			if($in){$in .= ",'".$type."'";
      			}else{$in = "'".$type."'";}
      		}
      		$where.=" AND handling_payment NOT IN ({$in})";
      	}
      	unset($in);
      	if($params['not_hand_shipment']){
      		$types=explode(",",$params['not_hand_shipment']);
      		foreach($types as $type){
      			if($in){$in .= ",'".$type."'";
      			}else{$in = "'".$type."'";}
      		}
  			$where.=" AND handling_shipment NOT IN ({$in})";
      	}
      	unset($in);
      	if($params['hand_shipment']){
      		$types=explode(",",$params['hand_shipment']);
      		foreach($types as $type){
      			if($in){$in .= ",'".$type."'";
      			}else{$in = "'".$type."'";}
      		}
      		$where.=" AND handling_shipment IN ({$in})";
      	}
      	unset($in);
      	if($params['hand_payment']){
      		$types=explode(",",$params['hand_payment']);
      		foreach($types as $type){
      			if($in){$in .= ",'".$type."'";
      			}else{$in = "'".$type."'";}
      		}
      		$where.=" AND handling_payment IN ({$in})";
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
        $order_by="order by {$params['order']}";
      }

      if($params['order_id']){
          $order_id=$this->secure_url_param($params['order_id']);
          $where .= " and order_id='{$order_id}'";
      }
      if($params['curr_order_id']){
          $curr_order_id=$this->secure_url_param($params['curr_order_id']);
          $where .= " and order_id>'{$curr_order_id}'";
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
      }elseif($params['length']){
        $limit='limit 0,'.$params['length'];
      }


      $query="SELECT SQL_CALC_FOUND_ROWS * $from $where $order_by $limit";
      $res=ShopDB::query($query);

      $part_count = ShopDb::query_one_row("Select FOUND_ROWS()", false);
      $part_count = $part_count[0];
      $res = array($res,$part_count);
      $order=shopDB::fetch_assoc($res[0]);

    }else{
      $res=array_pop($smarty->_SHOP_db_res);
      $part_count= $res[1];
      if(isset($res)){
        $order=shopDB::fetch_assoc($res[0]);
      }
    }

    if($params['all']){
      if(!empty($order)){
        $orders[]=$order;
        while($order=shopDB::fetch_assoc($res)){
          $orders[]=$order;
        }
        $smarty->assign("shop_orders",$orders);
        $smarty->assign("shop_orders_count",$part_count);
      }

      $repeat=FALSE;
      return $content;

    }else{

      $repeat=!empty($order);

      if($order){
        $smarty->assign("shop_order",$order);
        $smarty->assign("shop_orders_count",$part_count);
        $smarty->_SHOP_db_res[]=$res;

        $query="SELECT * FROM User WHERE user_id={$order['order_user_id']}";
        $res=ShopDB::query($query);
        $user=shopDB::fetch_assoc($res);
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

	      	$ticket=ShopDB::fetch_array($res);
    	}else{
      		$res=array_pop($smarty->_SHOP_db_res);
		  	$ticket=ShopDB::fetch_assoc($res);
    	}
    	if($params['all']){
   			//$repeat=!empty($ticket); //not required
     		if($ticket){
       			$c=1;
		       $tickets[]=$ticket;
		       while($ticket=ShopDB::fetch_assoc($res)){
		         $tickets[]=$ticket;$c++;
		       }

		       $smarty->assign("shop_tickets",$tickets);
		       $smarty->assign("shop_tickets_count",$c);
     		}
     		$repeat=FALSE;
   			return $content;
    	}elseif($params['min_date']){
    		//$repeat=!empty($ticket); //not required.
    		if($ticket){
		    	$c=1;
		        $min_date=true;
		        while($ticket=ShopDB::fetch_assoc($res)){
		        	$c++;
		            $min_date=min($ticket['event_date'],$min_date);
      			}
      			$smarty->assign("shop_tickets_count",$c);
    			$smarty->assign("shop_ticket_min_date",$min_date);
      		}
      		$repeat=FALSE;
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

  function setStatusPaid($order_id){
    return Order::set_payed($order_id);
  }


  /**
   * Order_Smarty::set_send_f()
   *
   * @deprecated beta6
   * @return
   */
  function set_send_f($order_id){
    global $_SHOP;
    return Order::set_send($order_id, 0, $this->user_auth_id);
  }

  function setStatusSent($order_id){
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
    return Order::set_payed($order_id);
  }

  function order_print ($params, &$smarty){

    if($params['print_prefs']=='pdf'){
      $print=FALSE;
    }else{
      $print=TRUE;
    }
    $mode = (int)$params['mode'];
    If (!$mode) $mode =3;

   Order::printOrder($params['order_id'],'', 'stream', $print, $mode);
  }

  function paymentForOrder($params, &$smarty){
    require_once "classes/class.checkout.php";
    $orderId = is($params['order_id'],0);
    $return = Checkout::paymentAction($orderId,$smarty);
    $smarty->assign('payment_tpl',$return);
  }

  function EncodeSecureCode($order= null, $item='sor=', $loging=false) {
    return Order::EncodeSecureCode($order, $item, $loging);
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
        addWarning("No Such Variable");
        $nonum="This";
        return $nonum;
    }
    }
  }
//  function EncodeSecureCode($order){
//    return $order->EncodeSecureCode();
//  }
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
    // cant find any
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