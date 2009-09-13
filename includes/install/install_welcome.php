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
 
class install_welcome {
  function precheck($Install) {
    return true;
  }
  function postcheck($Install) {

    return true;
  }

  function display($Install) {
    Install_Form_Open ($Install->return_pg,'');
    echo "<h2>Welcome to the FusionTicket Installation Wizard.</h2> ";
    echo "<p align='justify' style='font-size:15px;'>
            FusionTicket is distributed under the GNU GPL v3 Licence. 
            By installing this software you are agreeing to this licence.<br> 
            The Software is \"AS IS\", FusionTicket will not be responsible for any damages or loss of 
            profit caused by this software<br> or any other patch script included with this software.<br><br>
            You are <b>NOT</b> allowed to sell this script but are able to make money from <b>USING</b> it.<br>
            There may be a professional version in the future.<br><br>
            If you need help performing the installation, please refer to the included 
            <a href=\"../install.html\" target=\"_blank\">installation guide</a>.
          </p>
          <p align='justify'  style='font-size:16px;'>
            This web based installer will help you install the software on your web server.<br> 
            To continue with the installation process click the 'Next' button below.
          </p>\n";      
    Install_Form_Buttons ();
    Install_Form_Close ();
  }  
}
?>