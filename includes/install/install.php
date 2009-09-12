<?php
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2009 Christopher Jenkins, Niels, Lou. All rights reserved.
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
// error_reporting(E_ALL);
  if (isset($_REQUEST['do']) and $_REQUEST['do']=='Cancel'){
    session_destroy();
    echo "<script>window.location.href='{$_SERVER['PHP_SELF']}';</script>";
    exit;
  }

  define("INSTALL_VERSION","1.3.5 BETA5");

  define("INST_DEFAULT",0);
  define("INST_LICENSE",1);
  define("INST_SYSTEM",2);
  define("INST_DATABASE",3);
  define("INST_LANGUAGE",4);
  define("INST_MAIL",6);
  define("INST_EDITOR",7);
  define("INST_UPGRADE",8);
  define("INST_INSTALL",9);
  define("INST_LOGIN",10);
  define("INST_TABLES",11);

session_start();

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

$root = "http://" . $_SERVER['HTTP_HOST'];
$root .= substr($_SERVER['SCRIPT_NAME'], 0, - 15);


require_once(ROOT."includes".DS."classes".DS."basics.php");
require_once(ROOT."includes/classes/ShopDB.php");

$_SERVER['PHP_SELF']   = clean($_SERVER['PHP_SELF']   ,'HTML');
$_SERVER['REQUEST_URI']= clean($_SERVER['REQUEST_URI'],'HTML');

if (isset($_SERVER['SCRIPT_URI'])) {
  $_SERVER['SCRIPT_URI'] = clean($_SERVER['SCRIPT_URI'] ,'HTML');
}
if (isset($_SERVER['SCRIPT_URL'])) {
  $_SERVER['SCRIPT_URL'] = clean($_SERVER['SCRIPT_URL'] ,'HTML');
}

if (!defined('PHP_SELF')){
  define('PHP_SELF',$_SERVER['PHP_SELF']);
}


function Install_Form_Open ($target_pg, $onsubmit='', $ispage=true){
  if (!$ispage){
    echo "<form name='install' method=\"post\" action='$target_pg' onSubmit=\"".$onsubmit."\">\n";
  }else{
    if (isset($target_pg)){
      $target_pg= "?inst_pg=$target_pg";
    }else{
      $target_pg='';
    }
    echo "<form name='install' method=\"post\" action='".$_SERVER['PHP_SELF'].$target_pg."' onSubmit=\"".$onsubmit."\">\n";
  }
  echo "<table border=0 cellpadding=\"0\" cellspacing=\"0\" width='100%' style=\"height: 400\">";
  echo "<tr ><td colspan=2 valign='top' height='100%' >\n"  ;
}

function Install_Form_Close (){
  echo "</td></tr></table></center></form>\n";
}

function Install_Form_Buttons (){
  echo "</td></tr><tr>\n";
  echo "<td  colspan=2 bgcolor=\"#f5F5f5\" valign=\"bottom\" style='border-top:1px solid #c0c0c0;padding: 5px;' align=\"right\">
          <input type=\"submit\" value=\"Cancel\" name=\"do\" onClick=\"Confirm_Inst_Cancel()\" class=\"UI_Button\" />
          &nbsp;
          <input type=\"submit\" value=\"Next\" name=\"do\" class=\"UI_Submit\" />\n";
}

function Install_Form_Rollback (){
  echo "</td></tr><tr>\n";
  echo "<td  colspan=2 bgcolor=\"#f5F5f5\" valign=\"bottom\" style='border-top:1px solid #c0c0c0;padding: 5px;' align=\"right\">
          <input type=\"submit\" value=\"Cancel\" name=\"do\" onClick=\"Confirm_Inst_Cancel()\"  class=\"UI_Button\" />
          &nbsp;
          <input type=\"submit\" value=\"Back\"  name=\"do\" class=\"UI_Submit\" />\n";
}

function Install_request($arr){
  foreach ($arr as $info){
    If (isset($_REQUEST[$info])){
      $_SESSION['SHOP'][$info] = $_REQUEST[$info];
    }
  }
}

function loginmycheck ($link, $username,$auth){
  $query="SELECT admin_id FROM `Admin` 
          WHERE `admin_login`="._esc($username). "
          AND  `admin_password`="._esc(Md5($auth));
  if($res=ShopDB::query_One_row($query)){
    return True;
  }	else {
    return false;
  }
}

function Opendatabase(){
  global $_SHOP;
  $DB_Hostname = $_SESSION['SHOP']['db_host'];
  $DB_Username = $_SESSION['SHOP']['db_uname'];
  $DB_Password = $_SESSION['SHOP']['db_pass'];
  $DB_Database = $_SESSION['SHOP']['db_name'];
  
  $pos = strpos($DB_Hostname,':');
  if ($pos != false) {
    $DB_Hostname = substr($DB_Hostname,0, $pos);
    $port = substr($DB_Hostname,$pos+1);
  } else
    $port = 3306;
  
  $link = new mysqli($DB_Hostname, $DB_Username, $DB_Password, '', $port);
  $link->select_db($DB_Database);
  $_SHOP->link = $link;
  return $link;
}    
/*
* mysql < dump.sql
*/
function file_to_db($filename){
  if (!$lines = file($filename)){
    echo "<div class=err>ERROR: can not read $filename</div>";
    return 0;
  }
  foreach ($lines as $l){
    if (preg_match("/^\s*(#|--)/", $l)){
      // do no
    }else
    if (preg_match("/;\s*$/", $l)){
      $query = $query . substr($l, 0, - 1);

      if (!shopDB::query($query)){
        echo "<div class=err>ERROR: cannot execute database query</div><pre>$query</pre>";
        return 0;
      }
      $query = '';
    }else{
      $query = $query . $l;
    }
  }
  return 1;
}

function callback($matches){
  return $_SESSION[$matches[1]];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
  <title>Fusion Ticket Installation</title>
  <link rel="stylesheet" type="text/css" href="../css/formatting.css" media="screen" />

  <script language="JavaScript">
    function Confirm_Inst_Cancel(){
      if(window.confirm('Cancel The Installation Process ?')){
        window.close ();
        return true;
      }
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
echo "
  <div id=\"wrap\">
    <div id=\"header\">
      <img src=\"{$root}/images/fusion.png\" border=\"0\"/>
      <h2>Installation Procedure <span style=\"color:red; font-size:14px;\"><i>[Beta 5]</i></span></h2>
    </div>
    <div id=\"navbar\">
      <ul>
      </ul>
    </div>
    <div id=\"right\">";
      

  if(!isset($_SESSION['is_started']) or !isset($_REQUEST['inst_pg'])){
    $_REQUEST['inst_pg']=INST_DEFAULT;
  }

  $_SESSION['is_started'] = True;

  $Install_Errors   = Array ();
  $Install_Warnings = Array ();

  switch($_REQUEST['inst_pg']){
    case INST_UPGRADE:
      $ver = explode('.', phpversion());
      if ($ver[0] != 5){
        array_push($Install_Errors,"FAILED: PHP version 5.2.x is required, You have php version " .phpversion() . ". Please update your server." );
      }elseif ($$ver[1] < 2){
        array_push($Install_Warnings,"WARNING: To take full advantage of the power of Fusion Ticket your server must have php 5.2.x . You have php version " . phpversion() . ". Please update your server.");
      }elseif ($ver[1] > 2 or $ver[2] > 10 or $ver[2] == 0){
        array_push($Install_Warnings,"WARNING: this program works best with php version 5.2.x.  You have php version " . phpversion() . ". This shouldn't be a problem.");
      }
      if (!function_exists('mysqli_connect')){
        array_push($Install_Errors,"MySQL-Exstentions havent been enabled, this is required for security reasons, its should be fairly standard on any PHP 5 Build it will be taking over from standard mysql function.");
      }
      if (!function_exists('curl_init')){
        array_push($Install_Warnings,"<a href='http://ch2.php.net/manual/en/ref.curl.php'>cURL</a> is not compiled in your php. cURL is used by paypal or authorize.net payment methods - you will be unable to use those. Install curl, or configure another network library in <i>'includes/config/init_common.php'");
      }
      if (!function_exists('iconv')){
        array_push($Install_Warnings,"<a href='http://ch2.php.net/manual/en/ref.iconv.php'>iconv</a> is not compiled in your php. iconv is used to enable non-latin alphabets in PDF templates.");
      }
      if (!function_exists('xml_parser_create')){
        array_push($Install_Warnings,"<a href='http://ch2.php.net/manual/en/ref.xml.php'>xml parser</a> is not compiled in your php. xml parser is used by email and pdf templates - you will be unable to print tickets and send emails");
      }

      if (!function_exists('openssl_seal')){
        array_push($Install_Warnings,"<a href='http://ch2.php.net/manual/en/ref.openssl.php'>openssl</a> is not compiled in your php. openssl is used to encrypt credit card information - you will be unable to use this feature.");
      }
      if (ini_get('register_globals')){
        array_push($Install_Warnings,"For security reasons 'register_globals' should be 'OFF'. Read documentation for explanations.");
      }

      if (ini_get('safe_mode')){
        array_push($Install_Warnings,"Your php is in 'SAFE MODE'. This can lead to problems during install (unable to create directory errors). Please read documentation.");
      }

      $install_dir = ROOT;
      if (!is_writable("$install_dir/includes/temp")){
        array_push($Install_Errors,"$install_dir/includes/temp should be writable by the webserver user.");
      }
      if (!is_writable("$install_dir/files")){
        array_push($Install_Errors,"$install_dir/files should be writable by the webserver user.");
      }

      if (!file_exists("$install_dir/includes/config/init_config.php")) {
        if (!is_writable("$install_dir/includes/config")) {
          array_push($Install_Errors,"$install_dir/includes/config should be 	temporarily writable by the webserver user.");
        }
      } elseif (!is_writable("$install_dir/includes/config/init_config.php")){
        array_push($Install_Errors,"$install_dir/includes/config/init_config.php should be temporarily writable by the webserver user.");
      } else {
        $_SESSION['ConfigExist'] = file_exists(ROOT."includes/config/init_config.php") and 
                                   (filesize(ROOT."includes/config/init_config.php")>100);
        If ($_SESSION['ConfigExist']){
          include (ROOT."includes/config/init_config.php");
          $_SESSION['SHOP'] = (Array)$_SHOP;
          $_SESSION['radio']       = 'UPGRADE';
          $_SESSION['ConfigExist'] = True;
        }
      }

      break;
    case INST_SYSTEM:   /* System Requirements & Directory Permissions Check */
    case INST_DATABASE: /* Database Configuration */
      If ($_REQUEST['radio']) {
        $_SESSION['radio']    = $_REQUEST['radio'];
        $_SESSION['db_demos'] = $_REQUEST['db_demos'];
      }
      if (!$_SESSION['DB_Error'] or $_SESSION['radio'] == 'NORMAL') {
        break;
      }

    case INST_TABLES:
      $return_pg = INST_DATABASE;
      Install_Request(Array('db_name','db_uname','db_pass', 'db_host', 'db_prefix'));
      if(empty($_SESSION['SHOP']['db_host']))
        {array_push($Install_Errors,'No database hostname specified.');}
      if(empty($_SESSION['SHOP']['db_name']))
        {array_push($Install_Errors,'No database name specified.');}

      $link = OpenDatabase();
      if($link->errno==1049 and $_REQUEST['db_create_now']){
        $link->query('CREATE DATABASE ' . $DB_Database);
        $link->select_db($DB_Database);
        $_SESSION['radio'] = 'NORMAL';
        $_SESSION['db_demos'] = $_REQUEST['db_demos'];
      }
      
      if($link->connect_errno or $link->errno){
        array_push($Install_Errors,'A database connection could not be established using the settings you have provided.<br>'.
                                   'Error code: ', mysqli_connect_error() ,'<br>', mysqli_error($link));
        break;
      } 

      
      if ($_SESSION['radio']=='UPGRADE' AND $result = $link->Query("SHOW TABLES") AND count( $result->fetch_All()) == 0) {
        array_push($Install_Errors,'This database is empty and can not upgraded.');
        $return_pg = INST_UPGRADE;
        $_SESSION['radio'] = 'NORMAL';
      }
      
      break;

    case INST_LOGIN:
      $return_pg = INST_TABLES;
      $link      = OpenDatabase();

      $_SESSION['admin_login']    = $_REQUEST['admin_login'];
      $_SESSION['admin_password'] = $_REQUEST['admin_password'];
      $result = $link->Query("SHOW TABLES LIKE 'admin'");
      if ($result) {
        if(count($result->fetch_rowdo())<> 1 or !loginmycheck ($link, $_SESSION['admin_login'], $_SESSION['admin_password'])){
          array_push($Install_Errors,"Admin User not found in database.");
        }    
      } elseif (strlen($_SESSION['admin_password']) < 6){
        array_push($Install_Errors,"Admin password should be at least 6 letters long");
      }
      
    case INST_INSTALL:
      $includes_dir = ROOT . "/includes";
      if (!$init_config = @file_get_contents("$includes_dir/install/init_config.php")){
        array_push($Install_Errors,"Cannot read master configuration file <i>$includes_dir/install/init_config.php</i>.");
      }
  }

  If ((count($Install_Errors)>0 || count($Install_Warnings)>0) and !isset($_POST['continue'])){
    If (count($Install_Errors)==0) $return_pg = $_REQUEST['inst_pg'];
    Install_Form_Open ($return_pg,'');
    echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
    if(count($Install_Errors)>0){
      echo "<tr>\n<td colspan=\"2\"><h2><font color=\"#3366CC\">Error</font></h2></td>\n</tr><tr><td>\n";
      echo "The installer encountered the following errors:<br><ul>\n";
      for($i=0;$i<count($Install_Errors);$i++){echo "<li class='err'>".$Install_Errors[$i]."</li>\n";}
      echo "</ul></td></tr>";
    }
    // Handle Warnings
    if(count($Install_Warnings)>0){
      echo "<tr>\n<td colspan=\"2\"><h2><font color=\"#3366CC\">Warning !</font></h2></td>\n</tr><tr><td>\n";
      echo "The installer has issued the following warnings:<br><ul>\n";

      for($i=0;$i<count($Install_Warnings);$i++){echo "<li class='warn'>".$Install_Warnings[$i]."</li>\n";}
      echo "</ul></td></tr>";
    }
    echo "</table>";
    
    If (count($Install_Errors)>0){
      Install_Form_Rollback ();
    } else {
      echo "<input type='hidden' name='continue' value='1' />\n";
      Install_Form_Buttons ();
    }
    Install_Form_Close ();
    
  } else
  switch($_REQUEST['inst_pg']){
    case INST_DEFAULT: /* Default Installation Wizard Screen */
      Install_Form_Open (INST_LICENSE,'');
      echo "<h2>Welcome to the Fusion Ticket Installation Wizard.</h2> ";
      echo "<p align=justify>
              Fusion Ticket is distributed under the GNU GPL v3 Licence. 
              You are agreeing to this licence by installing this software. 
              Therefore FusionTicket will not be responsible for any damages or loss of 
              profit caused by this software or any other patch script included with this software.
            </p>
            <p align=justify>
              This also means under the open software licence any modifications to 
              this script also fall under this licence.<br><br>
              Therefore you are <b>NOT</b> allowed to sell this script but are able to make money from <b>USING</b> it.<br>
              There may be a professional version in the future.
            </p>
            <p align=justify>
              If you need help performing the installation, please refer to the included 
              <a href=\"../install.html\" target=\"_blank\">installation guide</a> located in the root folder of the installation package.
            </p>
            <p align=justify>
              This web based installer will help you install the software on your web server. 
              To continue with the installation process click the 'Next' button below.
            </p>\n";      
      Install_Form_Buttons ();
      Install_Form_Close ();
      break;

    case INST_LICENSE: /* software License Agreement */
      Install_Form_Open (INST_UPGRADE,'return(Validate_License_page());');
      $license = @file_get_contents(ROOT."LICENCE");
      echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
              <tr>
                <td colspan=\"2\">
                  <h2>Software License Agreement</h2>
                </td>
              </tr>
              <tr>
                <td colspan=\"2\">
                  <textarea rows=\"15\" cols=\"45\" readonly=readonly class=\"UI_TextArea\" style=\"width:100%;\">
                    {$license}
                  </textarea>
                </td>
              </tr>
              <tr>
                <td colspan=\"2\">
                  <input type=\"radio\" name=\"sla_radio\" id=\"SLA_Agree_Yes\" value=\"1\" />
                    <label for=\"SLA_Agree_Yes\">I Agree and Accept the terms of the license agreement.</label><br />
                  <input type=\"radio\" name=\"sla_radio\" id=\"SLA_Agree_No\" value=\"0\" checked=\"checked\" />
                    <label for=\"SLA_Agree_No\">I Disagree with the terms of the license agreement.</label>
                </td>
              </tr>
            </table>\n";
      Install_Form_Buttons ();
      Install_Form_Close ();    
      break;
    
    
    case INST_UPGRADE: /* Table Configuration & Testing */
      Install_Request(Array('admin_login','admin_password'));
      Install_Form_Open (INST_SYSTEM,'return(Validate_Inst_Upgrade());');
      
      if (!$install_mode = $_SESSION['radio']){
        $install_mode = 'NORMAL';
      }
      $chk[$install_mode] = 'checked="checked"';

      echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
              <tr>
                <td colspan=\"2\">
                  <h2>Install Type</h2>
                </td>
              </tr>
              <tr>
                <td colspan=\"2\">
                  The installation process can optionally leave your existing database in-tact in the event you are performing an upgrade. If you wish to leave your existing database unchanged select the \"UPGRADE\" option below, otherwise select the \"FULL INSTALL\" option to continue with a normal installation.<br /><br />
                </td>
              </tr>
              <tr>
                <td colspan=\"2\">
                  <input type=\"radio\" name=\"radio\" value=\"NORMAL\" {$chk['NORMAL']}/> FULL INSTALL<br />
                  <input type=\"radio\" name=\"radio\" value=\"UPGRADE\" {$chk['UPGRADE']} /> UPGRADE<br />
                </td>\n
              </tr>
              <tr>
                <td colspan='2'>
                  <br>Install demonstration data:  <input type=checkbox name='db_demos' value='1'>
                </td>
              </tr>
            </table>\n";
      Install_Form_Buttons ();
      Install_Form_Close ();
      break;

    case INST_SYSTEM: /* System Requirements & Directory Permissions Check */

    case INST_DATABASE: /* Database Configuration */
      if (!$_SESSION['DB_Error'] or $_SESSION['radio'] == 'NORMAL') {
        Install_Request(Array('root_url','install_dir'));
        Install_Form_Open (INST_TABLES,'');
        echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
                <tr>
                  <td colspan=\"2\"><h2>Database Connection Settings</h2></td>
                </tr>
                <tr>
                  <td colspan=\"2\">
                    Enter the required database connection information below to allow the installation process to create tables in the specified database.<br><br> 
                  </td>
                </tr>
                <tr>
                  <td width='30%'>Hostname</td>
                  <td><input type=\"text\" name=\"db_host\" value=\"".$_SESSION['SHOP']['db_host']."\" /></td>
                </tr>        
                <tr>
                  <td>Username</td>
                  <td><input type=\"text\" name=\"db_uname\" value=\"".$_SESSION['SHOP']['db_uname']."\" /></td>
                </tr>
                <tr>
                  <td>Password</td>
                  <td><input type=\"text\" name=\"db_pass\" value=\"".$_SESSION['SHOP']['db_pass']."\" /></td>
                </tr>
                <tr>
                  <td>Database</td>
                  <td><input type=\"text\" name=\"db_name\" value=\"".$_SESSION['SHOP']['db_name']."\" /></td>
                </tr>\n";
        if ($_SESSION['DB_Error'] ) {
          $chk = ($_SESSION['db_demos'])?'checked="checked"':'';
          echo "
                <tr>
                  <td><br>Create Database now:</td>
                  <td><br><input type=checkbox name='db_create_now' value='1'></td>
                </tr>
                <tr>
                  <td>Install demonstration data:</td>
                  <td><input type=checkbox name='db_demos' $chk value='1'></td>
                </tr>\n";
        }
        echo "</table>\n";
          
        Install_Form_Buttons ();
        Install_Form_Close ();    
        break;
      }
      
    case INST_TABLES: /* Table Configuration & Testing */
      Install_Form_Open (INST_LOGIN,'Validate_Inst_Database()');
      echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
              <tr>
                <td colspan=\"2\">
                  <h2>Admin login and password</h2>
                </td>
              </tr>
              <tr>
                <td colspan=\"2\">
                  Please choose the username and the password for the Fusion Ticket super user.<br><br>
                </td>
              </tr>
              <tr>
                <td>Admin login:</td>
                <td><input type=\"text\" name=\"admin_login\" value=\"".$_SESSION['admin_login']."\" /></td>
              </tr>
              <tr>
                <td>Admin password:</td>
                <td><input type=\"text\" name=\"admin_password\" value=\"".$_SESSION['admin_password']."\" /> (at least 6 letters)</td>
              </tr>
            </table>";
      Install_Form_Buttons ();
      Install_Form_Close ();
      break;
    /* Final Installation */
    case INST_LOGIN:

    case INST_INSTALL:
      $install_mode=$_SESSION['radio'];
      /* Save Config file first */
      $_SHOP->install_dir = $_SESSION['install_dir'];
break;

      OpenDatabase();
      if (!$_SHOP->link) {
        array_push($Install_Errors,"<div class=err>ERROR: can not connect to the database</div>");
      }

      if(!$Install_Errors and $Install_Type == 'NORMAL'){
        $Table_Names = ShopDB::TableList('');
        for ($i=0;$i<count($Table_Names);$i++){
          ShopDB::query("drop table ".$Table_Names[$i]);
        }
      }

      if (!$Install_Errors){
        global  $tbls;
        require_once("../includes/install/install_db.php");
        if ($errors = ShopDB::DatabaseUpgrade($tbls, true)){
          $Install_Errors[] = $errors;
        }
      }

      if (!$Install_Errors and $install_mode == 'NORMAL'){
        // import contens of mysqldump to db
        if (!file_to_db("$install_dir/includes/install/base_sql.sql")){
          array_push($Install_Errors,"Can not create database structure!");
        }

        if (!$Install_Errors and $_POST['db_demos']==1) {
          If (!file_to_db("$install_dir/includes/install/demo_sql.sql")){
            array_push($Install_Errors,"Can not create database structure!");
          }
        }
        if (!$Install_Errors and $_SESSION['admin_login']) {
          $query = "update Admin set
                      admin_login='{$_SESSION['admin_login']}',
                      admin_password=md5('{$_SESSION['admin_password']}'
                      admin_status='admin')";

          if (!shopDB::query($query)){
            array_push($Install_Errors,"Admin user can not be created!");
          }
        }
      }
      if (!$Install_Errors){
        // force recompile of templates
        $query = "UPDATE Template set template_status='new'";
        shopDB::query($query);
        shopDB::query("UPDATE Template set template_type='systm' where template_name='forgot_passwd'");
        shopDB::query("UPDATE Template set template_type='systm' where template_name='Signup_email'");
        shopDB::query("UPDATE Template set template_type='systm' where template_name='email_res'");
      }

      if(count($Install_PostErrors)>0){
        echo "<tr>\n<td colspan=\"2\"><h2><font color=\"#3366CC\">Error</font></h2></td>\n</tr><tr><td>\n";
        echo "The installer encountered the following errors:<br>\n";
        for($i=0;$i<count($Install_Errors);$i++){echo "<li class='err'><div>".$Install_Errors[$i]."</div></li>\n";}
        echo "</td></tr>";
      }else{
        Install_Form_Open ("$root/index.php",'', false);
        echo "<h2>Installation Completed</h2>You are now ready to start using Fusion Ticket.<br />\n";
        echo "For security reasons you should put this file/folder to read-only by webserver:
              <tt>includes/config/init_config.php</tt>";
        if (is_writable("$install_dir/includes/config")) {
          echo " and <tt>includes/config</tt>";
          }

        echo  "<div>You should also delete the <tt>inst</tt> folder.</div>
                <br>
              <ul>
                <li><a href='$root/admin/index.php' target='_blank'>Go to Admin</a>.</li>
                <li><a href='$root/pos/index.php' target='_blank'>Go to Demo Sale Point</a></li>
                <li><a href='$root/control/index.php' target='_blank'>Go to Demo Ticket Control Point</a></li>
    </ul>
                <br>
                Please tell us if you run Fusion Ticket on your site by sending an email to <i>admin at fusionticket dot co dot uk</i>!";

        Install_Form_Buttons ();
        Install_Form_Close ();
//        session_destroy();
        }

    break;
    }
?>
</td>
</tr>
</table>

      </div>

      <div id="footer">
        Powered by <a href="http://fusionticket.org">Fusion Ticket</a> - The Free Open Source Box Office
      </div>
    </div>
  </body>
</html>