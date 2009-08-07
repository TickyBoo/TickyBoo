<?PHP
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
 
 
require_once("admin/AdminView.php");
require_once('classes/Trash.php');

class GarbageView extends AdminView{

	function garbage_list (){
			
		$this->list_head(con('garbage'),2);
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
	
		echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}?empty=true'>".con('empty_trash')."</a></center><br>";

    GLOBAL $data, $keys;

		include "classes/OrphanCheck.php";

    $space = (count($keys)*60 < $this->width -200)?1:0;


		$this->list_head(con('Record_Orphan_Test'),count($keys)+2+$space);
    print " <tr class='admin_list_header'>
              <th width=130 align='left'>
                Tablename
              </th>
              <th width=50 align='right'>
                ID
              </th>";
      foreach ($keys as $key) {
        print "<th width=60 align='center'> {$key}&nbsp;</th>";
      }
      if ($space) {
        print "<th align='center'>&nbsp;</th>";
      }

      print "</tr>";
      $alt =0;
      foreach ($data as $row) {
      
        print "<tr class='admin_list_row_$alt'>
          <td class='admin_list_item'>{$row['_table']}</td>
          <td class='admin_list_item' align='right'>{$row['_id']}</td>\n";
        foreach ($keys as $key) {
          print "<td align='center'>{$row[$key]}&nbsp;</td>\n";
        }
        if ($space) {
          print "<th align='center'>&nbsp;</th>";
        }
        print "</tr>";
        $alt = ($alt + 1) % 2;
      }
      print "</table>";

	
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