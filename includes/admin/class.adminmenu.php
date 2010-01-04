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

if (!defined('ft_check')) {die('System intrusion ');}
require_once("classes/AUIComponent.php");

class MenuAdmin extends AUIComponent {

  function draw () {
    global $_SHOP;
		// Get the current filename
		$current_script_name=$_SERVER["SCRIPT_NAME"];
		$exploded_script_name=explode("/",$current_script_name);
		$current_file_name = $exploded_script_name[count($exploded_script_name) - 1];

		// Specify menu items in an array
		//  "file name" 		=> "text_define"    (text define from /includes/lang/site_XX.inc)
		$menu_items = array (
			"index.php" 		=> "index_admin",
			"view_users.php"	=> "users_admin",
			"view_event.php"	=> "event_admin",
			"view_stats.php"	=> "stats",
			"view_order.php"	=> "order_admin",
			"view_template.php"	=> "template_admin",
			"view_handling.php"	=> "payment_admin",
			"view_search.php"	=> "search_order",
			"view_impexp.php"	=> "imp_export_admin",
			"view_garbage.php"	=> "garbage"
		);

		// Begin drawing the menu table
		echo "<center>
        <table width='{$this->width}' class='menu_admin' cellspacing='1' >
        <tr><td  class='menu_admin_title'>" . con('administration') . "</td></tr>";

		// Loop through the menu item array and put the'menu_admin_link_selected'-class on the linkt to current file
		foreach($menu_items as $link => $text){
			echo "<tr><td  class='menu_admin_item'><a href={$link} ";
			if ($link==$current_file_name){
				echo "class='menu_admin_link_selected'>";
			} else {
				echo "class='menu_admin_link'>";
			}
			echo con($text);
			echo "</a></td></tr>";
		}
		// Continue and close the menu table below

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