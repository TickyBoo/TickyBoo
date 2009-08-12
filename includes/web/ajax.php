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


if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
	require_once ( "../includes/config/init_common.php" );
	
	if($_REQUEST['pos'] == true){
		require_once ( "config/init_spoint.php" );
		
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