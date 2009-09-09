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

$fond = 0;

if($_REQUEST['pos']) {
  require_once ( 'pos_template.php');
} else {
  require_once ( 'template.php');
}

if (!function_exists('json_encode')) {
	
	function array2json($arr) {
	    $parts = array();
	    $is_list = false;
	
	    //Find out if the given array is a numerical array
	    $keys = array_keys($arr);
	    $max_length = count($arr)-1;
	    if(($keys[0] == 0) and ($keys[$max_length] == $max_length)) {//See if the first key is 0 and last key is length - 1
	        $is_list = true;
	        for($i=0; $i<count($keys); $i++) { //See if each key correspondes to its position
	            if($i != $keys[$i]) { //A key fails at position check.
	                $is_list = false; //It is an associative array.
	                break;
	            }
	        }
	    }
	
	    foreach($arr as $key=>$value) {
	        if(is_array($value)) { //Custom handling for arrays
	            if($is_list) $parts[] = array2json($value); /* :RECURSION: */
	            else $parts[] = '"' . $key . '":' . array2json($value); /* :RECURSION: */
	        } else {
	            $str = '';
	            if(!$is_list) $str = '"' . $key . '":';
	
	            //Custom handling for multiple data types
	            if(is_numeric($value)) $str .= $value; //Numbers
	            elseif($value === false) $str .= 'false'; //The booleans
	            elseif($value === true) $str .= 'true';
	            else $str .= '"' . addslashes($value) . '"'; //All other things
	            // :TODO: Is there any more datatype we should be in the lookout for? (Object?)
	
	            $parts[] = $str;
	        }
	    }
	    $json = implode(',',$parts);
	    
	    if($is_list) return '[' . $json . ']';//Return numerical JSON
	    return '{' . $json . '}';//Return associative JSON 
	}
	
	function json_encode($arr){
		return array2json($arr);
	}
}



if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

	if($_REQUEST['pos'] == true){
		if (isset($_REQUEST['action'])) {
			$r = $_REQUEST;
		    $action = clean($_REQUEST['action']); //need to be cleaned so no false data can be included.
		    require_once("classes/pos_ajax.php");
		    $PosAjax = new PosAjax($r,$action);
		    $result = $PosAjax->callAction();
		}
		if(!$result){
		    $object = array("status" => false, "reason" => 'Missing action request');
		    echo json_encode($object);
		}
	}elseif($_REQUEST['admin'] == true){

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