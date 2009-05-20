<?PHP
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
require_once('classes/Trash.php');

class GarbageView extends AdminView{

	function garbage_list (){
			
		$this->list_head(garbage,2);
		$stats=Trash::stats();
		
		echo "<tr class='admin_list_row_0'>
		<td class='admin_list_item'>".con('event')."</td>
		<td class='admin_list_item' align='right'>".$stats['event']."</td></tr>";
	
		echo "<tr class='admin_list_row_1'>
		<td class='admin_list_item'>".con('seat')."</td>
		<td class='admin_list_item' align='right'>".$stats['seat']."</td></tr>";
	
		echo "<tr class='admin_list_row_0'>
		<td class='admin_list_item'>".con('order')."</td>
		<td class='admin_list_item' align='right'>".$stats['order']."</td></tr>";
	
		echo "<tr class='admin_list_row_1'>
		<td class='admin_list_item'>".con('unused_guests') ."</td>
		<td class='admin_list_item' align='right'>".$stats['guests']."</td></tr>";

		echo "</table></form>";
	
		echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}?empty=true'>".empty_trash."</a></center>";
	
	}





	function draw () { 
		global $_SHOP;
    
		if($_GET['empty']){
			Trash::empty_trash();
			$this->garbage_list();
		}else{
			$this->garbage_list();
		}
	}
}
?>