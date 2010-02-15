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

class install_login {
  static function precheck($Install) {
    return  false;
  }

  static function postcheck($Install) {
    return true;
  }

  static function display($Install) {
    Install_Form_Open ($Install->return_pg,'','Login to update you system');
    echo "<table cellpadding=\"1\" cellspacing=\"2\" width=\"100%\">
            <tr>
              <td colspan=\"2\">
                 For security please login with your admin username and password. This is the one that you use to administer the system.
              </td>
            </tr>
            <tr> <td height='6px'></td> </tr>
            <tr>
              <td width='30%'>Admin login:</td>
              <td><input type=\"text\" name=\"username\" value=\"\" /></td>
            </tr>
            <tr>
              <td>Admin password:</td>
              <td><input type=\"password\" name=\"password\" value=\"\" /> (at least 6 letters)</td>
            </tr>
          </table>";
    Install_Form_Buttons ();
    Install_Form_Close ();
  }
}
?>