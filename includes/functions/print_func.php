<?php
/*
%%%copyright%%%
 * phpMyTicket - ticket reservation system
 * Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of phpMyTicket.
 *
 * This file may be distributed and/or modified under the terms of the
 * "GNU General Public License" version 2 as published by the Free
 * Software Foundation and appearing in the file LICENSE included in
 * the packaging of this file.
 *
 * Licencees holding a valid "phpmyticket professional licence" version 1
 * may use this file in accordance with the "phpmyticket professional licence"
 * version 1 Agreement provided with the Software.
 *
 * This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
 * THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE.
 *
 * The "phpmyticket professional licence" version 1 is available at
 * http://www.phpmyticket.com/ and in the file
 * PROFESSIONAL_LICENCE included in the packaging of this file.
 * For pricing of this licence please contact us via e-mail to
 * info@phpmyticket.com.
 * Further contact information is available at http://www.phpmyticket.com/
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@phpmyticket.com if any conditions of this licencing isn't
 * clear to you.

 */

require_once("admin/AdminView.php");
function user_address ($user_data, $width = '500')
{
  echo "<table  class='user_address' width='$width' border='0' cellspacing='0' cellpadding='1'>";
  echo "<tr><td class='user_address_td'>" . $user_data["user_firstname"] . " " . $user_data["user_lastname"] . "</td></tr>";
  echo "<tr><td class='user_address_td'>" . $user_data["user_address"] . "</td></tr>";
  if ($addr = $user_data["user_address1"]){
    echo "<tr><td class='user_address_td'>" . $addr . "</td></tr> ";
  }
  echo "<tr><td class='user_address_td'>" . $user_data["user_zip"] . " " . $user_data["user_city"] . "</td></tr>";
  $country      = $user_data["user_country"];
  $countries    = new AdminView();
  $country_name = $countries->getCountry($country);
  echo "<tr><td class='user_address_td'>$country_name</td></tr>";
  echo "<tr><td class='user_address_td'>" . $user_data["user_email"] . "</td></tr></table>";
}

function login_form ()
{
  echo "<form action='' method='post' name='login'>
       <table width='500' border='0' cellspacing='1' cellpadding='5'>
       <tr>
       <td colspan='2' class='user_title'>" . enter_member . "</td>
       </tr>
       <tr><td class='user_item'>" . email . "</td><td><input type='text' name='username'></td>
       </tr><tr><td class='user_item'>" . password . "</td><td><input type='password' name='password'></td>
       </tr><tr>
       <td  align='right'><input type='submit' name='submit_login' value='" . weiter . "'></td>
       <td align='center'><a href='forgot_password.php'>" . forgot_password . "</a></td>
       </tr>
       </table></form><br><br>
 ";
}

?>