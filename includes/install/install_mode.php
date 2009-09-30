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
 
class install_mode {
  function precheck($Install) {
    $ver = explode('.', phpversion());
//    print_r($ver) ;
    if ($ver[0] != 5){
      array_push($Install->Errors,"FAILED: PHP version 5.2.x is required, You have php version " .phpversion() . ". Please update your server." );
    }elseif ($ver[1] < 2){
      array_push($Install->Warnings,"WARNING: To take full advantage of the power of Fusion Ticket your server must have php 5.2.x .<br> You have php version " . phpversion() . ". Please update your server.");
    }elseif ($ver[1] > 2 or $ver[2] > 10 or $ver[2] == 0){
      array_push($Install->Warnings,"WARNING: this program works best with php version 5.2.x.<br> You have php version " . phpversion() . ". This shouldn't be a problem.");
    }
    if (!function_exists('mysqli_connect')){
      array_push($Install->Errors,"MySQL-Exstentions havent been enabled, this is required for security reasons, its should be fairly standard on any PHP 5 Build it will be taking over from standard mysql function.");
    }
    if (!function_exists('curl_init')){
      array_push($Install->Warnings,"<a href='http://ch2.php.net/manual/en/ref.curl.php'>cURL</a> is not compiled in your php. cURL is used by paypal or authorize.net payment methods - you will be unable to use those. Install curl, or configure another network library in <i>'includes/config/init_common.php'");
    }
    if (!function_exists('iconv')){
      array_push($Install->Warnings,"<a href='http://ch2.php.net/manual/en/ref.iconv.php'>iconv</a> is not compiled in your php. iconv is used to enable non-latin alphabets in PDF templates.");
    }
    if (!function_exists('xml_parser_create')){
      array_push($Install->Warnings,"<a href='http://ch2.php.net/manual/en/ref.xml.php'>xml parser</a> is not compiled in your php. xml parser is used by email and pdf templates - you will be unable to print tickets and send emails");
    }

    if (!function_exists('openssl_seal')){
      array_push($Install->Warnings,"<a href='http://ch2.php.net/manual/en/ref.openssl.php'>openssl</a> is not compiled in your php. openssl is used to encrypt credit card information - you will be unable to use this feature.");
    }

    if (ini_get('register_globals')){
      array_push($Install->Warnings,"For security reasons 'register_globals' should be 'OFF'. Read documentation for explanations.");
    }

    if (ini_get('magic_quotes_gpc')){
      array_push($Install->Warnings,"'magic_quotes_gpc' should be 'OFF' to use fusion ticket safely. All the quoting will be done ".
                                    "insite the program itself. Read documentation for explanations.");
    }

    if (ini_get('safe_mode')){
      array_push($Install->Warnings,"Your php is in 'SAFE MODE'. This can lead to problems during install (unable to create directory errors). Please read documentation.");
    }

    if (!is_writable(ROOT."includes/temp")){
      array_push($Install->Errors,ROOT."includes/temp should be writable by the webserver user.");
    }
    if (!is_writable(ROOT."files")){
      array_push($Install->Errors,ROOT."files should be writable by the webserver user.");
    }

    return $_SESSION['DatabaseExist'] ;
  }

  function postcheck($Install) {
    $_SESSION['radio']    = $_REQUEST['radio'];
    $_SESSION['db_demos'] = $_REQUEST['db_demos'];
    
    return true;
  }


  function display($Install) {
    Install_Form_Open ($Install->return_pg,'return(Validate_Inst_Upgrade());');
    
    if (!$mode = $_SESSION['radio']){
      $mode = 'NORMAL';
    }
    $chk[$mode] = 'checked="checked"';

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
 
  }  
}
?>