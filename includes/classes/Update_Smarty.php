<?php

require_once("classes/Time.php");
require_once('classes/Order.php');

class Update_Smarty {
  
	function Update_Smarty (&$smarty) {

		$smarty->register_object("update",$this, array('view','countdown'));
    $smarty->assign_by_ref("update",$this);
    
    //if(!$dont_run){
		//Set to same as $_SHOP->shopconfig_lastrun_int for testing mode.
		if($this->lastrun()<='0'){
      //Checks to see if res time is enabled anything more than 9 will delete
			if($_SHOP->shopconfig_restime >= 10){
  			$run=$this->check_reserved();
  		}
  		if($_SHOP->shopconfig_delunpaid == "Yes"){
  			$run=$this->delete_unpaid();
  		}

  		$run=$this->saveupdate();
		}
		//}
	}
	function __get($key){
    global $_SHOP;
    If (stripos('ShopConfig_', $key) !== false) {
      return $__SHOP->$key;
    }
  }
  
	//Used for returning results so a template can know if a button/item should be enabled
	function view ($params,&$smarty) {

		//check if reserving is enabled
		$enabled['can_reserve']=false;
		$event_date=$params['event_date'];
		if(!$event_date){
			die ("No Event Date");
		}
		if($_SHOP->shopconfig_restime >= 20){
			$enabled['can_reserve']=true;
			
			//check to see if can reserve, adds two days before the reservation would expire, stops
			// people reserving tickets that would expire after the event.
			$time=Time::StringToTime($event_date);
			$remain=Time::countdown($time);
			// edit number to change the offset for reserving, reserved tickets will allways expire 2 days before the event.
			// I would recommend keeping this above 1440, a day before the event.
			if($remain["justmins"]>=($_SHOP->shopconfig_restime+2880)){
				$enabled['can_reserve']=true;
				$use_alt= check_event($event_date);
		   		if($use_alt==true){
					$enabled['can_reserve']=false;
				}
			}
			if($_SHOP->shopconfig_maxres > 1){
				if(isset($_SESSION['_SHOP_USER']) and $user=$_SESSION['_SHOP_USER'] and $user['user_status']==2){
					$query="SELECT * FROM User WHERE user_id=".ShopDB::quote($user['user_id'])." AND user_status='2'";
					if(!$res=ShopDB::query($query)){
						echo "#ERR-NOUSR";
						return FALSE;
					}else{
						$user_query=shopDB::fetch_assoc($res);
						require_once('classes/MyCart_Smarty.php');
						$cart=MyCart_Smarty::overview_f();
						$res_total=$user_query['user_current_tickets']+$cart['valid'];
						
						if($res_total > $_SHOP->shopconfig_maxres){
							$enabled['can_reserve']=false;
							$enabled['maxres']=$_SHOP->shopconfig_maxres;
							$enabled['currentres']=$res_total;
						}else{
							$enabled['can_reserve']=true;
						}
					}
				}
			}
		}
		$smarty->assign("update_view",$enabled);
	}
/*
  function test(){
	$time=Time::StringToTime('2008-02-24 19:00:20');
	$array=Time::countdown($time);
	
	echo("{$array['justmins']} remaining");
	
	$query="SELECT * FROM `Handling` WHERE handling_delunpaid='Yes' ";
	$query2="SELECT * FROM `Handling` LEFT JOIN `Order` 
			ON handling_id=order_handling_id 
			WHERE order_organizer_id={$_SHOP->organizer_id}
			AND handling_delunpaid='Yes' 
		  	AND (now() - interval $ttl minute) > order_date
			AND order_status NOT IN ('trash','res','cancel')  
			AND order_payment_status !='payed' 
			AND order_place!='pos'";
		
  }
*/	
  	function countdown($params,&$smarty){
  		global $_SHOP;
  		
  		if($params['reserved']){
  			$order_id=$this->secure_url_param($params['order_id']);
			$query="SELECT * FROM `Order` WHERE order_id=".ShopDB::quote($order_id)."
					AND order_status NOT IN ('cancel','trash') LIMIT 1";
			if($res=ShopDB::query($query)){
				$result=shopDB::fetch_assoc($res);
				$time=Time::StringToTime($result['order_date']);
				$array=Time::countdown($time,$_SHOP->shopconfig_restime);
			}
		}else{
			$order_id=$this->secure_url_param($params['order_id']);
			$query="SELECT * FROM `Order` WHERE order_id=".ShopDB::quote($order_id)."
					AND order_status NOT IN ('cancel','trash') LIMIT 1";
			if($res=ShopDB::query($query)){
				$result=shopDB::fetch_assoc($res);
				$query="SELECT * FROM `Handling` WHERE handling_id=".ShopDB::quote($result['order_handling_id'])." 
						AND handling_delunpaid='Yes' LIMIT 1 ";
				if($res=ShopDB::query($query)){
					$result2=shopDB::fetch_assoc($res);
					$time=Time::StringToTime($result['order_date']);
					$array=Time::countdown($time,$result2['handling_expires_min']);
				}
				
			}
		}
		if($array){
			$smarty->assign("order_remain",$array);
		}
	}


  // Will delete resevered orders out of time 
  function check_reserved(){
  	global $_SHOP;
  	
	  $where =" order_status NOT IN ('trash','ord','cancel')
		AND order_payment_status !='payed' 
		AND order_shipment_status !='send' 
		AND (NOW() - INTERVAL ".$_SHOP->shopconfig_restime." MINUTE) > order_date ";
	  if($_SHOP->shopconfig_check_pos == 'No'){
	    $where .= " AND order_place !='pos' ";
    }
    $query="SELECT order_id FROM `Order` WHERE $where";

  	if($res=ShopDB::query($query)){
  	  while($row = shopDB::fetch_array($res)){
  			//echo "BANG!<br> ";
  			If (!Order::Check_payment($row['order_id']) and
        	  ($_SHOP->shopconfig_restime >= 10)) {
  			  Order::order_delete($row['order_id']);
        }
  	  }
  	}
  }
  
  //Deletes Unpaid Items, Handling and Config `delunpaid` need to be set to yes to work.
  function delete_unpaid(){
  	global $_SHOP;
  	
  	if($_SHOP->shopconfig_delunpaid == "Yes"){
	  $query="SELECT * FROM `Handling` 
	  			WHERE handling_delunpaid='Yes'
				AND handling_expires_min > 10 ";
	  if($res=ShopDB::query($query)){
	  	//Cycles through Handling's
		while($row=shopDB::fetch_array($res)){
			$query2="SELECT * FROM `Order`
      WHERE (now() - interval ".ShopDB::quote($row['handling_expires_min'])." minute) > order_date
				  AND order_status NOT IN ('trash','res','cancel')  
				  AND order_payment_status !='payed' 
				  AND order_shipment_status !='send' 
				  AND order_place!='pos' 
				  AND order_handling_id=".ShopDB::quote($row['handling_id']);
			if($resord=ShopDB::query($query2)){
			//Cycles through orders to see if they should be canceled!
				while($roword=shopDB::fetch_array($resord)){
				  Order::order_delete($roword['order_id']);
				}		
			}else{
			  	$error['unpaidord']="Could not load unpaid orders for handling id: {$row['handling_id']} !";	
			}	
		}
	  }else{
		$error['Unpaid']="Could not load unpaid handlings";
		return;	
	  }
	}else{
	  return;
	}
  }

  
  // Will check last time the update script was run and return the time in mins
  function lastrun(){
  global $_SHOP;
	$time=Time::StringToTime($_SHOP->shopconfig_lastrun);
	$remain=Time::countdown($time,$_SHOP->shopconfig_lastrun_int);
	$return=$remain['justmins'];
	
	return $return;
  }
  
  function saveupdate($config_id='1'){
  global $_SHOP;
  
	$query="UPDATE `ShopConfig` SET shopconfig_lastrun=NOW() LIMIT 1";
	if(!$data=ShopDB::query($query)){
		$error['save']="Save Error, Could not save lastrun";
		return;		
	}
	return true;
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
  
}

?>