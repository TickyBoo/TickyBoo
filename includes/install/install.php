<?php
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2010 Christopher Jenkins, Niels, Lou. All rights reserved.
 *
 * Original Design:
 *  phpMyTicket - ticket reservation system
 *   Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
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
if (!defined('ft_check')) {die('System intrusion ');}

session_start();
if (empty($_REQUEST)) {
  session_destroy();
  session_start();
}
include_once "install_version.php";

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
  <title>Fusion Ticket Installation</title>
  <link rel="stylesheet" type="text/css" href="../css/formatting.css" media="screen" />
  <link rel="stylesheet" type="text/css" href="../css/ui-lightness/jquery-ui-1.7.2.custom.css" media="screen" />
  <script type="text/javascript" src="../scripts/jquery/jquery-1.4.1.min.js"></script>
  <script language="JavaScript">
    function Confirm_Inst_Cancel(){
      if(window.confirm('Cancel The Installation Process ?')){
        window.close ();
        return true;
      }
      return false;
    }
    function Validate_Inst_Upgrade(){
      if (!document.install.radio[0].checked ||
          window.confirm("Full installations removes all tables before the installation start.\n\nContinue The Installation Process ?"))
        {return true} else {return false};
    }
    function Validate_License_page(){
      if (!document.install.sla_radio[0].checked){
        window.alert("You must accept the terms of the software license agreement in order to install and use this software.");
        return false;
      } else {
        return true;
      };
    $(function() {
            $("input[type='submit'] :enabled:first").focus();
        });
    }

  </script>
  <style>
    .err {color:#dd0000;}
    .warn {color:#cc9900;}
    .ok {color:#00dd00;}
  </style>

</head>
<body>
<?php
if (isset($_REQUEST['do']) and $_REQUEST['do']=='Cancel'){
  session_destroy();
  echo "<script>window.location.href='{$_SERVER['PHP_SELF']}';</script>";
  exit;
}


$states = array("install_welcome", "install_license", "install_login", "install_database", "install_mode",
                "install_adminuser", "install_merchant", "install_mail","install_register","install_execute",
                'install_finish');




/**
 * shortcut for / or \ (depending on OS)
 */
if (!defined('DS')) {
  define('DS', DIRECTORY_SEPARATOR);
}
/**
 * absolute filesystem path to the root directory of this framework
 */
if (!defined('ROOT')) {
  define('ROOT',(dirname(dirname(dirname(__FILE__)))).DS);
}
require_once(ROOT."includes".DS."config".DS."defines.php");

 if(function_exists("date_default_timezone_set") and
    function_exists("date_default_timezone_get")) {
   @date_default_timezone_set(@date_default_timezone_get());
 }

$root = "http://" . $_SERVER['HTTP_HOST'];
$root .= substr($_SERVER['SCRIPT_NAME'], 0, - 15);
define ('BASE_URL',$root);

require_once(ROOT."includes".DS."classes".DS."basics.php");
require_once(ROOT."includes/classes/class.shopdb.php");
require_once(ROOT."includes/install/install_base.php");

foreach($states as $id => $name) {
  define(strtoupper($name), $id);
  require_once(dirname(__FILE__).DS."{$name}.php");
}

$_SERVER['PHP_SELF']   = clean($_SERVER['PHP_SELF']   ,'HTML');
if (!defined('PHP_SELF')){
  define('PHP_SELF',$_SERVER['PHP_SELF']);
}

if(!isset($_SESSION['is_started']) or !isset($_REQUEST['inst_pg'])){
  $_REQUEST['inst_pg'] = 0;
  $first = true;
} else $first= false;

$_SESSION['is_started'] = True;

$Install->Errors   = Array ();
$Install->Warnings = Array ();
$Install->return_pg  = $_REQUEST['inst_pg'];
//print_r($Install);
//echo $_REQUEST['inst_mode'],':',$_REQUEST['continue'];
  echo "
    <div id=\"wrap\">
      <div id=\"header\">
        <img src=\"".BASE_URL."/images/logo.png\" border=\"0\"/>
        <h2>Installation Procedure <span style=\"color:red; font-size:14px;\"><i>[".INSTALL_VERSION."]</i></span></h2>
      </div>
";

if ($first) {
  selectnext($Install);
} else {
  switch(is($_REQUEST['inst_mode'],'disp')){
    case 'pre':
      selectnext($Install, isset($_REQUEST['continue']));
      break;

    case 'disp':
      $test = call_user_func(array ($states[$Install->return_pg], 'postcheck'),$Install);
      if((ShowResults($Install,'post')== null) ) {
        if ($test) $Install->return_pg++;
        selectnext($Install);
      }

      break;

    case 'post':
      if (isset($_REQUEST['continue'])){
        $Install->return_pg++;
        selectnext($Install);
      } else {
        call_user_func(array ($states[$Install->return_pg], 'display'),$Install);
      }
      break;
  }
}

function selectnext($Install,$continue = false) {
  global $states;
  $first = true;
  while ($first and $Install->return_pg <= count($states)) {
    if ($continue or call_user_func(array ($states[$Install->return_pg], 'precheck'),$Install)) {
      if(!ShowResults($Install,'pre')) {
        call_user_func(array ($states[$Install->return_pg], 'display'),$Install);
      }
      return;
    } elseif (!ShowResults($Install,'pre')) {
      $Install->return_pg ++;
    } else return;
    $continue  = false;
  }
}
?>
      <div id="footer">
        Powered by <a href="http://fusionticket.org">Fusion Ticket</a> - The Free Open Source Box Office
      </div>
      </div>
    </div>
  </body>
</html>