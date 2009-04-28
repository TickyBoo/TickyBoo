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
require_once('functions/datetime_func.php');

class export_templates extends AdminView {

  function cp_form (&$data,&$err){
		global $_SHOP;
		
		$query = "select template_id, template_name, template_type from Template
              order by template_name, template_type";

		if($res=ShopDB::query($query)){
		  while($row=shopDB::fetch_array($res)){
			  $event[$row['template_id']]=$row['template_name'].' ('.$row['template_type'].')';
			}
		}
		
		echo "<form action='".PHP_SELF."' method='GET'>";
		$this->form_head(export_xml_event_title);
//function print_select_assoc ($name,&$data,&$err,$opt,$mult=false){
		
		$this->print_select_assoc('export_template_id',$data,$err,$event);
		$this->print_input('export_template_file',$data,$err);
		echo "
		<tr><td align='center' class='admin_value' colspan='2'>
  		<input type='hidden' name='export_type' value='templates'>
		<input type='submit' name='submit' value='". export_submit ."'></td></tr>
		</table></form>";
  }

    
  function export (){
    global $_SHOP;
  
    if($_GET['submit'] and $_GET['export_template_id']>0){
			require_once('functions/xmlsql_func.php');
			$id=_esc((int)$_GET['export_template_id']);
			

    	if($res=ShopDB::query_one_row("select template_name, template_type, template_text from Template where template_id={$id}")){
  			$filename=$_GET['export_template_file'];
  			if(empty($filename)){
  			  $filename='template_'.$res['template_type'].'_'.$res['template_name'].'.xml';
  			}
   			$this->write_header($filename);

  		  $ret ="<templatefile type='{$res['template_type']}'>". $res['template_text'] .'</templatefile>'."\n";
  			echo $ret;
  			return TRUE;
      }
    }  
  }
  
  function draw (){
    $this->cp_form($_GET,$this->err);
  }
	
	function write_header($filename){
		header('Content-type: text/xml');
		header('Content-Disposition: attachment; filename="'.$filename.'"');	
	}
}
?>