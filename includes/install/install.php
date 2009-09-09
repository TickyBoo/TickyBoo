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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
  <title>Fusion Ticket Installation</title>
  <link rel="stylesheet" type="text/css" href="../css/formatting.css" media="screen" />

  <script language="JavaScript">
  	function Confirm_Inst_Cancel()
  		{
      if(window.confirm('Cancel The Installation Process ?'))
        {
        window.close ();
        return true;
        }
      }
  	function Validate_Inst_Upgrade()
  	  {
      if (!document.install.radio[0].checked || window.confirm("Full installations removes all tables before the installation start.\n\nContinue The Installation Process ?"))
        {return true} else {return false};
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
//  print_r($_REQUEST);
  $root = "http://" . $_SERVER['HTTP_HOST'];
  $root .= substr($_SERVER['SCRIPT_NAME'], 0, - 15);
//  print_r($_POST);
echo "
		<div id=\"wrap\">
			<div id=\"header\">
        <img src=\"{$root}/images/fusion.png\" border=\"0\"/>
 				<h2>Installation procedure <span style=\"color:red; font-size:14px;\"><i>[Beta 5]</i></span></h2>
			</div>
			<div id=\"navbar\">
				<ul>
					<li><a>pos_booktickets</a></li>
					<li><a>pos_currenttickets</a></li>
					<li><a href='?action=logout' >logout</a></li>

				</ul>
			</div>
			<div id=\"right\">";
      
	function Install_Form_Open ($target_pg, $onsubmit='', $ispage=true)
		{
    if (!$ispage)
      {
  		echo "<form name='install' method=\"post\" action='$target_pg' onSubmit=\"".$onsubmit."\">\n";
      }
    else
      {
      if (isset($target_pg))
        {$target_pg= "?inst_pg=$target_pg";}
      else
        {$target_pg='';}
  		echo "<form name='install' method=\"post\" action='".$_SERVER['PHP_SELF'].$target_pg."' onSubmit=\"".$onsubmit."\">\n";
  		}
		echo "<table border=0 cellpadding=\"0\" cellspacing=\"0\" width='100%' style=\"height: 400\">";
    echo "<tr ><td colspan=2 valign='top' height='100%' >\n"  ;
		}

	function Install_Form_Close ()
		{echo "</td></tr></table></center></form>\n";}

	function Install_Form_Buttons ()
		{
    echo "</td></tr><tr>\n";
		echo "<td  colspan=2 bgcolor=\"#f5F5f5\" valign=\"bottom\" style='border-top:1px solid #c0c0c0;padding: 5px;' align=\"right\">
            <input type=\"submit\" value=\"Cancel\" name=\"do\" onClick=\"Confirm_Inst_Cancel()\" class=\"UI_Button\" />
            &nbsp;
            <input type=\"submit\" value=\"Next\" name=\"do\" class=\"UI_Submit\" />\n";
		}

	function Install_Form_Rollback ()
		{
    echo "</td></tr><tr>\n";
		echo "<td  colspan=2 bgcolor=\"#f5F5f5\" valign=\"bottom\" style='border-top:1px solid #c0c0c0;padding: 5px;' align=\"right\">
            <input type=\"submit\" value=\"Cancel\" name=\"do\" onClick=\"Confirm_Inst_Cancel()\"  class=\"UI_Button\" />
            &nbsp;
            <input type=\"submit\" value=\"Back\"  name=\"do\" class=\"UI_Submit\" />\n";
		}

  function Install_request($arr)
    {
    foreach ($arr as $info)
      {
      If (isset($_REQUEST[$info]))
        {
		    $_SESSION[$info] = $_REQUEST[$info];
		    }
      }
    }
    
/*
* mysql < dump.sql
*/
function file_to_db($filename)
{
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
  if (isset($_REQUEST['do']) and $_REQUEST['do']=='Cancel')
    {
    session_destroy();
    echo "<script>window.location.href='{$_SERVER['PHP_SELF']}';</script>";
    }

	if(!isset($_SESSION['is_started']) or !isset($_REQUEST['inst_pg']))
		{$_REQUEST['inst_pg']=INST_DEFAULT;}

  $_SESSION['is_started'] = True;

	$Install_Errors = Array ();
	$Install_Warnings = Array ();
//
//  array_push($Install_Warnings,"Your php is in 'SAFE MODE'. This can lead to problems during install (unable to create directory errors). Please read documentation.");
	switch($_REQUEST['inst_pg'])
		{
    case INST_LICENSE:
      $ver = explode('.', phpversion());
      if ($ver[0] != 5){
        array_push($Install_Errors,"FAILED: php version 5 is required, You have php version " .phpversion() );
      }else
      if ($ver[1] > 2 or $ver[2] > 6){
        array_push($Install_Warnings,"WARNING: this program was tested with php version 5.2.6. You have php version " . phpversion() . ". This shouldn't be a problem.");
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
      break;
    
 		case INST_DATABASE:
      $return_pg = INST_DATABASE;

      if (isset($_POST['install_dir']) and $_POST['install_dir']){
        $_SESSION['install_dir'] = $_POST['install_dir'];
      }
      if (!$install_dir = $_SESSION['install_dir']){
        $cwd = getcwd();
        $install_dir = substr($cwd, 0, - 5);
      }
      $_SHOP->install_dir =$install_dir;
      if (!file_exists("$install_dir/includes/config/init_config.php")) {
        if (!is_writable("$install_dir/includes/config")) {
          array_push($Install_Errors,"$install_dir/includes/config should be writable by the webserver user.");
          }
        }
      else
      if (!is_writable("$install_dir/includes/config/init_config.php")){
        array_push($Install_Errors,"$install_dir/includes/config/init_config.php should be writable by the webserver user.");
      }
      if (!is_writable("$install_dir/includes/fonts")){
        array_push($Install_Errors,"$install_dir/includes/fonts should be writable to create the PDF files.");
      }
      if (!is_writable("$install_dir/includes/temp")){
        array_push($Install_Errors,"$install_dir/includes/temp should be writable by the webserver user.");
      }
      if (!is_writable("$install_dir/files")){
        array_push($Install_Errors,"$install_dir/files should be writable by the webserver user.");
      }

      break;
 		case INST_TABLES:
      $return_pg = INST_DATABASE;
			if(empty($_REQUEST['DB_Hostname']))
				{array_push($Install_Errors,'No database hostname specified.');}
			if(empty($_REQUEST['DB_Database']))
				{array_push($Install_Errors,'No database name specified.');}
      break;

 		case INST_UPGRADE:
      $return_pg = INST_TABLES;
      Install_Request(Array('admin_login','admin_password'));
      if (strlen($_SESSION['admin_login']) <> 0){
        if (strlen($_SESSION['admin_password']) < 6){
          array_push($Install_Errors,"Admin password should be at least 6 letters long");
        }
      }
      break;
 		case INST_INSTALL:
      $install_dir = $_SESSION['install_dir'];
      $includes_dir = $install_dir . "/includes";
      if (!$init_config = @file_get_contents("$includes_dir/install/init_config.php")){
        array_push($Install_Errors,"Cannot read master configuration file <i>$includes_dir/install/init_config.php</i>.");
      }
    }
  If ((count($Install_Errors)>0 || count($Install_Warnings)>0) and !isset($_POST['continue']))
    {
    If (count($Install_Errors)==0) $return_pg = $_REQUEST['inst_pg'];
    Install_Form_Open ($return_pg,'');
    if(count($Install_Errors)>0)
			{
			echo "<font size=\"5\" color=\"#3366CC\">Error</font><br><br>\n";
			echo "The installer encountered the following errors:<br>\n";
			for($i=0;$i<count($Install_Errors);$i++){echo "<li class='err'><div>".$Install_Errors[$i]."</div></li>\n";}
			}
		// Handle Warnings
		if(count($Install_Warnings)>0)
			{
			echo "<font size=\"5\" color=\"#3366CC\">Warning !</font><br><br>\n";
			echo "The installer has issued the following warnings:<br>\n";
			for($i=0;$i<count($Install_Warnings);$i++){echo "<li><div class='warn'>".$Install_Warnings[$i]."</div></li>\n";}
  	}
    If (count($Install_Errors)>0)
			{
      Install_Form_Rollback ();
      }
    else
      {
			echo "<input type='hidden' name='continue' value='1' />\n";
      Install_Form_Buttons ();
      }
		Install_Form_Close ();
    }
  else
	switch($_REQUEST['inst_pg'])
		{
		case INST_DEFAULT: /* Default Installation Wizard Screen */
			Install_Form_Open (INST_LICENSE,'');
			echo "<h2>Welcome to the Fusion Ticket Installation Wizard.</h2> ";
			echo "<p>This web based installer will help you install the software on your web server. To continue with the installation process click the Next button below.</p>\n";
			echo "<p>If you need help performing the installation, please refer to the included <a href=\"../Install.\" target=\"_blank\">installation guide</a> located in the root folder of the installation package.</p>\n";
			Install_Form_Buttons ();
			Install_Form_Close ();
		break;

		case INST_LICENSE: /* software License Agreement */
			Install_Form_Open (INST_SYSTEM,'');
      $cwd = getcwd();
      $install_dir = substr($cwd, 0, - 5);
			echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
			echo "<tr>\n<td colspan=\"2\"><b>Software License Agreement</b></td>\n</tr>\n";
			echo "<tr>\n<td colspan=\"2\"><textarea rows=\"10\" cols=\"45\" readonly=readonly class=\"UI_TextArea\" style=\"width:100%;\">\n";
			$fp=@fopen("{$install_dir}/LICENCE",'r');
			echo @fread($fp,filesize("{$install_dir}/LICENCE"));
			@fclose($fp);
			echo "</textarea></td>\n</tr>\n";
			echo "<tr>\n<td colspan=\"2\">\n";
			echo "<input type=\"radio\" name=\"SLA_Agree\" value=\"1\" /> ";
			echo "I Agree and Accept the terms of the license agreement.<br />\n";
			echo "<input type=\"radio\" name=\"SLA_Agree\" value=\"0\" checked=\"checked\" /> ";
			echo "I Disagree with the terms of the license agreement.\n";
			echo "</td>\n</tr>\n";
			echo "</table>\n";
			Install_Form_Buttons ();
			Install_Form_Close ();		
		break;

		case INST_SYSTEM: /* System Requirements & Directory Permissions Check */
			if($_REQUEST['SLA_Agree']!=1)
				{
				Install_Form_Open (INST_LICENSE,'');
				echo "<font size=\"5\" color=\"#3366CC\">Error</font><br />\n";
				echo "You must accept the terms of the software license agreement in order to install and use this software.";
				Install_Form_Rollback ();
				Install_Form_Close ();	
				}
			else
				{
				Install_Form_Open (INST_DATABASE,'');

        $_SESSION['ConfigExist'] = file_exists("../includes/config/init_config.php") and (filesize("../includes/config/init_config.php")>100);
				If ($_SESSION['ConfigExist'])
            {
            include ("../includes/config/init_config.php");

            $_SESSION['DB_Hostname'] = $_SHOP->db_host;
            $_SESSION['DB_Username'] = $_SHOP->db_uname;
            $_SESSION['DB_Password'] = $_SHOP->db_pass;
            $_SESSION['DB_Database'] = $_SHOP->db_name;

            $_SESSION['install_dir'] = $_SHOP->install_dir;
            $_SESSION['root_url']    = $_SHOP->root;
            $_SESSION['radio']       = 'UPGRADE';
            }
        Install_Request(Array('install_dir','root_url'));

        if (!$install_dir = $_SESSION['install_dir']){
          $cwd = getcwd();
          $install_dir = substr($cwd, 0, - 5);
        }
        if (!$root = $_SESSION['root_url']){
          $root = "http://" . $_SERVER['HTTP_HOST'];
          $root .= substr($_SERVER['SCRIPT_NAME'], 0, - 14);
        }

				echo "<table>\n";
				echo "<tr>\n<td colspan=\"2\"><b>Main folder</b></td>\n</tr>\n";
				echo "<tr>\n<td colspan=\"2\">This is the directory where Fusion Ticket is installed.<br>Fix if incorrect.</td>\n</tr>\n";
				echo "<tr>\n<td>Folder:</td>\n";
				echo "<td><input name='install_dir' value='$install_dir' size='50'></td>\n</tr>\n";
				echo "<tr>\n<td colspan=\"2\"><br><b>Base URL</b></td>\n</tr>\n";
				echo "<tr>\n<td colspan=\"2\">This is the URL of FusionTicket files. Fix if incorrect.</td>\n</tr>\n";
				echo "<tr>\n<td>URL:</td>\n";
				echo "<td><input name='root_url' value='$root' size='50'></td>\n</tr>\n";
				echo "</table>\n";

			  Install_Form_Buttons ();
		    Install_Form_Close ();
			  }
		break;

		case INST_DATABASE: /* Database Configuration */
      Install_Request(Array('root_url','install_dir'));
			Install_Form_Open (INST_TABLES,'Validate_Inst_Database()');
			echo "<table>\n";
			echo "<tr>\n<td colspan=\"2\"><b>Database Connection Settings</b></td>\n</tr>\n";
			echo "<tr>\n<td colspan=\"2\">Enter the required database connection information below to allow the installation process to create tables in the specified database.<br><br> </td>\n</tr>\n";
			echo "<tr>\n<td>Hostname</td>\n";
			echo "<td><input type=\"text\" name=\"DB_Hostname\" value=\"".$_SESSION['DB_Hostname']."\" /></td>\n</tr>\n";
			echo "<tr>\n<td>Username</td>\n";
			echo "<td><input type=\"text\" name=\"DB_Username\" value=\"".$_SESSION['DB_Username']."\" /></td>\n</tr>\n";
			echo "<tr>\n<td>Password</td>\n";
			echo "<td><input type=\"text\" name=\"DB_Password\" value=\"".$_SESSION['DB_Password']."\" /></td>\n</tr>\n";
			echo "<tr>\n<td>Database</td>\n";
			echo "<td><input type=\"text\" name=\"DB_Database\" value=\"".$_SESSION['DB_Database']."\" /></td>\n</tr>\n";
			echo "</table>\n";
			Install_Form_Buttons ();
			Install_Form_Close ();		
		break;
		
		case INST_TABLES: /* Table Configuration & Testing */
      Install_Request(Array('DB_Hostname','DB_Username','DB_Password', 'DB_Database', 'TB_Prefix'));

			$DB_Hostname=$_SESSION['DB_Hostname'];
			$DB_Username=$_SESSION['DB_Username'];
			$DB_Password=$_SESSION['DB_Password'];
			$DB_Database=$_SESSION['DB_Database'];
			$pos = strpos($DB_Hostname,':');
      if ($pos!= false) {
         $port = substr($DB_Hostname,$pos+1);
         $DB_Hostname = substr($DB_Hostname,0, $pos);
      } else
        $port = 3306;
			if(!@mysqli_connect ($DB_Hostname, $DB_Username, $DB_Password, $DB_Database, $port))
				{
				Install_Form_Open (INST_DATABASE,'');
				echo "<font size='5' color='#3366CC'>Error</font><br />\n";
				echo "A database connection could not be established using the settings you have provided.<br>";
				echo mysqli_connect_error();
				}
/*			elseif(!@mysql_select_db ($DB_Database))
			  {
				Install_Form_Open (INST_DATABASE,'');
				echo "<font size=\"5\" color=\"#3366CC\">Error</font><br />\n";
				echo "The database could not be selected using the database name you have provided.";
				Install_Form_Rollback ();
				Install_Form_Close ();
				}*/
			else
				{
  			Install_Form_Open (INST_UPGRADE,'Validate_Inst_Database()');
  			echo "<table>\n";
  			echo "<tr>\n<td colspan=\"2\"><b>Admin login and password</b></td>\n</tr>\n";
  			echo "<tr>\n<td colspan=\"2\">Please choose the username and the password for the Fusion Ticket super user.<br>Choose it even if you update, this will be your new login / password.<br><br></td>\n</tr>\n";
  			echo "<tr>\n<td>Admin login:</td>\n";
  			echo "<td><input type=\"text\" name=\"admin_login\" value=\"".$_SESSION['admin_login']."\" /></td>\n</tr>\n";
  			echo "<tr>\n<td>Admin password:</td>\n";
  			echo "<td><input type=\"text\" name=\"admin_password\" value=\"".$_SESSION['admin_password']."\" /> (at least 6 letters)</td>\n</tr>\n";
  			echo "</table>\n";
  			Install_Form_Buttons ();
  			Install_Form_Close ();

        }
      break;
		case INST_UPGRADE: /* Table Configuration & Testing */
      Install_Request(Array('admin_login','admin_password'));
			Install_Form_Open (INST_INSTALL,'return(Validate_Inst_Upgrade());');
      if (!$install_mode = $_SESSION['radio']){
        $install_mode = 'NORMAL';
      }
      $chk[$install_mode] = 'checked="checked"';

			echo "<table>\n";
			echo "<tr>\n<td colspan=\"2\"><b>Install Type</b></td>\n</tr>\n";
			echo "<tr>\n<td colspan=\"2\">The installation process can optionally leave your existing database in-tact in the event you are performing an upgrade. If you wish to leave your existing database unchanged select the \"UPGRADE\" option below, otherwise select the \"FULL INSTALL\" option to continue with a normal installation.<br /><br /></td>\n</tr>\n";
			echo "<tr>\n<td colspan=\"2\">\n";
			echo "<input type=\"radio\" name=\"radio\" value=\"NORMAL\" {$chk['NORMAL']}/> ";
			echo "FULL INSTALL<br />\n";
			echo "<input type=\"radio\" name=\"radio\" value=\"UPGRADE\" {$chk['UPGRADE']} /> ";
			echo "UPGRADE<br />\n";
			echo "</td>\n</tr>\n";
      echo "<tr><td colspan='2'><br>Install demonstration data:  <input type=checkbox name='db_demos' value='1'></td></tr>";
			echo "</table>\n";
			Install_Form_Buttons ();
			Install_Form_Close ();
		break;
		/* Final Installation */
		case INST_INSTALL:
			$install_mode=$_REQUEST['radio'];
      /* Save Config file first */
      $_SHOP->install_dir = $_SESSION['install_dir'];

      function callback($matches)
      {
        return $_SESSION[$matches[1]];
      }
//      print_r($_SESSION);
      $_SESSION['install_dir_esc'] = addslashes($_SESSION['install_dir']);
      $_SESSION['full_include_path'] = get_include_path() . PATH_SEPARATOR . $_SESSION['install_dir'];
      if ($install_mode == 'NORMAL'){
        $_SESSION['old_organizer_id'] = 3;
      }
      $init_config = preg_replace_callback("/%(\w+)%/", "callback", $init_config);
      if (!$fh = fopen("$includes_dir/config/init_config.php", 'w') or !fwrite($fh, $init_config) or !fclose($fh))
        {
        array_push($Install_Errors,"Can not write configuration file <i>$includes_dir/config/init_config.php</i>");
        }
      if (!$Install_Errors)
        {
        if (!file_exists("$includes_dir/tmp/web") and
           (!mkdir("$includes_dir/tmp/web") or !mkdir("$includes_dir/tmp/web/templates_c") or
            !mkdir("$includes_dir/tmp/web/cache") or !mkdir("$includes_dir/tmp/pos") or
            !mkdir("$includes_dir/tmp/pos/templates_c") or !mkdir("$includes_dir/tmp/pos/cache")))
          {
          array_push($Install_Errors,"WARNING: can not create temporary folders under $includes_dir/tmp");
          }
        }

      if (!$Install_Errors)
        {
        require_once("../includes/classes/ShopDB.php");
        if ($install_mode == 'NORMAL')
          {
          $DB_Hostname = $_SESSION['DB_Hostname'];
    			$pos = strpos($DB_Hostname,':');
          if ($pos != false) {
             $port = substr($DB_Hostname,$pos+1);
             $DB_Hostname = substr($DB_Hostname,0, $pos);
          } else
            $port = 3306;

          $_SHOP->link = new mysqli($DB_Hostname,
                                    $_SESSION['DB_Username'],
                                    $_SESSION['DB_Password'],
                                    $_SESSION['DB_Database'],
                                    $port);
          }
        else
          {
            require_once("../includes/config/init_config.php");
            $_SHOP->link = new mysqli($_SHOP->db_host, $_SHOP->db_uname, $_SHOP->db_pass, $_SHOP->db_name);
          }

        if (!$_SHOP->link)
          {
          array_push($Install_Errors,"<div class=err>ERROR: can not connect to the database");
          }
        }

      if(!$Install_Errors and $Install_Type == 'NORMAL')
         {
         $Table_Names = ShopDB::TableList('');
         for ($i=0;$i<count($Table_Names);$i++){
           ShopDB::query("drop table ".$Table_Names[$i]);
           }
         }

      if (!$Install_Errors)
        {
        global  $tbls;
        require_once("../includes/install/install_db.php");
        if ($errors = ShopDB::DatabaseUpgrade($tbls))
          {
          $Install_Errors[] = $errors;
          }
        }

      if (!$Install_Errors and $install_mode == 'NORMAL')
        {
        // import contens of mysqldump to db
        if (!file_to_db("$install_dir/includes/install/phpMyTicket.sql")){
          array_push($Install_Errors,"Can not create database structure!");
          }

        if (!$Install_Errors and $_POST['db_demos']==1) {
          If (!file_to_db("$install_dir/includes/install/demosql.sql")){
            array_push($Install_Errors,"Can not create database structure!");
            }
          }
        }
      if (!$Install_Errors and $_SESSION['admin_login'])
        {
        $query = "update Admin set
                    admin_login='{$_SESSION['admin_login']}',
                    admin_password=md5('{$_SESSION['admin_password']}')
                  where admin_id='3' and
                        admin_status='organizer'";

        if (!shopDB::query($query))
          {
          array_push($Install_Errors,"Admin user can not be created!");
          }
        }
      if (!$Install_Errors)
        {
        // force recompile of templates
        $query = "UPDATE Template set template_status='new'";
        shopDB::query($query);
        }

      if(count($Install_PostErrors)>0)
	          {
		        echo "<font size=\"5\" color=\"#3366CC\">Error</font><br />\n";
		        echo "<p>Some problems were reported during the installation:</p>\n";
		        for($i=0;$i<count($Install_PostErrors);$i++){echo "<li class='err'>".$Install_PostErrors[$i]."</li><br />\n";}
		        }
       else
		        {
			      Install_Form_Open ("$root/index.php",'', false);
		        echo "<b>Installation Completed</b><br />You are now ready to start using Fusion Ticket.<br />\n";
            echo "For the security reasons you should put this file/folder to read-only by webserver:
                  <tt>includes/config/init_config.php</tt>";
            if (is_writable("$install_dir/includes/config")) {
              echo " and <tt>includes/config</tt>";
              }

           echo  "<div>You also have to delete the <tt>inst</tt> folder.</div>
                    <br>

                    <li><a href='$root/admin/index.php' target='_blank'>Go to Admin</a>. <br>
                    <li><a href='$root/pos/index.php' target='_blank'>Go to Demo Sale Point</a><br>
                    <li><a href='$root/control/index.php' target='_blank'>Go to Demo Ticket Control Point</a><br>
                    <br>
                    Please tell us if you run Fusion Ticket on your site by sending us an email to <i>admin at fusionticket dot co dot uk</i>!";

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