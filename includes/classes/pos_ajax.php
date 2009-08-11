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
require_once("shop_plugins/function.placemap.php");

class PosAjax { 

	private $request = array();
	private $action = "";
	private $actionName = "";
	private $json;

	function __construct($request, $action){
		$this->request = $request;
		$this->actionName = $action;
		$this->action = "get".ucfirst(strtolower($action));
		$this->json = array();
	}
	
	
	private function getEvents(){
		//Check for date filters
		if($this->request['datefrom']){
			$fromDate = $this->request['datefrom'];
		}else{
			$fromDate = date('Y-m-d');
		}
		if($this->request['dateto']){
			$toDate = $this->request['dateto'];
		}else{
			$toDate = 'event_date';
		}
		
		$sql = "SELECT * 
				FROM Event,
				Ort,
				Event_stat
				WHERE 1=1
				AND ort_id = event_ort_id
				AND event_id = es_event_id
				AND event_date >= "._esc($fromDate)." 
				AND event_date <= "._esc($toDate)."
				and event_rep LIKE '%sub%'
				AND event_status = 'pub'
				ORDER BY event_date,event_time
				LIMIT 0,50";
		if(!$query = ShopDB::query($sql)){
			return false;
		}
		
		//Load html and javascript in the json var.
		$this->json['events'] = array(); //assign a blank array.
		
		//Break down cats and array up with additional details.
		while($evt = ShopDB::fetch_assoc($query)){
			$date = date_parse($evt['event_date']);
			$eventText = $evt['event_name'].' - '.$evt['ort_name'].' - '.$date['day'].'/'.$date['month'].'/'.$date['year'];
			$option = "<option value='".$evt['event_id']."'>".$eventText."</option>";
			
			$this->json['events'][$evt['event_id']] = array ('html'=>$option,'free_seats'=>$evt['es_free']);
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
	 * 			 - price (number)
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
			$this->json['categories'][$cat['category_id']] = array('html'=>$option,'numbering'=>$numbering,'placemap'=>$placemap,'price'=>$cat['category_price']); 
		}
		//Finish loading categories and there details lets grab the discounts to...
		//If we only need the categories updating then just stop here.
		if($this->request['categories_only']){
			return true;
		}
		
		//Select Events Discounts
		$sql = "SELECT *
			FROM Discount d
			WHERE 1=1
			AND d.discount_event_id = "._esc($eventId);
		$query = ShopDB::query($sql);
		
		//We count the number of rows to see if we should bother running through discounts.
		$numRows = ShopDB::num_rows($query);
		
		if($numRows > 0){	
			//Define json array for discounts
			$this->json['enable_discounts'] = true; //enable discounts.
			$this->json['discounts'] = array(); //assign a blank array.
			//Add the  "None Discount"
			$this->json['discounts'][] = array('html'=>"<option id='0' selected='selected'> ".con('normal')." </option>",'type'=>'fixed','price'=>0);
			while($disc = ShopDB::fetch_array($query)){
				//Check to see if percent or fixed
				$price = $disc['discount_price'];
				if(strtolower($disc['discount_type']) == 'percent' ){
					$option = "<option id='".$disc['discount_id']."'>".$disc['discount_name']." - ".$disc['discount_value']."%</option>";
					$type = "percent";
				}else{
					$option = "<option id='".$disc['discount_id']."'>".$disc['discount_name']." - ".$disc['discount_value']."</option>";
					$type = "fixed";
				}
				//Load up each row
				$this->json['discounts'][] = array('html'=>$option,'type'=>$type,'price'=>$price);
			}
		}else{
			$this->json['enable_discounts'] = false; //disable discounts.
		}
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
		$sql = "SELECT user_id, user_lastname, user_firstname,
                   user_zip, user_city, user_email, user_owner_id
            FROM `User`
            WHERE 1";
		$query = ShopDB::query($sql);
		$numRows = ShopDB::num_rows($query);

		if($numRows > 0){
      $alt = 0;
   		//Break down cats and array up with additional details.
  		while($user = ShopDB::fetch_assoc($query)){
        $html = "<tr class='admin_list_row_$alt'>
                   <td width='152'>{$user['user_lastname']}, {$user['user_firstname']}</td>
                   <td width='102'>{$user['user_zip']}</td>
                   <td width='235'>{$user['user_city']}</td>
                   <td width='184'>{$user['user_email']}</td>
                 </tr>";

    		$this->json['users'][] = array('user_id'=>$user['user_id'], 'html'=> $html);
    		$alt = ( $alt + 1 ) % 2;
  		}
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
	
	
	public function callAction(){
		if(is_callable(array($this,$this->action))){
			$this->json = am($this->json,array("status" =>true, "reason" => ''));
			$return = call_user_func(array($this,$this->action));
			if($return){
				echo json_encode($this->json);	
			}else{
				$object = array("status" => false, "reason" => 'function failed');
				echo json_encode($object);
			}
			return true;
		}
		return false;
	}

}
?>