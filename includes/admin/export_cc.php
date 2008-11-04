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
require_once("classes/ShopDB.php");

class export_cc extends AdminView {

  function cc_form (&$data,&$err){
		global $_SHOP;
		$query = "select count(*) as export_cc_count from CC_Info where cc_info_organizer_id='{$_SHOP->organizer_id}'";

		$data=ShopDB::query_one_row($query);
		
		echo "<form action='{$_SERVER["PHP_SELF"]}' method='GET'>";
		$this->form_head(export_cc_title);
		$this->print_field('export_cc_count',$data);
		if($data['export_cc_count']){
      echo "<tr><td align='center' class='admin_value' colspan='2'>
  
	  	<input type='hidden' name='export_type' value='cc'>
			<input type='submit' name='submit' value='".export_cc_submit."'></td></tr>";
		}
    echo "</table></form>";
  }

    
  function export (){
    global $_SHOP;
  
    if($_GET['submit']){
      $query="select * from CC_Info where cc_info_organizer_id='{$_SHOP->organizer_id}'";

		  if(!$res=ShopDB::query($query) or !shopDB::num_rows($res)){
         return 0;
      }
			
			$this->write_header();
			
			while($row=shopDB::fetch_array($res)){
				echo $row[0].':'.$row[1]."\n";
			}
			return TRUE;
    }  
  }
  
  function draw (){
    $this->cc_form($_GET,$this->err);
  }
	
	function write_header(){
		header('Content-type: text/plain');
		header('Content-Disposition: attachment; filename="cc_info.txt"');	
		
	}
}
?>