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
require_once(dirname(dirname(__FILE__)).DS."admin".DS."class.adminview.php");

class install_merchant  {
  function precheck($Install) {
    return  ($_SESSION['radio']=='NORMAL');
  }

  function postcheck($Install) {
    $link      = OpenDatabase();
    if(!loginmycheck ($link, $_POST['username'], $_POST['password'])){
      array_push($Install->Errors,"Admin User not found in database.");
    }
    return true;
  }

  function display($Install) {
    define("organizer_name","Name");
    define("organizer_address","Address");
    define("organizer_plz","Zip");
    define("organizer_ort","City");
    define("organizer_state","State");
    define("organizer_country","Country");
    define("organizer_phone","Phone");
    define("organizer_fax","Fax");
    define("organizer_email","E-Mail");
    define("organizer_currency","Currency");
    define("organizer_logo","Merchant's Logo");
    define("organizer_remove_image","Remove Logo");

    Install_Form_Open ($Install->return_pg,'');
  	echo "<form method='POST' action='{$_SERVER['PHP_SELF']}' enctype='multipart/form-data'>\n";
    echo "<table cellpadding=\"1\" cellspacing=\"2\" width=\"100%\">
            <tr>
              <td colspan=\"2\">
                <h2>Merchant Detail Settings</h2>
                Enter our required merchant details. This information can later be changed within the admin section.<br>
              </td>
            </tr>";

    AdminView::print_input('organizer_name'   ,$_SESSION, $err,25,100);
    AdminView::print_input('organizer_address',$_SESSION, $err,25,100);
    AdminView::print_input('organizer_plz'    ,$_SESSION, $err,25,100);
    AdminView::print_input('organizer_ort'    ,$_SESSION, $err,25,100);
    AdminView::print_input('organizer_state'  ,$_SESSION, $err,25,100);
    AdminView::print_countrylist('organizer_country', $_SESSION, $err);
    AdminView::print_input('organizer_phone'  ,$_SESSION, $err,25,100 );
    AdminView::print_input('organizer_fax'    ,$_SESSION, $err,25,100 );
    AdminView::print_input('organizer_email'  ,$_SESSION, $err,25,100 );
    AdminView::print_input('organizer_currency',$_SESSION, $err,4,3 );

    AdminView::print_file('organizer_logo'     ,$_SESSION, $err);
    echo " </table>";
    Install_Form_Buttons ();
    Install_Form_Close ();
  }
}
?>