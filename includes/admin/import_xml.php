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

class import_xml extends AdminView {

  function cp_form (&$data,&$err){
		global $_SHOP;
				
    echo "<form method='POST' action='{$_SERVER['PHP_SELF']}' enctype='multipart/form-data'>\n";
		$this->form_head(import_xml_title);
		
		echo "<tr><td class='admin_name'  width='40%'>".import_xml_file."</td>
					<td class='admin_value'><input type='file' name='import_xml_file'></td></tr>";

		echo "
		<tr><td align='center' class='admin_value' colspan='2'>
  	<input type='hidden' name='import_type' value='xml'>
		<input type='submit' name='submit' value='".import_xml_submit."'></td></tr>
		</table></form>";
  }

    
  function import (){
    global $_SHOP;
  
		if(!empty($_FILES['import_xml_file']) and !empty($_FILES['import_xml_file']['name']) and !empty($_FILES['import_xml_file']['tmp_name'])){
			require_once('classes/xml2sql.php');
			echo import_xml_title." : ".$_FILES['import_xml_file']['name']." ... ";
			flush();
			xml2xml::xml2sql($_FILES['import_xml_file']['tmp_name']);
			echo done;
			return TRUE;
    }  
  }
  
  function draw (){
    $this->cp_form($_GET,$this->err);
  }
	
}
?>