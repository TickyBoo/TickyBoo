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

require_once("classes/AUIComponent.php");

class MenuAdmin extends AUIComponent {

    function draw ()
    {
        global $_SHOP;
        echo "<center>
        <table width='".$this->width."' class='menu_admin' cellspacing='1' >
        <tr><td  class='menu_admin_title'>" . con('administration') . "</td></tr>

        <tr><td  class='menu_admin_item'><a href='index.php'       class='menu_admin_link'>" . con('index_admin') . "</a></td></tr>
        <tr><td  class='menu_admin_item'><a href='view_users.php'  class='menu_admin_link'>" . con('users_admin') . "</a></td></tr>
 		    <tr><td  class='menu_admin_item'><a href='view_event.php'  class='menu_admin_link'>" . con('event_admin') . "</a></td></tr>
        <tr><td  class='menu_admin_item'><a href='view_stats.php'  class='menu_admin_link'>" . con('stats') . "</a></td></tr>
        <tr><td  class='menu_admin_item'><a href='view_order.php'  class='menu_admin_link'>" . con('order_admin') . "</a></td></tr>
        <tr><td  class='menu_admin_item'><a href='view_template.php'    class='menu_admin_link'>" . con('template_admin') . "</a></td></tr>
        <tr><td  class='menu_admin_item'><a href='view_handling.php'    class='menu_admin_link'>" . con('payment_admin') . "</a></td></tr>
        <tr><td  class='menu_admin_item'><a href='view_search.php' class='menu_admin_link'>" . con('search_order') . "</a></td></tr>
        <tr><td  class='menu_admin_item'><a href='view_impexp.php'      class='menu_admin_link'>" . con('imp_export_admin') . "</a></td></tr>
        <tr><td  class='menu_admin_item'><a href='view_garbage.php'     class='menu_admin_link'>" . con('garbage') . "</a></td></tr>";
        /*
       if($_SHOP->is_admin){
         echo "<tr><td  class='menu_admin_item'><a href='$_SHOP->php_myadmin' class='menu_admin_link'>PHP MyAdmin</a></td></tr>";
       }*/
        echo "<tr><td></td></tr>";
        echo "<tr><td  class='menu_admin_item'>
       <a href='{$_SERVER["PHP_SELF"]}?action=logout' class='menu_admin_link'>" . con('logout') . "</a></td></tr>
       </table>
      </center><br>";
    }
}

?>