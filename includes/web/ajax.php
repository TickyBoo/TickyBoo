<?php
/**
 * AJAJ will return JSON only!
 * 
 * The class will follow strict rules and load the settings to see if a session is present 
 * if not then will return false with a bad request status
 * 
 * JSON Requests should allways use json_encode(mixed, JSON_FORCE_OBJECT)
 * Its allways good practice to turn the var into an object as 
 * JSON is 'Object Notifaction'
 * 
 */
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
	require_once ( "../includes/config/init_common.php" );
	
	if($_REQUEST['pos'] == true){
		require_once ( "config/init_spoint.php" );
		
		if (isset($_REQUEST['action'])) {
			$r = $_REQUEST;
		    $action = $_REQUEST['action'];
		    require_once("classes/pos_ajax.php");
		    $PosAjax = new PosAjax($r,$action);
		    $result = $PosAjax->callAction();
		}
		if(!$result){
		    $object = array("status" => false, "reason" => 'Missing action request');
		    echo json_encode($object);
		}
	}elseif($_REQUEST['admin'] == true){
		require_once ("../includes/config/init_admin.php");
	}elseif($_REQUEST['test'] == true){
		$object = array("status"=>true,
			"reason"=>"",
			"request"=>"<h1>Test JSON Data</h1>"
			);
		echo json_encode($object);
	}else{
		$object = array("status"=>false,
		"reason"=>'Incorrect Request');
		echo json_encode($object);
	}
}else{
	header("Status: 400");
	echo "This is for AJAX / AJAJ / AJAH requests only, please go else where.";
}
?>