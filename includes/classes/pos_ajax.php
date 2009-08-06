<?php

//Load File
require_once("shop_plugins/function.placemap.php");

class PosAjax { 

	private $request = array();
	private $action = "";
	private $actionName = "";
	private $json;

	function __construct($request,$action){
		$this->request = $request;
		$this->actionName = $action;
		$this->action = "get".ucfirst(strtolower($action));
		$this->json = array();
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
			$this->json['discounts'][] = array('html'=>"<option id='0' selected='selected'> None </option>",'type'=>'fixed','price'=>0);
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