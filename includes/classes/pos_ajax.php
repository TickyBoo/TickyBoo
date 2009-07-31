<?php

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
	
	//Will grab cats and discounts at the same time, less chances of errors
	private function getCategories(){
		if(!isset($this->request['category_id'])){
			return false;
		}else{
			$cat_id = &$this->request['category_id'];
		}
		if(!is_numeric($cat_id)){
	 		return false;
		}
			
		$sql = "SELECT *
			FROM Category c,
			Category_stat cs
			WHERE 1=1
			AND c.category_id = cs.cs_category_id
			AND c.category_event_id = "._esc($cat_id);
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
				$placemap = "load me!"; //needs loading method
			}
			$this->json['categories'][] = array('html'=>$option,'numbering'=>$numbering,'placemap'=>$placemap,'price'=>$cat['category_price']); 
		}
		//Finish loading categories and there details lets grab the discounts to...
		
		//Select Events Discounts
		$sql = "SELECT *
			FROM Discount d
			WHERE 1=1
			AND d.discount_event_id = "._esc($cat_id);
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