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

//Load File
if (!defined('ft_check')) {die('System intrusion ');}
require_once("shop_plugins".DS."function.placemap.php");

class PosAjax { 

	private $request = array();
	private $action = "";
	private $actionName = "";
	private $json;

	function __construct($request, $action){
		$this->request = $request;
		$this->actionName = $action;
    $other = substr($action,0,1);
    if(strtolower($other) == '_'){
      $this->action = 'do'.ucfirst(substr($action,1));
    }else{
      $this->action = "get".ucfirst(strtolower($action));      
    }
		$this->json = array();
	}
	
	
	/**
	 * PosAjax::getEvents()
	 * 
	 * @param datefrom ('yyyy-mm-dd') optional
	 * @param dateto ('yyyy-mm-dd') optional
	 * @param return_dates_only (true|false) If set to true, event_dates will only be returned.
	 * 
	 * Will Return:
	 * 	- events 
	 * 		| - id (event_id)
	 *			| - html (option html)
	 * 		  	- free_seats (tot free seats)
	 * 		| - id ....
	 * 	- event_dates
	 * 		| - date ('yyyy-mm-dd')
	 * 		| - date ('yyyy-mm-dd')
	 * 		  - date ...
	 *
	 * 
	 * @return boolean : if function returned anything sensisble.
	 */
	private function getEvents(){
		//Check for date filters
		if($this->request['datefrom']){
			$fromDate = $this->request['datefrom'];
		}else{
			$fromDate = date('Y-m-d');
		}
		if($this->request['dateto']){
			$toDate = _esc($this->request['dateto']);
		}else{
			$toDate = 'event_date';
		}
		
		$sql = "SELECT  event_id, event_name, ort_name, event_date, event_time, es_free
				FROM Event,
				Ort,
				Event_stat
				WHERE 1=1
				AND ort_id = event_ort_id
				AND event_id = es_event_id
				AND event_date >= "._esc($fromDate)." 
				AND event_date <= ".$toDate."
				and event_rep LIKE '%sub%'
				AND event_status = 'pub'
				AND es_free > 0
				ORDER BY event_date,event_time
				LIMIT 0,50";
		if(!$query = ShopDB::query($sql)){
			return false;
		}
		//Load html and javascript in the json var.
		$this->json['events'] = array(); //assign a blank array.
		//Break down cats and array up with additional details.
		while($evt = ShopDB::fetch_assoc($query)){
      		$date = formatDate($evt['event_date'],con('shortdate_format'));
      		$time = formatTime($evt['event_time']);
      		
			$option = "<option value='{$evt['event_id']}'>{$evt['event_name']} - {$evt['ort_name']} - {$date} - {$time}</option>";
			
			$this->json['events'][strval($evt['event_id'])] = array ('html'=>$option,'free_seats'=>$evt['es_free']);
		}
		return true;
	}
	
	/**
	 * PosAjax::getCategories()
	 *
	 * @param categories_only (true|false) will only return the categories if set true else grabs discounts too. 
	 * 
	 * Will return:
	 *  - categories 
	 * 		|- id (number)
	 * 			|- html (category option)
	 * 			|- numbering (true|false)
	 * 			|- placemap (placemap html)
	 * 			|- price (number)
	 *       - free_seats (int)
	 * 		|- id.. (number)
	 * |- enable_discounts (true|false)
	 * |- discounts
	 * 		|- id (number)
	 * 			|- html (discount option)
	 * 			|- type (fixed|percent)
	 * 			 - price (number)
	 * 		|- id.. (number)
	 * 
	 * @return boolean as to whether the JSON should be compiled or not.
	 */
	private function getCategories(){
		if(!isset($this->request['event_id'])){
			return false;
		}else{
			$eventId = &$this->request['event_id'];
		}
		if(!is_numeric($eventId)){
	 		return false;
		}
			
		$sql = "SELECT *
			FROM Category c,
			Category_stat cs
			WHERE 1=1
			AND c.category_id = cs.cs_category_id
			AND c.category_event_id = "._esc($eventId);
		$query = ShopDB::query($sql);
		
		//Load html and javascript in the json var.
		$this->json['categories'] = array(); //assign a blank array.
		
		//Break down cats and array up with additional details.
		while($cat = ShopDB::fetch_assoc($query)){
			$option = "<option value='".$cat['category_id']."'>".$cat['category_name']." -  ".$cat['category_price']."</option>";
			$numbering = false; //default numbering to none
			$placemap = ""; //leave placemap empty shouldnt be filled unless told to colect it.
			if(strtolower($cat['category_numbering']) != 'none'){
				$numbering = true; // If there should be a placemap set to true otherwise leave as false to show qty box.
				
				//Load Place Map
				$placemap = $this->loadPlaceMap($cat);
			}
			$this->json['categories'][strval($cat['category_id'])] = array('html'=>$option,'numbering'=>$numbering,'placemap'=>$placemap,'price'=>$cat['category_price'],'free_seats'=>$cat['cs_free']); 
		}
		//Finish loading categories and there details lets grab the discounts to...
		//If we only need the categories updating then just stop here.
		if($this->request['categories_only']){
			return true;
		}
		
		//Select Events Discounts
		$this->json['discount_sql'] = $sql = "select discount_id, discount_name, discount_value, discount_type
			FROM Discount d
			WHERE d.discount_event_id = "._esc($eventId);
		$query = ShopDB::query($sql);
		
		//We count the number of rows to see if we should bother running through discounts.
		$numRows = ShopDB::num_rows($query);
		
//		if($numRows > 0){	
			//Define json array for discounts
			$this->json['enable_discounts'] = false; //enable discounts.
			$this->json['discounts'] = array(); //assign a blank array.
			//Add the  "None Discount"
			$this->json['discounts'][] = array('html'=>"<option value='0' selected='selected'> ".con('normal')." </option>",'type'=>'fixed','price'=>0);
			while($disc = ShopDB::fetch_assoc($query)){
				//Check to see if percent or fixed
  			$this->json['enable_discounts'] = true; //enable discounts.
				if(strtolower($disc['discount_type']) == 'percent' ){
					$option = "<option value='".$disc['discount_id']."'>".$disc['discount_name']." - ".$disc['discount_value']."%</option>";
					$type = "percent";
				}else{
					$option = "<option value='".$disc['discount_id']."'>".$disc['discount_name']." - ".$disc['discount_value']."</option>";
					$type = "fixed";
				}
				//Load up each row
				$this->json['discounts'][] = array('html'=>$option,'type'=>$type,'price'=>$disc['discount_value']);
			}
//		}else{
//			$this->json['enable_discounts'] = false; //disable discounts.
//		}
		return true;
	}



	/**
	 * PosAjax::_pre_items()
	 *
	 * This is part of the cartlist
	 * @return n one.
	 */
  function _pre_items (&$event_item,&$cat_item,&$place_item,&$data){
    $data[]=array($event_item,$cat_item,$place_item);
  }

	/*
	 * PosAjax::getCartInfo()
	 *
	 * @param categories_only (true|false) will only return the categories if set true else grabs discounts too.
	 *
	 * @return boolean as to whether the JSON should be compiled or not.
	 */

	private function getCartInfo(){
	global $cart, $order;

    $this->json['page'] = 1;
    $this->json['total'] = 1;
    $this->json['records'] = 0;
    $this->json['userdata'] = array();
    $mycart=$_SESSION['_SMART_cart'];
    $this->json['userdata']['can_cancel'] = !$cart->is_empty_f() or isset($_SESSION['_SHOP_order']);
    $cart_list  =array();
    if($mycart and !$cart->is_empty_f()){
      $mycart->load_info();
      $mycart->iterate(array(&$this,'_pre_items'),$cart_list);
    }


    $counter  = 0;
    $subprice = 0.0;
    foreach ($cart_list as $cart_row) {
      $event_item    = $cart_row[0];
      $category_item = $cart_row[1];
      $seat_item     = $cart_row[2];
      $seat_item_id  = $seat_item->id;
      $seats_ids     = $seat_item->places_id;
      $seats_nr      = $seat_item->places_nr;
      $disc          = ($seat_item->discounts)?$seat_item->discounts[0]: 0;
      $seatinfo = '';

      if($category_item->cat_numbering=='rows'){
        $rcount=array();
        foreach($seat_item->places_nr as $places_nr){
          $rcount[$places_nr[0]]++;
        }
        foreach($rcount as $row => $count){
          $seatinfo .= ", $count x ".con('row')." {$row}";
        }
      } elseif (!$category_item->cat_numbering or $category_item->cat_numbering == 'both'){
        foreach($seat_item->places_nr as $places_nr){
 					$seatinfo .= ", {$places_nr[0]} - {$places_nr[1]}";
        }
      }
      $seatinfo = substr($seatinfo,2);
      if ($seat_item->ordered) {
            $col = "<font color='red'>".con('Ordered').'</font>';
      } else {
        if ($seat_item->is_expired()) {
            $col = "<font color='red'>".con('expired').'</font>';
      	} else {
      	    $col = $seat_item->ttl()." min.";          //"<img src='../images/clock.gif' valign='middle' align='middle'> ".
        }
        $col ="<form id='remove' class='remove-cart-row' name='remove{$seat_item_id}' action='index.php' method='POST' >".
     		 		 "<input type='hidden' value='{$event_item->event_id}' name='event_id' />".
      		 	 "<input type='hidden' value='{$category_item->cat_id}' name='category_id' />".
      		 	 "<input type='hidden' value='{$seat_item_id}' name='item' />".
             "<button type='submit' class='ui-widget-content jqgrow remove-cart-row-button'
                      style='display: inline; cursor: pointer; padding:0; margin: 0; border: 0px'> ".
             "<img src='../images/trash.png' style='display: inline; cursor: pointer;padding:0; margin: 0; border: 0px' width=16></button> ".
             $col.
  			     "</form>";
  //  			 "<input type='hidden' value='remove" name="action" />
      }
      $row = array($col);
      $row[] = "<b>{$event_item->event_name}</b> - {$event_item->event_ort_name}<br>".
               formatdate($event_item->event_date)."  ".formatdate($event_item->event_time,con('time_format'));
      $row[] = count($seats_ids);
      $col = "{$category_item->cat_name}";
      if ($seatinfo) {
        $col = "<acronym title='{$seatinfo}'>$col</acronym>";
      }
  		if ($disc) {
   	    $col .= "<br><i>".con('Discount_for')." ".$disc->discount_name.'</i>';
      }
      $row[] = $col;
  		if ($disc) {
     	 	$row[] = valuta($disc->apply_to($category_item->cat_price));
  		} else {
     		$row[] = valuta($category_item->cat_price);
  		}
  		$subprice += $seat_item->total_price($category_item->cat_price);
  		$row[] = valuta($seat_item->total_price($category_item->cat_price));

  		$this->json['rows'][] = array('id'=> "{$event_item->event_id}|{$category_item->cat_id}|{$seat_item_id}", 'cell'=> $row);
  		$counter++ ;
		}
    $sql = 'SELECT `handling_id`, `handling_fee_fix`, `handling_fee_percent`
            FROM `Handling`
            WHERE handling_sale_mode LIKE "%sp%"';

 		if(check_event($cart->min_date_f())){
			$sql .= " and handling_alt <= 3";
		} else {
   		$sql .= " and handling_alt_only='No'";
		}

    $res=ShopDB::query($sql);
    $totalprice = $subprice;
    $handlings = array();
    while ($pay=shopDB::fetch_assoc($res)){
      $fee = ($subprice*is($pay['handling_fee_percent'],0.0)/100.00) + is($pay['handling_fee_fix'],0.0);
      if (($_POST['handling_id']== $pay['handling_id'] and $counter and $_POST['no_fee']!=='1')) { // and !$counter and $_POST['no_fee']!==1
        $totalprice += $fee;
      }
 			$fee = ($fee == 0.00)? '': '+ '.valuta($fee);
      $handlings[] = array('index'=>"#price_{$pay['handling_id']}", 'value'=>$fee);
    }
    $this->json['userdata']['handlings'] = $handlings;
    $this->json['userdata']['total']     = valuta($totalprice);
    $this->json['userdata']['can_order'] = $counter !== 0;
		return true;
	}
	
	
	private function getPlaceMap(){
		if(!isset($this->request['category_id'])){
			return false;
		}else{
			$catId = &$this->request['category_id'];
		}
		if(!is_numeric($catId)){
	 		return false;
		}
			
		$sql = "SELECT *
			FROM Category c,
			Category_stat cs
			WHERE 1=1
			AND c.category_id = cs.cs_category_id
			AND c.category_id = "._esc($catId);
		$result = ShopDB::query_one_row($sql);
		
		if(strtolower($cat['category_numbering']) != 'none'){
			$placemap = $this->loadPlaceMap($result);
			$this->json['placemap'] = $placemap;
			return true;
		}
		return false;
	}	

	private function getUserSearch(){
   		$fields = ShopDB::fieldlist('User');
    	$where = '';
   		foreach($_POST as $field => $data) {
      		if (in_array($field,$fields) and strlen(clean($data))>1) {
     			if ($where){ $where.='and ';}
        		$where.= "({$field} like "._esc('%'.clean($data).'%').") \n";
  			}
   		}
   		if (!$where) $where = '1=2';
   			
	   	$this->json['POST'] = $where;

		$sql = "SELECT user_id, CONCAT_WS(', ',user_lastname, user_firstname) AS user_data,
               	user_zip, user_city, user_email
				FROM `User`
        		WHERE {$where}";// and user_owner_id =". $_SESSION['_SHOP_AUTH_USER_DATA'][;
		$query = ShopDB::query($sql);
		$numRows = ShopDB::num_rows($query);
	    $this->json['page'] = 1;
	    $this->json['total'] = 1;
	    $this->json['records'] = 0;
	    $this->json['userdata'] = array();

		while($user = ShopDB::fetch_row($query)){
			$this->json['rows'][] = array('id'=>$user[0], 'cell'=> $user);
		}
	return true;
	}

	private function getCanprint(){
		if($this->request['orderid']){
			$orderid = $this->request['orderid'];
		}else{
			return false;
		}
		$sql = "SELECT order_payment_status
            FROM `Order`
            WHERE order_id="._esc($orderid);
    	$q = ShopDB::query_one_row($sql);
 	  	$this->json['status'] = $q['order_payment_status']=='payed';
    
		return true;
	}

	private function getUserData(){
		$sql = "SELECT *
            FROM `User`
            WHERE user_id="._esc($_POST['user_id']);
		$query = ShopDB::query($sql);
		$numRows = ShopDB::num_rows($query);
		if($numRows > 0){
  	  $this->json['user'] = ShopDB::query_one_row($sql);
 			return true;
    }
		return false;
	}

	/**
	 * PosAjax::loadPlaceMap()
	 * 
	 * @param mixed $category
	 * @return placemap html
	 */
	private function loadPlaceMap($category){
		
		//define vars...
		$params = array();
		$smarty = "";
		
		$params['category'] = $category; //add category details
		
		return smarty_function_placemap($params, $smarty); //return the placemap		
	}
  
  /**
	* @name add to cart function
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
  private function doAddToCart() {
    $event_id = is($this->request['event_id'],0);
    $category_id = is($this->request['category_id'],0);
    $mode='mode_pos';
    $seats = $this->request['place'];
    $reserved=false;
    $discount_id = $this->request['discount_id'];
    $force= false;
    if($event_id <= 0){
      addWarning('wrong_event_id');
      $this->json['status']=false;
      return true;
    }
    require_once "smarty.mycart.php";
    $res = MyCart_Smarty::CartCheck($event_id,$category_id,$seats,$mode,$reserved,$discount_id,$force);
    if($res){
      $this->json['reason']=$res;
      $this->json['status']=true;
    	return true;
    }else{
      $this->json['reason']='';
      $this->json['status']=false;
    	return true;
    }
  }
	
	
  public function callAction(){
    if(is_callable(array($this,$this->action))){
		  $this->json = am($this->json,array("status" =>true, "reason" => ''));
      //Instead of falling over in a heap at least return an error.
      try{
        $return = call_user_func(array($this,$this->action)); 
      }catch(Exception $e){
        addWarning($e->getMessage());
        $return = false;
      }
      if(!$return){
				$this->json = array("status" => false, "reason" => '');
			}
      $this->loadMessages();
  		echo json_encode($$this->json);
			return true;
		}
		return false;
	}
  
  private function loadMessages() {
    $this->json['messages']['warning'] = printMsg('__Warning__');
    $this->json['messages']['Notice'] = printMsg('__Notice__');
    if (isset($_SHOP->Messages['__Errors__'])) {
      $err = $_SHOP->Messages['__Errors__'];
      foreach ($err as $key => $value) {
        $output = '';
        foreach($value as $val){
          $output .= $val. "</br>";
        }
        $this->json['messages']['Error'][$key] = $output;
      }
    }
  }  
}
?>