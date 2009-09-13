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
 
class install_database {
  function precheck($Install) {
    return !$_SESSION['ConfigExist'] or $_SESSION['DB_Error'] or $_SESSION['radio'] == 'NORMAL';
  }

  function postcheck($Install) {
    Install_Request(Array('db_name','db_uname','db_pass', 'db_host', 'db_prefix'),'SHOP');
    if(empty($_SESSION['SHOP']['db_host']))
      {array_push($Install->Errors,'No database hostname specified.');}
    if(empty($_SESSION['SHOP']['db_name']))
      {array_push($Install->Errors,'No database name specified.');}
    if ($Install->Errors) return true;
    $link = OpenDatabase();
    if($link->errno==1049 and $_REQUEST['db_create_now']){
      $link->query('CREATE DATABASE ' . $DB_Database);
      $link->select_db($DB_Database);
      $_SESSION['radio']    = 'NORMAL';
      $_SESSION['db_demos'] = $_REQUEST['db_demos'];
    }
    
    if($link->connect_errno or $link->errno){
      array_push($Install->Errors,'A database connection could not be established using the settings you have provided.<br>'.
                                 'Error code: ', mysqli_connect_error() ,'<br>', mysqli_error($link));
      return true;
    } 
    
    if ($_SESSION['radio']=='UPGRADE' AND $result = $link->Query("SHOW TABLES") AND count( $result->fetch_All()) == 0) {
      array_push($Install->Warning,'This database is empty and can not upgraded.');
      $Install->return_pg = INSTALL_MODE;
      $_SESSION['radio'] = 'NORMAL';
    }
      
    return true;
  }

  function display($Install) {
    Install_Form_Open ($Install->return_pg,'');
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
  
  }  
}
?>