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

require_once("classes/AUIComponent.php");

class MenuAdmin extends AUIComponent {

    function draw ()
    {
        global $_SHOP;
        echo "<center>
        <table width='".$this->width."' class='menu_admin' cellspacing='1' >
        <tr><td  class='menu_admin_title'>" . administration . "</td></tr>

        <tr><td  class='menu_admin_item'><a href='index.php' class='menu_admin_link'>" . index_admin . "</a></td></tr>
        <tr><td></td></tr>
        <tr><td  class='menu_admin_item'><a href='view_ort.php' class='menu_admin_link'>" . ort_admin . "</a></td></tr>
        <tr><td  class='menu_admin_item'><a href='view_event_group.php' class='menu_admin_link'>" . event_group_admin . "</a></td></tr>
		    <tr><td  class='menu_admin_item'><a href='view_event.php' class='menu_admin_link'>" . event_admin . "</a></td></tr>
        <tr><td  class='menu_admin_item'><a href='view_order.php' class='menu_admin_link'>" . order_admin . "</a></td></tr>
        <tr><td  class='menu_admin_item'><a href='view_search.php' class='menu_admin_link'>" . search_order . "</a></td></tr>
        <tr><td  class='menu_admin_item'><a href='view_stats.php' class='menu_admin_link'>" . stats . "</a></td></tr>
        <tr><td  class='menu_admin_item'><a href='view_impexp.php' class='menu_admin_link'>" . export_admin . " / " . import_admin . "</a></td></tr>
        <tr><td  class='menu_admin_item'><a href='view_template.php' class='menu_admin_link'>" . template_admin . "</a></td></tr>
        <tr><td  class='menu_admin_item'><a href='view_handling.php' class='menu_admin_link'>" . payment_admin . "</a></td></tr>
        <tr><td  class='menu_admin_item'><a href='view_salepoint.php' class='menu_admin_link'>" . salepoint_admin . "</a></td></tr>
        <tr><td  class='menu_admin_item'><a href='view_control.php' class='menu_admin_link'>" . control_admin . "</a></td></tr>
        <tr><td  class='menu_admin_item'><a href='view_garbage.php' class='menu_admin_link'>" . garbage . "</a></td></tr>";
//        <tr><td  class='menu_admin_item'><a href='view_file.php' class='menu_admin_link'>".file_admin."</a></td></tr>
//    		<tr><td  class='menu_admin_item'><a href='view_options.php' class='menu_admin_link'>" . order_options . "</a></td></tr>
//		    <tr><td  class='menu_admin_item'><a href='view_organizer.php' class='menu_admin_link'>" . organizer_admin . "</a></td></tr>
        /*
       if($_SHOP->is_admin){
         echo "<tr><td  class='menu_admin_item'><a href='$_SHOP->php_myadmin' class='menu_admin_link'>PHP MyAdmin</a></td></tr>";
       }*/
        echo "<tr><td></td></tr>";
        echo "<tr><td  class='menu_admin_item'>
       <a href='{$_SERVER["PHP_SELF"]}?action=logout' class='menu_admin_link'>" . logout . "</a></td></tr>
       </table>
      </center><br>";
    }
}

?>