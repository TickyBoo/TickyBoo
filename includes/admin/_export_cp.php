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

class export_cp extends AdminView {

  function cp_form (&$data,&$err){
		global $_SHOP;
		
		$query = "select * from Event where event_rep LIKE '%sub%'";

		if($res=ShopDB::query($query)){
		  while($row=shopDB::fetch_array($res)){
			  $event[$row['event_id']]=$row['event_name'].' ('.$row['event_date'].')';
			}
		}
		
		echo "<form action='{$_SERVER["PHP_SELF"]}' method='GET'>";
		$this->form_head(export_cp_title);
//function print_select_assoc ($name,&$data,&$err,$opt,$mult=false){
		
		$this->print_select_assoc('export_cp_event',$data,$err,$event);
		$this->print_field('export_cp_file',$data,$err);
		echo "
		<tr><td align='center' class='admin_value' colspan='2'>
  		<input type='hidden' name='export_type' value='cp'>
		<input type='submit' name='submit' value='".export_cp_submit."'></td></tr>
		</table></form>";
  }

    
  function export (){
    global $_SHOP;
  
    if($_GET['submit'] and $_GET['export_cp_event']>0){
			require_once('functions/xmlsql_func.php');
			$event_id=_esc((int)$_GET['export_cp_event'],false);
			
			$what[]=array(
			'table'=>'Event',
			'query'=>"select * from Event where event_id='$event_id'");
			
			$what[]=array(
			'table'=>'PlaceMap2',
			'query'=>"SELECT * FROM `PlaceMap2` WHERE `pm_event_id`='$event_id'");
			
			$what[]=array(
			'table'=>'Category',
			'query'=>"SELECT * FROM `Category` WHERE `category_event_id`='$event_id'");

			$what[]=array(
			'table'=>'PlaceMapPart',
			'query'=>"SELECT * FROM `PlaceMapPart` WHERE `pmp_event_id`='$event_id'");

			$what[]=array(
			'table'=>'PlaceMapZone',
			'query'=>"SELECT PlaceMapZone.* FROM `PlaceMapZone`,PlaceMap2 WHERE `pmz_pm_id`=pm_id and pm_event_id=$event_id");

			$what[]=array(
			'table'=>'Discount',
			'query'=>"SELECT * FROM `Discount` WHERE `discount_event_id`='$event_id'");

			$what[]=array(
			'table'=>'Ort',
			'query'=>"SELECT Ort.* FROM `Event`,Ort WHERE `event_ort_id`=ort_id and event_id='$event_id'");

			$what[]=array(
			'table'=>'Seat',
			'query'=>"SELECT * FROM `Seat` WHERE `seat_event_id`='$event_id'");

			$what[]=array(
			'table'=>'Order',
			'pk'=>'order_id',
			'query'=>"SELECT  DISTINCT `Order`.* FROM Seat,`Order` WHERE seat_event_id='$event_id' and seat_order_id=order_id");

			$what[]=array(
			'table'=>'User',
			'pk'=>'user_id',
			'query'=>"SELECT  DISTINCT User.* FROM Seat,User WHERE seat_event_id='$event_id' and seat_user_id=user_id");


			$what[]=array(
			'table'=>'Color',
			'pk'=>'color_id',
			'query'=>"SELECT DISTINCT Color.* FROM Color,`Category`  WHERE `category_color`=color_id and `category_event_id`='$event_id'");

			$what[]=array(
			'table'=>'Category_stat',
			'pk'=>'cs_category_id',
			'query'=>"SELECT DISTINCT Category_stat.* FROM Category_stat,`Category`  WHERE `cs_category_id`=category_id and `category_event_id`='$event_id'");

			$what[]=array(
			'table'=>'Event_stat',
			'query'=>"SELECT * FROM Event_stat  WHERE `es_event_id`='$event_id'");

			$filename=$_GET['export_cp_file'];
			if(empty($filename)){
			  $filename='event'.$event_id.'.xml';
			}
			$this->write_header($filename);
			
			sql2xml_all($what,SQL2XML_OUT_ECHO);
			
			return TRUE;
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