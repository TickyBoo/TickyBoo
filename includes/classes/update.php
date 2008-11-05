<?php

require_once("classes/ShopDB.php");
require_once("classes/Time.php");

class Update {	 
	
	function Update () {
		if(!isset($this->shopconfig_id)){
			$this->load('1');
		}
	}
		
		
	function check_event($event_date){
  		if($this->shopconfig_posttocollect>=10){
			$time=Time::StringToTime($event_date);
			$remain=Time::countdown($time);
			//if there is less than 10 mins till the event needs to go to alt payment return a 1
			// so alt payment should be used.
			//echo $remain["justmins"]."-".$this->shopconfig_posttocollect;
			if($remain["justmins"]<=($this->shopconfig_posttocollect+10)){
				return 1;
			}else{
				return 0;
			}
		}
	}
	
	
	function load ($config_id){
	    global $_SHOP;
	    
	    $query="SELECT * FROM `ShopConfig` 
		WHERE shopconfig_id = ".ShopDB::quote($config_id)." 
		AND shopconfig_organizer_id={$_SHOP->organizer_id} LIMIT 1";
	    if($data=ShopDB::query_one_row($query)){
	      $this->_fill($data);
	      return $this;
	    }
  }
	
  function _fill ($data){
    foreach($data as $k=>$v){
     $this->$k=$v;
    }
  }
}


















	
?>