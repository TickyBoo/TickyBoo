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

class install_adminuser {
  function precheck($Install) {
    return  (!$_SESSION['DatabaseExist'] or $_SESSION['radio']=='NORMAL');
  }

  function postcheck($Install) {
    Install_Request(Array('admin_login','admin_password'));
    if (strlen($_SESSION['admin_login']) < 3){
      array_push($Install->Errors,"You need to fill-in a real Admin login name.");
    }
    if (strlen($_SESSION['admin_password']) < 6){
      array_push($Install->Errors,"Admin password should be at least 6 letters long");
    }
    return true;
  }

  function display($Install) {
    Install_Form_Open ($Install->return_pg,'Validate_Inst_Database()');
    echo "<table cellpadding=\"1\" cellspacing=\"2\" width=\"100%\">
            <tr>
              <td colspan=\"2\">
                <h2>Admin login and password</h2>
                 Please choose the username and the password for the Fusion Ticket super user.<br /><br />
              </td>
            </tr>
            <tr>
              <td width='40%'>Admin login:</td>
              <td><input type=\"text\" name=\"admin_login\" value=\"".$_SESSION['admin_login']."\" /></td>
            </tr>
            <tr>
              <td>Admin password:</td>
              <td><input type=\"password\" name=\"admin_password\" value=\"".$_SESSION['admin_password']."\" /> (at least 6 letters)</td>
            </tr>
          </table>";
    Install_Form_Buttons ();
    Install_Form_Close ();
  }
}
?>