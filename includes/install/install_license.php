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
 
class install_license {
  function precheck($Install) {
    if (!file_exists(ROOT."includes/config/init_config.php")) {
      if (!is_writable(ROOT."includes/config")) {
        array_push($Install->Errors, ROOT."includes/config should be temporarily writable by the webserver user.");
      }
    } elseif (!is_writable(ROOT."includes/config/init_config.php")){
      array_push($Install->Errors, ROOT."includes/config/init_config.php should be temporarily writable by the webserver user.");
    } else {
      $_SESSION['ConfigExist'] = file_exists(ROOT."includes/config/init_config.php") and 
                                 (filesize(ROOT."includes/config/init_config.php")>100);
      If ($_SESSION['ConfigExist']){
        include (ROOT."includes/config/init_config.php");
        $_SESSION['SHOP']  = (Array)$_SHOP;
        $_SESSION['radio'] = 'UPGRADE';
      }
    }
    return true;
  }

  function postcheck($Install) {//int_r($_REQUEST);
    if($_REQUEST['sla_radio']!=1){
      array_push($Install->Errors,"You must accept the terms of the software license agreement in order to install and use this software." );
    }
    return true;
  }

  function display($install) {
    Install_Form_Open ($install->return_pg,'return(Validate_License_page());');
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
  }  
}
?>