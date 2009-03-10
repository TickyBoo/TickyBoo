<?php
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 * Copyright (C) 2007-2008 Christopher Jenkins. All rights reserved.
 *
 * Original Design:
 *	phpMyTicket - ticket reservation system
 * 	Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of fusionTicket.
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
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@noctem.co.uk if any conditions of this licencing isn't 
 * clear to you.
 */

  global $_SHOP;

  If (!defined('PHP_SELF')) define('PHP_SELF',$_SERVER['PHP_SELF']);

//check if the site is online  
  require_once("classes/ShopDB.php");
  
  $query="SELECT status FROM ShopConfig LIMIT 1";
  if(!$res=ShopDB::query_one_row($query) or $res['status']=='OFF'){
    if($_SHOP->is_admin){
      $_SHOP->system_status_off=TRUE;
      
    }else{
      echo "<center>
            <h1>This service is temporarily unavailable</h1>
	    <h3>Please return later</h3></center>";
      exit;
    }   	  
  }
  
//starting a new session

  session_name($_SHOP->session_name);
  session_start();

  if (isset($_REQUEST['action'])) {$action=$_REQUEST['action'];} else { $action=false;}

//authentifying (if needed)

  if(isset($_SHOP->auth_required)){
  
    if(!isset($_SHOP->auth_dsn)){
      $_SHOP->auth_dsn="mysql://".$_SHOP->db_uname.":".$_SHOP->db_pass."@".$_SHOP->db_host."/".$_SHOP->db_name;
    }
  
    //authentication stuff
    require_once "Auth/Auth.php";  
  
    //this function shows the login-password dialog
    //languages stuff is loaded after, so it is not internationalized  
    function loginFunction (){
			echo "<html><head>
      <meta HTTP-EQUIV=\"content-type\" CONTENT=\"text/html; charset=UTF-8\">
			</head>
			<body>
      <center><form method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "?login=1\">
      <table style='border: #cccccc 1px solid;' cellpadding='5' cellespacing='0'>
       <tr><td>User</td><td><input type=\"text\" name=\"username\"></td></tr>
      <tr><td>Password</td><td><input type=\"password\" name=\"password\"></td></tr>
      <tr><td>Language</td><td><select name='setlang'>";

			global $_SHOP;
			foreach($_SHOP->langs_names as $lang=>$name){
				echo"<option value='$lang'>$name</option>";
			}
			echo "</select></td></tr>";
      echo "<tr><td colspan='2' align='center'><input type=\"submit\" value='login'></td></tr></table>";
      echo "</form></center></body></html>";
    }  
 
    function loginCallback ($username,$auth){
      global $_SHOP; 
      require_once("classes/ShopDB.php");
      $query="SELECT * FROM `{$_SHOP->auth_table}` WHERE `{$_SHOP->auth_login}`='$username'";
      if($res=ShopDB::query($query) and $data=shopDB::fetch_assoc($res)){
        unset($data[ $_SHOP->auth_password ]);
        $_SESSION['_SHOP_AUTH_USER_DATA']=$data;
      }	else {
        session_destroy();
	      exit;
      }
      
      $_SESSION['_SHOP_AUTH_USER_NAME']=$username;
   }
  
    //authentication starts here
    $params = array("dsn" => $_SHOP->auth_dsn,
      'table' =>$_SHOP->auth_table,
      'usernamecol' =>$_SHOP->auth_login,
      'passwordcol' =>$_SHOP->auth_password);  
     
    $_auth = new Auth('DB',$params,'loginFunction');
    $_auth ->setSessionName($_SHOP->session_name); 
    $_auth ->setLoginCallback('loginCallback'); 
    if ($action == 'logout') {
      $_auth->logout();
      session_destroy();
      $_auth->start();
      exit;
    } else {
      $_auth->start();
    }

    if (!$_auth->getAuth()) {
      exit;
    }
    
    $_SHOP->auth = $_auth; 
  }


//loading language file
  
  if(isset($_REQUEST['setlang']) ) {
	  if ($lang=$_REQUEST['setlang'] and in_array($lang,$_SHOP->langs)){
  		//  setcookie("lang",$lang,time()+60*60*24*30);
    	$_SHOP->lang=$lang;
    	$_SESSION['_SHOP_LANG']=$_SHOP->lang;
      If (isset($_REQUEST['href'])) {
        header("location:{$_REQUEST['href']}");
        die;
      }
	  }
  }else if(isset($_SESSION['_SHOP_LANG'])){
    $_SHOP->lang=$_SESSION['_SHOP_LANG'] ;
  }else if($_SERVER["HTTP_ACCEPT_LANGUAGE"]){
		$lpat=implode($_SHOP->langs,"|");
    if(preg_match("/$lpat/",$_SERVER["HTTP_ACCEPT_LANGUAGE"],$res)){
     $_SHOP->lang=$res[0]; 
    }else{
      $_SHOP->lang=$_SHOP->langs[0];
      $_SESSION['_SHOP_LANG']=$_SHOP->lang;
    } 
  }else{
    $_SHOP->lang=$_SHOP->langs[0];
    $_SESSION['_SHOP_LANG']=$_SHOP->lang;
  }
	
	if(isset($_SHOP->langs_locales[$_SHOP->lang])) {
	  $locale=$_SHOP->langs_locales[$_SHOP->lang];
	}else{ $locale = '';}
	if(!$locale){
		$locale=$_SHOP->lang;
	}
  setlocale(LC_TIME,$locale) or
  setlocale(LC_TIME,$locale.'_'.strtoupper($locale));
  If (file_exists(INC."lang".DS."site_". $_SHOP->lang.".inc")){
    include_once(INC."lang".DS."site_". $_SHOP->lang.".inc");
  }else {
    include_once(INC."lang".DS."site_en.inc");
  }

//loading organizer attributes
  if(empty($_SESSION['_SHOP_ORGANIZER_DATA'])){
    require_once("classes/ShopDB.php");
    $query="SELECT * FROM Organizer LIMIT 1";
		
    if($res=ShopDB::query($query) and $data=shopDB::fetch_object($res)){
      $_SESSION['_SHOP_ORGANIZER_DATA']= $data;
      $_SESSION['_SHOP_ORGANIZER_ID']  = $data->organizer_id;
		}
	}
  $_SHOP->organizer_data=$_SESSION['_SHOP_ORGANIZER_DATA'];
  $_SHOP->organizer_id = $_SESSION['_SHOP_ORGANIZER_ID'];
?>