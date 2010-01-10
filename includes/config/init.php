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
 * This define is used to store the passwords, pleace do not change this after
 * there are uses registrated to the system.
 * This this will invalided all given passwords in the system.
 */
if (!defined('ft_check')) {die('System intrusion ');}

  global $_SHOP;

 if(function_exists("date_default_timezone_set") and
    function_exists("date_default_timezone_get")) {
   @date_default_timezone_set(@date_default_timezone_get());
 }


//check if the site is online
  //require_once("classes/sessions.php");
  require_once("classes/class.shopdb.php");
  require_once("classes/basics.php");
  require_once("classes/model.php");

  $_SERVER['PHP_SELF']   = clean($_SERVER['PHP_SELF']   ,'HTML');
  $_SERVER['REQUEST_URI']= clean($_SERVER['REQUEST_URI'],'HTML');

  if (isset($_SERVER['SCRIPT_URI'])) {

    $_SERVER['SCRIPT_URI'] = clean($_SERVER['SCRIPT_URI'] ,'HTML');
  }
  if (isset($_SERVER['SCRIPT_URL'])) {
    $_SERVER['SCRIPT_URL'] = clean($_SERVER['SCRIPT_URL'] ,'HTML');
  }

  if (!defined('PHP_SELF'))
    define('PHP_SELF',$_SERVER['PHP_SELF']);


  $query="SELECT *, UNIX_TIMESTAMP() as current_db_time FROM ShopConfig LIMIT 1";
  if(!$res=ShopDB::query_one_row($query) or $res['status']==='OFF'){
    if($_SHOP->is_admin){
      $_SHOP->system_status_off=TRUE;

    }else{
      echo "<center>
            <h1>This service is temporarily unavailable</h1>
	          <h3>Please return later</h3></center>";
      exit;
    }
  }
  foreach($res as $key => $value){
    if ($key != 'status') {
      $_SHOP->$key = $value;
    }
  }
//  print_r($res);
//starting a new session

  session_name($_SHOP->session_name);
  session_start();
  If (isset($_SHOP->secure_id) and (!isset($_SESSION['_SHOP_SYS_SECURE_ID']) || ($_SHOP->secure_id <> $_SESSION['_SHOP_SYS_SECURE_ID'] ) )) {
    session_unset();
    $_SESSION = array();
    session_destroy(); //echo 'new session_id';
    session_start();
    $_SESSION['_SHOP_SYS_SECURE_ID'] = $_SHOP->secure_id;
  }
// check the order system for outdated orders and reservations
  check_system();

  if (isset($_REQUEST['action'])) {
    $action=$_REQUEST['action'];
  } elseif(!isset($action)){
    $action=false;
  }
  $_REQUEST['action'] = $action;
  $_GET['action']     = $action;
  $_POST['action']    = $action;

//authentifying (if needed)
  $accepted = true;
  foreach ($_POST as  $key => $value) {
    if (substr($key,0,3) === '___') {
      $key = substr($key,3) ;
      $name = substr($key,0, strpos($key,'_'));
      if (!isset($_SESSION['tokens'][$name])) {
        $accepted = false;
      } else {
        $testme = sha1 ($key.'-'.$_SESSION['tokens'][$name]['n'].'-'.$_SERVER["REMOTE_ADDR"]);
        if($testme !== $value ) {
          $accepted = false;
        }
      }
      break;
    }
  }
  if (!$accepted) {
     session_unset();
     session_destroy();
     die('Access Denied');
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

  $locale = get_loc($_SHOP->lang);
  $old = setlocale(LC_ALL, NULL);

  if (!setlocale(LC_TIME,$locale,$locale.'.utf8',$locale.'.utf-8')) {
    $loc = $locale.'_'.strtoupper($locale);
    if (!setlocale(LC_TIME,$loc, $loc.'.utf8', $loc.'.utf-8')) {
      $loc = $locale.'-'.strtoupper($locale);
      if(!setlocale(LC_TIME,$loc, $loc.'.utf8', $loc.'.utf-8')){
        if(!setlocale(LC_ALL, '')) {
          setlocale(LC_ALL, $old);
        }
      }
    }
  }
  If (file_exists(INC."lang".DS."site_". $_SHOP->lang.".inc")){
    include_once(INC."lang".DS."site_". $_SHOP->lang.".inc");
    $_SHOP->langfile = INC."lang".DS."site_". $_SHOP->lang.".inc";
  }else {
    include_once(INC."lang".DS."site_en.inc");
    $_SHOP->langfile = INC."lang".DS."site_en.inc";
  }

  if(isset($_SHOP->auth_required)){

    //authentication stuff
    require_once "Auth/Auth.php";
    require_once "classes/model.admin.php";

    //authentication starts here
    $params = array("advancedsecurity"=>false );


    $auth_container = new CustomAuthContainer($_SHOP->auth_status);
    $_auth = new Auth($auth_container,$params);//,'loginFunction'
    $_auth ->setSessionName($_SHOP->session_name);
    $_auth ->setLoginCallback('loginCallback');
    is($action,"");
    if ($action == 'logout') {
      $_auth->logout();
      session_unset();
      $_SESSION = array();
      session_destroy();
      $_auth->start();
      exit;
    } else {
      $_auth->start();
    }

    if (!$_auth->checkAuth()) {
      exit;
    }
    $_SHOP->auth = $_auth;
  }

  //ini_set("session.gc_maxlifetime", [timeinsec]);


//loading organizer attributes
  if(empty($_SESSION['_SHOP_ORGANIZER_DATA'])){
     $_SESSION['_SHOP_ORGANIZER_DATA'] = Organizer::load();
	}

  $_SHOP->organizer_data=(object)$_SESSION['_SHOP_ORGANIZER_DATA'];

  function logincallback ($username, $auth){
    global $_shop;
    $query="select * from `Admin` where `admin_login`="._esc($username);
    if($res=shopdb::query($query) and $data=shopdb::fetch_assoc($res)){
      unset($data[ $_shop->auth_password ]);
      $_SESSION['_SHOP_ORGANIZER_DATA']=$data;
    }	else {
      session_destroy();
     exit;
    }

    $_SESSION['_SHOP_AUTH_USER_NAME']=$username;
   // echo ini_get("session.gc_maxlifetime");
  }


?>