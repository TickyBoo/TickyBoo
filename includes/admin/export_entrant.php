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

require_once("admin/AdminView.php");
require_once 'Spreadsheet/Excel/Writer.php';

class export_entrant extends AdminView {

   var $query = '';
   
  function xl_form (&$data,&$err){
		global $_SHOP;

		$query = "select * from Event where event_rep LIKE '%sub%' ORDER BY event_date,event_time,event_name";

		if($res=ShopDB::query($query)){
		  while($row=shopDB::fetch_array($res)){
			  $event[$row['event_id']]=formatDate($row['event_date']).'-'.formatTime($row['event_time']).' '.$row['event_name'];
			}
		}

		echo "<form action='{$_SERVER[PHP_SELF]}' method='get'>";
		$this->form_head(export_entrant_title);
		$this->print_select_assoc('export_entrant_event',$data,$err,$event);
		$this->print_checkbox('export_entrant_NotSended',$data,$err);

		echo "
		<tr><td align='center' class='admin_value' colspan='2'>
  		<input type='hidden' name='export_type' value='entrant'>
		<input type='submit' name='submit' value='".export_xml_event_submit."'></td></tr>
		</table></form>";
  }

  function generate_xl ($res, $event){
    global $_SHOP;
    
    $workbook = new Spreadsheet_Excel_Writer();
    // sending HTTP headers
    $workbook->send("Tickets_for_".$event.".xls");
    // Creating a worksheet
    $worksheet =& $workbook->addWorksheet('Tickets');

    $format_bold =& $workbook->addFormat();
    $format_bold->setBold();

    $format_title =& $workbook->addFormat();
    $format_title->setBold();
    $format_title->setPattern(1);
    $format_title->setFgColor(26);
    $format_title->setbottom(1);

    $format_titler =& $workbook->addFormat(array('Align'=>'right'));
    $format_titler->setBold();
    $format_titler->setPattern(1);
    $format_titler->setFgColor(26);
    $format_titler->setbottom(1);

    $format_price =& $workbook->addFormat();
    $format_price->setNumFormat('#,##0.00;-#,##0.00');
    $format_price->setAlign('right');
    
    $format_priceb =& $workbook->addFormat();
    $format_priceb->setNumFormat('#,##0.00;-#,##0.00');
    $format_priceb->setAlign('right');
    $format_priceb->setBold();


    $format_header =& $workbook->addFormat();
    $format_header->setBold();
    $format_header->setSize(15);
    $format_header->setAlign('merge');
    $format_header->setAlign('top');

    $format_header2 =& $workbook->addFormat();
    $format_header2->setBold();
    $format_header2->setAlign('merge');
    $format_header2->setAlign('top');

    $format_left =&$workbook->addFormat(array('Align'=>'left'));

    $format_leftb =&$workbook->addFormat(array('Align'=>'left'));
    $format_leftb->setBold();

		$query = "select * from Event where event_id = {$event}";

		$row=ShopDB::query_one_row($query);
//    $dta = print_r($row, true);
    // The actual data
    $worksheet->hideGridLines();

    $worksheet->setrow(0,30);
    $worksheet->setrow(1,15);
    $worksheet->setcolumn(0, 0,15);
    $worksheet->setcolumn(1, 2,25);
    $worksheet->setcolumn(4, 4,18);
    $worksheet->write(0, 0, export_entrant_title, $format_header);
    $worksheet->write(0, 1, "", $format_header);
    $worksheet->write(0, 2, "", $format_header);
    $worksheet->write(0, 3, "", $format_header);
    $worksheet->write(0, 4, "", $format_header);

    $worksheet->write(1, 0, $row['event_name'],$format_header2 );
    $worksheet->write(1, 1, "",$format_header2 );
    $worksheet->write(1, 2, "",$format_header2 );
    $worksheet->write(1, 3, "",$format_header2 );
    $worksheet->write(1, 4, "",$format_header2 );


    $worksheet->write(2, 1, date." :",$format_bold);
    $worksheet->write(2, 2, formatDate($row['event_date']));
    $worksheet->write(2, 0, "");
    $worksheet->write(2, 3, time." :",$format_bold);
    $worksheet->write(2, 4, formatTime($row['event_time']));

    $worksheet->write(3, 0, order_id,$format_title);
    $worksheet->write(3, 1, FullName,$format_title);
    $worksheet->write(3, 2, city, $format_title);
    $worksheet->write(3, 3, tickets,$format_title);
    $worksheet->write(3, 4, price,$format_titler);

    $totprice=0.0;
    $totseats=0;
    $i=4;
    while($row=shopDB::fetch_assoc($res)){
      $worksheet->write($i, 0, $row['order_id'],$format_left );
      $worksheet->write($i, 1, $row['user_firstname'].' '.$row['user_lastname'],$format_left);
      $worksheet->write($i, 2, $row['user_city'], $format_left);

      $worksheet->write($i, 3 ,$row['seat_count'], $format_left);
      $totseats += $row['seat_count'];
      $price ='';
      If ($row['order_payment_status'] == 'payed') {
        $price .= order_type_payed;
      } else {
        $price .= $row['seat_totall_price'];
        $totprice += $row['seat_totall_price'];
      }
      If ($row['order_shipment_status'] == 'send') {
        $price .= ' ('.order_status_send.')';
      }

      $worksheet->write($i, 4,$price, $format_price);
      $i++;
    }
    $worksheet->write($i, 0, "");
    $worksheet->write($i, 1, "",$format_bold);
    $worksheet->write($i, 2, total_price." :",$format_bold);

    $worksheet->write($i, 3, $totseats, $format_leftb);
    $worksheet->write($i, 4, $totprice, $format_priceb);
/*
    for ($i  = 1; $i <= 65; $i++) {
      $worksheet->write($i, 7 ,$i,$workbook->addFormat(array('FgColor'=>$i, 'pattern'=>1)));
    }
*/
    // Let's send the file

    $workbook->close();  
  }
  
  function export (){
    global $_SHOP;

    if($_GET['submit']) {// and $_GET['export_xl2_event']>0){

			$event_id=_esc((int)$_GET['export_entrant_event']);
      if (!$_GET['expert_entrant_NotSended']) $org .= " and `Order`.order_shipment_status='none'";
      $this->query="SELECT DISTINCT `Order`.order_id,`Order`.order_tickets_nr,`Order`.order_total_price,`Order`.order_shipment_status,`Order`.order_payment_status,`Order`.order_fee,
                              User.user_firstname, User.user_lastname, User.user_city, count(Seat.seat_order_id) AS seat_count, sum(seat_price) as seat_totall_price
              FROM  Seat left JOIN `Order` ON (`Order`.order_id = Seat.seat_order_id)
                         left JOIN User ON (`Order`.order_user_id = User.user_id)
              WHERE `Order`.order_status = 'ord' and seat_event_id=$event_id
              GROUP BY `Order`.order_id, `Order`.order_tickets_nr, `Order`.order_total_price, `Order`.order_shipment_status, `Order`.order_payment_status, `Order`.order_status,
                       `Order`.order_fee, User.user_firstname, User.user_lastname, User.user_city
              ORDER BY User.user_firstname, User.user_lastname";
      if(!$res=ShopDB::query($this->query)){
        return 0;
      }
    $this->generate_xl($res, $event_id);
    return TRUE;
    }
  }
  
  function draw (){
    $this->xl_form($_GET,$this->err);
  }
}
?>