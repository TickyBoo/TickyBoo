<?php
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2010 Christopher Jenkins, Niels, Lou. All rights reserved.
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
  if(function_exists("mb_internal_encoding")) {
    mb_internal_encoding("UTF-8");
  }
  if(function_exists("date_default_timezone_set")) {
    @date_default_timezone_set($_SHOP->timezone);
  }

  ini_set('memory_limit','64M');
  ini_set('magic_quotes_runtime', 0);
  ini_set('allow_call_time_pass_reference', 0);

// mb_

//check if the site is online
  require_once("classes/basics.php");
  set_error_handler("customError");

  require_once("classes/class.shopdb.php");
  require_once("classes/class.model.php");
  if(isset($_SHOP->auth_required)){
    require_once "Auth/Auth.php";
    require_once "classes/model.admin.php";
  }
  //ini_set('session.save_handler','user');
  //require_once("classes/class.sessions.php");

	//emulates magic_quotes_gpc off
  function stripslashes_deep($value) {
    if(is_array($value)) {
        foreach($value as $k => $v) {
            $return[$k] = stripslashes_deep($v);
        }
    } elseif(isset($value)) {
        $return = stripslashes($value);
    }
    return $return;
  }

  if (get_magic_quotes_gpc()) {
	    $_POST    = array_map('stripslashes_deep', $_POST);
	    $_GET     = array_map('stripslashes_deep', $_GET);
	    $_COOKIE  = array_map('stripslashes_deep', $_COOKIE);
	    $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
	}


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



  trace( $_SERVER["PHP_SELF"]. " [{$_REQUEST['action']}]", true);
  trace( '====================================================================');
 // print_r($_SERVER);
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

//  echo "<pre>";
//  print_r($_SHOP);
//  echo "</pre>";
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

//authentifying (if needed)
  $accepted = true;
  foreach ($_POST as  $key => $value) {
    if (substr($key,0,3) === '___') {
      $key  = substr($key, 3) ;
      $name = substr($key, 0, strpos($key,'_'));
      if (!isset($_SESSION['tokens'][$name])) {
        $accepted = false;
      } else {
        $testme = sha1 ($key.'~'.$_SESSION['tokens'][$name]['n'].'~'.getIpAddress());
        if(strcmp($testme, $value )<>0 ) {
          $accepted = false;
        }
      }
      break;
    }
  }
//  echo  getIpAddress();
  if (!$accepted) {
     $tokens = print_r($_SESSION['tokens'], true);
     writeLog('% Tokens '.(($tokens)?$tokens:'NOT FOUND !!!'));
     writeLog("% Token {$name}, {$value}, {$testme}");
    writeLog('% used IP: '.getIpAddress());
    writeLog(print_r($_SERVER,true));
     writeLog(print_r($_ENV,true));
    writeLog('     ---------------------------------------------------');

     orphancheck();
     session_unset();
     session_destroy();
     $string = "<h1>Access Denied</h1>";
     $string .= "<p><strong>Why?</strong> :- Please check you submitted a form within the same domain (website address).</p>";
     $string .= "<p><strong>Or</strong> :- Your session does not match your url.</p>";
     $string .= "<p>Please check your cookie settings and turn it on.</p>";

     die($string);
  }


//loading language file
  if (isset($_SHOP->lang)) {
    // do noting
    $_SESSION['_SHOP_LANG']=$_SHOP->lang;
  } elseif(isset($_REQUEST['setlang']) ) {
	  if ($lang=$_REQUEST['setlang'] and in_array($lang,$_SHOP->langs)){
  		//  setcookie("lang",$lang,time()+60*60*24*30);
    	$_SHOP->lang=$lang;
    	$_SESSION['_SHOP_LANG']=$_SHOP->lang;
      If (isset($_REQUEST['href'])) {
        header("location:{$_REQUEST['href']}");
        die;
      }
	  }
  }elseif(isset($_SESSION['_SHOP_LANG'])){
    $_SHOP->lang=$_SESSION['_SHOP_LANG'] ;
  }elseif(isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]) && $_SERVER["HTTP_ACCEPT_LANGUAGE"]){
		$lpat=implode($_SHOP->langs,"|");
    if(preg_match_all("/$lpat/",$_SERVER["HTTP_ACCEPT_LANGUAGE"],$res )){
      $newlang = '';
      $langid = 9999;
       foreach ($res[0] as $lang) {
        $x =  array_search($lang, $_SHOP->langs );
        if (($x < $langid) && file_exists(INC."lang".DS."site_". $lang.".inc")) {
          $langid = $x;
          $newlang = $lang;
        }
      }
      $_SHOP->lang=$newlang;
    }else{
      $_SHOP->lang=$_SHOP->langs[0];
      $_SESSION['_SHOP_LANG']=$_SHOP->lang;
    }
  }else{
    $_SHOP->lang=$_SHOP->langs[0];
    $_SESSION['_SHOP_LANG']=$_SHOP->lang;
  }

  If (file_exists(INC."lang".DS."site_". $_SHOP->lang.".inc")){
    include_once(INC."lang".DS."site_". $_SHOP->lang.".inc");
    $_SHOP->langfile = INC."lang".DS."site_". $_SHOP->lang.".inc";
  }else {
    include_once(INC."lang".DS."site_en.inc");
    $_SHOP->langfile = INC."lang".DS."site_en.inc";
  }

 // writeLog($old = setlocale(LC_TIME, NULL));

  $loc = con('setlocale_ALL',' ');
  if(!empty($loc)){
    setlocale(LC_ALL, explode(';',$loc));
  }
  $loc = con('setlocale_TIME',' ');
  if(!empty($loc)){
    setlocale(LC_TIME, explode(';',$loc));
  }

  if(isset($_SHOP->auth_required)){

    //authentication starts here
    $params = array("advancedsecurity"=>false,
                    'sessionName'=> $_SHOP->session_name,
                    );


    $auth_container = new CustomAuthContainer($_SHOP->auth_status);
    $_auth = new Auth($auth_container,$params);//,'loginFunction'
    $_auth ->setLoginCallback('loginCallback');
    $action = is($action, $_REQUEST['action']);
    if ($action == 'logout') {
      $_auth->logout();
      session_unset();
      $_SESSION = array();
      session_destroy();
      $_auth->start();
      orphancheck();
      exit;
    } else {
      $_auth->start();
    }

    if (!$_auth->checkAuth()) {
      orphancheck();
      exit;
    }

    if(isset($_auth->admin)){
      $_SHOP->admin = $_auth->admin;
      unset($res->admin_password);
    } elseif($res = Admins::load($_SESSION['_SHOP_AUTH_ADMIN_ID'])) {
      $_SHOP->admin = $res;
      unset($res->admin_password);
    }
   // print_r($_SESSION);
  }

  //ini_set("session.gc_maxlifetime", [timeinsec]);


//loading organizer attributes
  if(empty($_SESSION['_SHOP_ORGANIZER_DATA'])){
     $_SESSION['_SHOP_ORGANIZER_DATA'] = Organizer::load();
	}

  $_SHOP->organizer_data=(object)$_SESSION['_SHOP_ORGANIZER_DATA'];


  function logincallback ($username, $auth){
    global $_SHOP;
    if($res = $auth->admin){
      $_SESSION['_SHOP_AUTH_USER_NAME']=$username;
      $_SESSION['_SHOP_AUTH_ADMIN_ID']=$res->admin_id;
    //  $res = empt($res->user,$res);
      $_SHOP->admin = $res;
      unset($res->admin_password);
    //  unset($res->_columns);
      $_SESSION['_SHOP_AUTH_USER_DATA']= (array)$res;
    }	else {
      session_destroy();
      orphancheck();
      exit;
    }

    $_SESSION['_SHOP_AUTH_USER_NAME']=$username;
   // echo ini_get("session.gc_maxlifetime");
  }


?>