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

require_once("page_classes/AUIComponent.php");
require_once("functions/print_func.php");
require_once("classes/MyCart.php");
class Menu extends AUIComponent{

function draw (){
  $cart=$_SESSION['cart'];
   echo "<center>
  <table width='100' class='menu' cellspacing='1' >
  <tr><td align='center' class='menu_title'>".administration."</td></tr>
  <tr><td  class='menu_admin_item'>
  <a href='view_ort.php' class='menu_admin_link'>".ort_admin."</a></td></tr>
  <tr><td  class='menu_admin_item'>
  <a href='view_event.php' class='menu_admin_link'>".event_admin."</a></td></tr>
  <tr><td  class='menu_admin_item'><a href='view_organizer.php' class='menu_admin_link'>".organizer_admin."</a></td></tr>
  <tr><td  class='menu_admin_item'><a href='view_salepoint.php' class='menu_admin_link'>".salepoint_admin."</a></td></tr>
  <tr><td  class='menu_admin_item'><a href='view_template.php' class='menu_admin_link'>".template_admin."</a></td></tr>

  <tr><td  class='menu_admin_item'><a href='search.php' class='menu_admin_link'>".search_order."</a></td></tr>
  <tr><td  class='menu_admin_item'><a href='view_order.php' class='menu_admin_link'>".order_admin."</a></td></tr>
  <tr><td  class='menu_admin_item'><a href='view_stats.php' class='menu_admin_link'>".stats."</a></td></tr>
  <tr><td  class='menu_admin_item'><a href='view_stats.php?action=grafik' class='menu_admin_link'>".graph_stats."</a></td></tr>
  <tr><td  class='menu_admin_item'><a href='view_xldata.php' class='menu_admin_link'>".excel_admin."</a></td></tr>
  <tr><td  class='menu_admin_item'><a href='$_SHOP->php_myadmin' class='menu_admin_link'>PHP MyAdmin</a></td></tr>
 
  <tr><td  class='menu_admin_item'>
  <a href='$PHP_SELF?action=logout' class='menu_admin_link'>".Logout."</a></td></tr>  
  </table>
  </center><br>";  print_cart_summary($cart);
}

}
?>