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
 * @author Chris Jenkins
 * @copyright 2008
 */
/**
 * some often used constants that should be part of PHP
 */
define('SECOND', 1);
define('MINUTE', 60 * SECOND);
define('HOUR', 60 * MINUTE);
define('DAY', 24 * HOUR);
define('WEEK', 7 * DAY);
define('MONTH', 30 * DAY);
define('YEAR', 365 * DAY);

/**
 * the two error-level constants 
 */
define('FT_DEBUG', 2);
define('FT_ERROR', 1);



/**
 * print out type and content of the given variable if DEBUG-define (in config/core.php) > 0
 * @param mixed $var     Variable to debug
 * @param boolean $escape  If set to true variables content will be html-escaped
 */
function debug($var = false, $escape = false)
{
   if (DEBUG > 0) {
      print '<pre class="debug">';
      $var = print_r($var, true);
      if ($escape) {
         $var = htmlspecialchars($var);
      }
      print $var . '</pre>';
   }
}

/**
 * Recursively strips slashes from all values in an array
 * @param mixed $value
 * @return mixed
 */
function stripslashes_deep($value)
{
   if (is_array($value)) {
      return array_map('stripslashes_deep', $value);
   } else {
      return stripslashes($value);
   }
}
/**
 * Recursively urldecodes all values in an array
 * @param mixed $value
 * @return mixed
 */
function urldecode_deep($value)
{
   if (is_array($value)) {
      return array_map('urldecode_deep', $value);
   } else {
      return urldecode($value);
   }
}

/** write a string to the log in tmp/logs
 *@param string $what string to write to the log
 *@param int $where log-level to log (default: KATA_DEBUG)
 */
function writeLog($what, $where = FT_DEBUG)
{
   if (DEBUG < 0) { return; }

   $logname = 'error';
   if ($where == FT_DEBUG) {
      $logname = 'debug';
   }

   $h = fopen(INC . 'tmp' . DS . $logname . '.log', 'a');
   if ($h) {
      fwrite($h, date('d.m.Y H:i ') . $what . "\n");
      fclose($h);
   }
}

/**
 * Loads files from the from LIB-directory
 * @param string filename without .php
 */
function uses()
{
  $args = func_get_args();
  foreach ($args as $arg) {
    if (!class_exists($arg)) {
      require_once (CLASSES . strtolower($arg) . '.php');
    }
  }
}

function FindClass($class_name) {
  $class_name = strtolower($class_name);

  If (file_exists(CLASSES . $class_name . '.php')) {
     return CLASSES ;
  }  elseIf (file_exists(CLASSES . 'payments' . DS . $class_name . '.php')) {
     return CLASSES . 'payments' . DS;
  }
}


function __autoload ($class_name ) {
  $class_name = strtolower($class_name);
  If ($path = FindClass($class_name)) {
     require ($path . $class_name . '.php');
  }
}

/**
 * Gets an environment variable from available sources.
 * Used as a backup if $_SERVER/$_ENV are disabled.
 *
 * @param  string $key Environment variable name.
 * @return string Environment variable setting.
 */
function env($key){
   if ($key == 'HTTPS') {
      if (isset($_SERVER) && !empty($_SERVER)) {
         return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on');
      } else {
         return (strpos(env('SCRIPT_URI'), 'https://') === 0);
      }
   }

   if (isset($_SERVER[$key])) {
      return $_SERVER[$key];
   } elseif (isset($_ENV[$key])) {
      return $_ENV[$key];
   } elseif (getenv($key) !== false) {
      return getenv($key);
   }

   if ($key == 'DOCUMENT_ROOT') {
      $offset = 0;
      if (!strpos(env('SCRIPT_NAME'), '.php')) {
         $offset = 4;
      }
      return substr(env('SCRIPT_FILENAME'), 0, strlen(env('SCRIPT_FILENAME')) -
                                               (strlen(env('SCRIPT_NAME')) + $offset));
   }
   if ($key == 'PHP_SELF') {
      return r(env('DOCUMENT_ROOT'), '', env('SCRIPT_FILENAME'));
   }
   return null;
}


/**
 * Merge a group of arrays
 * @param array First array
 * @param array etc...
 * @return array All array parameters merged into one
 */
function am() {
   $result = array();
   foreach (func_get_args() as $arg) {
      if (!is_array($arg)) {
         $arg = array($arg);
      }
      $result = array_merge($result, $arg);
   }
   return $result;
}


/**
 * Convenience method for htmlspecialchars. you should use this instead of echo to avoid xss-exploits
 * @param string $text
 * @return string
 */
function h($text)
{
   if (is_array($text)) {
      return array_map('h', $text);
   }
   return htmlspecialchars($text);
}

/**
 * convenience method to check if given value is set. if so, value is return, otherwise the default
 * @param mixed $arg value to check
 * @param mixed $default value returned if $value is unset
 */
function is(&$arg, $default = null)
{
   if (isset($arg)) {
      return $arg;
   }
   return $default;
}

function con($name) {
   global $_SHOP;
   if (defined($name)) {
      return constant($name);
   } else {
/*      if (is_writable($_SHOP->langfile)){

        $addcon = "<?php\ndefine('$name','$name');\n?>\n";
        file_put_contents($_SHOP->langfile, $addcon,FILE_APPEND);
      } */
      return $name;
   }
}


function MakeUrl($action='', $params='', $ctrl ='', $mod ='') {
  Global $_SHOP;

  $mod  = (!empty($mod)) ?$mod:$_REQUEST['mod'];
  $ctrl = (!empty($ctrl))?$ctrl:$_REQUEST['ctlr'];

  $mod  = (!empty($mod)) ?$mod:'shop';
  $ctrl = (!empty($ctrl))?$ctrl:'main';

  If ($_SHOP->UseRewriteURL) {
    $result = $_SHOP->user_root.$mod;
    if ($ctrl) {
      $result .= '/'.$ctrl;
      if ($action) {
        $result .= '/'.$action;
      }
    }
    IF ($params) {
      $result .= '?'.$params;
    }
  } else {
    $result = $_SHOP->user_root.'?mod='.$mod;
    if ($ctrl) {
      $result .= '&ctrl='.$ctrl;
      if ($action) {
        $result .= '&action='.$action;
      }
    }
    IF ($params) {
      $result .= '&'.$params;
    }
  }
  return $result;
}
	/**
	 * redirect to the given url. if relative the base-url to the framework is added.
	 * @param string url to redirect to
	 * @param int status http status-code to use for redirection (default 303=get the new url via GET even if this page was reached via POST)
	 */
	function Redirect($url, $status = 303) {
    GLOBAL $_SHOP;
		if (function_exists('session_write_close')) {
			session_write_close();
		}

		$pos = strpos($url, '://');
		if ($pos === false) { // is relative url, construct rest
			$url = $_SHOP->root . $url;
		}
    if ($status===true) {
      echo 	"<script type=\"text/javascript\" language=\"JavaScript\">\nwindow.location='".trim($url)."';\n</script>";
    }else{
  		if (is_numeric($status) && ($status >= 100) && ($status < 505)) {
  			header('HTTP/1.1 ' . $status);
  		}
  		header('Location: ' . $url);
    }
	}

	function constructBase() {
		$base = 'http' . (env('https') != '' ? 's' : '') . '://' .
		env('SERVER_NAME') . (env('SERVER_PORT') != '80' ? (':' . env('SERVER_PORT')) : '') .
		(dirname(env('PHP_SELF')));
		if (substr($base, -1, 1) != '/') {
			$base .= '/';
		}
		return $base;
	}

	function _esc ($str, $quote=true){
  		$str = shopDB::escape_string($str);
  		return ($quote)?"'".$str."'":$str;
	}

/**
 * This function creates a md5 password code to allow login true WWW-Authenticate
 *
 */
function md5pass($user,$pass) {
	return '*'.md5($user.':'.AUTH_REALM.':'.$pass);
}

function check_event($event_date){
  require_once("classes/Time.php");

  global $_SHOP;
	if($_SHOP->shopconfig_posttocollect>=10){
	  $time=Time::StringToTime($event_date);
		$remain=Time::countdown($time);
		//if there is less than 10 mins till the event needs to go to alt payment return a 1
		// so alt payment should be used.
		//echo $remain["justmins"]."-".$this->shopconfig_posttocollect;
		if($remain["justmins"]<=($_SHOP->shopconfig_posttocollect+10)){
			return 1;
		}else{
			return 0;
		}
	}
}

function check_system() {
	global $_SHOP;
  require_once("classes/Time.php");
  require_once("classes/Order.php");

  // NS: I moved the current_db_time to the init.php so we have lesser sql calls.
  // also i have moved the error messages to the language file. so the can be translated.
  
  if ($_SHOP->shopconfig_lastrun_int == 0) {
    	return;
	} elseif ( $_SHOP->current_db_time <= $_SHOP->shopconfig_lastrun ) {
    	return;
	}
  	
  //	print_r('run');
	//Checks to see if res time is enabled anything more than 9 will delete
	if ( $_SHOP->shopconfig_restime >= 1 ) {
		$query = "SELECT order_id FROM `Order`
              WHERE order_status NOT IN ('trash','ord','cancel')
          		AND order_payment_status NOT IN ('payed','pending')
          		AND order_shipment_status !='send'
          		AND order_date_expire <= NOW()";
		if ( $_SHOP->shopconfig_check_pos == 'No' ) {
			$query .= " AND order_place != 'pos' ";
		}
		if ( $res = ShopDB::query($query) ) {
			while ( $row = shopDB::fetch_array($res) ) {
				if ( !Order::Check_payment($row['order_id']) and
           ($_SHOP->shopconfig_restime >=	10) ) {
					Order::order_delete( $row['order_id'], 'AutoCancel_order');
				}
			}
		}
	}

	if ( $_SHOP->shopconfig_delunpaid == "Yes" ) {
		$query = "SELECT order_id
              FROM `Handling` left join `Order` on order_handling_id = handling_id
  			      WHERE handling_expires_min > 10
              AND order_date_expire IS NOT NULL
              AND order_date_expire <= NOW()
  			  		AND order_status NOT IN ('trash','res','cancel')
  			  		AND order_payment_status NOT IN ('payed','pending')
  			  		AND order_shipment_status != 'send'
  			  		AND order_place != 'pos'";

    if($resultOrder=ShopDB::query($query)){
			//Cycles through orders to see if they should be canceled!
			while ( $roword = shopDB::fetch_array($resultOrder) ) {
				Order::order_delete( $roword['order_id'], 'AutoCancel_paying');
			}
		}
	}
  //    echo "store";
	$query = "UPDATE `ShopConfig` SET shopconfig_lastrun= UNIX_TIMESTAMP(NOW()+INTERVAL shopconfig_lastrun_int MINUTE) LIMIT 1";
	if ( !$data = ShopDB::query($query) ) {
		die( "Save Error, Could not save lastrun");
		return;
	}
	return true;

}

function formatDate($edate){
   global $_SHOP;
   ereg ("([0-9]{4})-([0-9]{2})-([0-9]{2})", $edate, $regs);
   //$lang=$_SERVER["INTERFACE_LANG"];
   $lang=$_SHOP->lang;
   setlocale(LC_TIME, get_loc($lang));
   $pdate= strftime ("%a %e %b %Y", mktime (0,0,0, $regs[2], $regs[3], $regs[1]));
   return $pdate;
 }

function formatAdminDate($edate,$year4=true){
   ereg ("([0-9]{4})-([0-9]{2})-([0-9]{2})", $edate, $regs);
   If ($year4) {
     $pdate=$regs[3]."-".$regs[2]."-".$regs[1];
   } else {
     $pdate=$regs[3]."-".$regs[2]."-".substr($regs[1], -2);
   }
   return $pdate;
 }

function formatTime($time){
  list($h,$m,$s)=split(":",$time);

  if(strlen($h) or strlen($m)){
    //return strftime("%X",mktime($h,$m));
		return $h."h".$m;
  }
}

function stringDatediff($datefrom, $dateto) {
   $datefrom 	= strtotime($datefrom, 0);
   $dateto 		= strtotime($dateto, 0);

   $difference = $dateto - $datefrom; // Difference in seconds
   return $difference;
}

function subtractDaysFromDate($date,$no_days) {
	$time1  = strtotime($date);
	$res = strtotime((date('Y-m-d', $time1)." -$no_days"."days"));

	return date('Y-m-d', $res);
}

function addDaysToDate($date,$no_days) {
	$time1  = strtotime($date);
	$res = strtotime((date('Y-m-d', $time1)." +$no_days"."days"));

	return date('Y-m-d', $res);
}

function get_loc($lang){
  switch($lang){
    case "de":
      return "de_DE";
      break;
    case "en":
      return "en";
      break;
    case "fr":
      return "fr_FR";
      break;
    case "it":
      return "it_IT";
      break;
  }
}

function strip_tags_in_big_string($textstring){
    while (strlen($textstring) != 0)
        {
        $temptext = strip_tags(substr($textstring,0,1024));
        $safetext .= $temptext;
        $textstring = substr_replace($textstring,'',0,1024);
        }
    return $safetext;
}

function wp_entities($string, $encode = 1){

$a = (int) $encode;
$original = array("'"   ,"\""   ,"#"    ,"("    ,")","'"  );
$entities = array("&%39;","&%34;","&%35;","&#40;","&#41;","&apos;");

if($a == 1)
    return str_replace($original, $entities, $string);
else
    return str_replace($entities, $original, $string);
}

function clean($string, $type='ALL') {
  switch (strtolower($type)) {
    case 'revert':
       return  htmlspecialchars_decode(wp_entities($string,0),ENT_QUOTES );
       break;
    case 'all'  : $string = strip_tags_in_big_string ($string);
    case 'strip': $string = $string;
    case 'html' : $string = wp_entities(htmlentities($string, ENT_QUOTES));
  }
  return $string;
}
/**
 * This function creates a md5 password code to allow login true WWW-Authenticate
 *
 */
function sha1pass($user, $pass) {
	return '*'.sha1(md5($user.':'.AUTH_REALM.':'.$pass).'~'.$user);
}

function is_base64_encoded($data){
  return preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data);
}

?>