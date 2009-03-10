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

session_cache_limiter("must-revalidate");

require_once("../includes/config/init_admin.php");
require_once("classes/ShopDB.php");
require_once 'Spreadsheet/Excel/Writer.php';

global $_SHOP;


  if(isset($_GET["event_id"])) $event_id=$_GET["event_id"];
  $query="SELECT * FROM (Event,Ort,Event_stat) WHERE Event.event_id='$event_id' AND Event.event_ort_id=Ort.ort_id AND Event_stat.es_event_id=Event.event_id
    AND Event.event_organizer_id='{$_SHOP->organizer_id}'
		AND Event_stat.es_organizer_id='{$_SHOP->organizer_id}'";
		 
   if(!$res=ShopDB::query($query) or !$event=ShopDB::fetch_assoc($res)){
     //user_error(mysql_error());
     return 0;
   }
   
    // Creating a workbook
    $workbook = new Spreadsheet_Excel_Writer();
    // sending HTTP headers
    $workbook->send($event["event_name"]."-".$event["event_date"].".xls");
    // Creating a worksheet
    $worksheet =& $workbook->addWorksheet('Event Data');
    // The actual data
    foreach($event as $k=>$v){
     $worksheet->write($i, 0, $k);
     $worksheet->write($i, 1, $v);
     $i++;
    }
    
    $worksheet1 =& $workbook->addWorksheet('Category Data');
  
    $query="SELECT * FROM Category,Category_stat  
            WHERE category_event_id='{$_GET["event_id"]}' and cs_category_id=category_id 
	    and category_organizer_id='{$_SHOP->organizer_id}' and cs_organizer_id='{$_SHOP->organizer_id}'";
    if(!$res=ShopDB::query($query)){
      user_error(ShopDB::error());
      return FALSE;
    }

    while($row=ShopDB::fetch_assoc($res)){
      $cat[]=$row;
    }

    $j=1;
    foreach($cat as $category){ 
      $i=0;
     
      foreach($category as $k=>$v){
       if($j==1){
        $worksheet1->write(0,$i,$k);
        $worksheet1->write($j,$i,$v);
       }else{
        $worksheet1->write($j,$i,$v);
       }
       $i++;
     }
     $j++;
    }

    $worksheet2 =& $workbook->addWorksheet('Place Data');
    
    $query="select * from Seat where seat_event_id='{$_GET["event_id"]}' and seat_organizer_id='{$_SHOP->organizer_id}'";
    if(!$res=ShopDB::query($query)){
      user_error(mysql_error());
      return FALSE;;	
    }     
    while($row=ShopDB::fetch_assoc($res)){
      $place[]=$row;
    }
    $j=1;
    foreach($place as $pl){ 
      $i=0;
      foreach($pl as $k=>$v){
       if($j==1){
        $worksheet2->write(0,$i,$k);
        $worksheet2->write($j,$i,$v);
       }else{
        $worksheet2->write($j,$i,$v);
       }
       $i++;
     }
     $j++;
    }
 
    $worksheet3 =& $workbook->addWorksheet('Ticket Data');
 
    $query="select * from Seat LEFT JOIN Discount ON seat_discount_id=discount_id,`Order`,User,Event,Category,Ort where 
               seat_order_id=order_id AND event_id='{$_GET["event_id"]}' 
	       AND seat_event_id=event_id AND  seat_category_id=category_id 
	       AND seat_user_id=user_id AND event_ort_id=ort_id
	       and event_organizer_id='{$_SHOP->organizer_id}' and 
	       category_organizer_id='{$_SHOP->organizer_id}'";
    if(!$res=ShopDB::query($query)){
      user_error(ShopDB::error());
      return 0;
    }

    $worksheet3->write(0, 0, 'Seat ID');
    $worksheet3->write(0, 1, 'Order ID');
    
    $worksheet3->write(0, 2, 'User_ID');
    $worksheet3->write(0, 3, 'User_LastName');
    $worksheet3->write(0, 4, 'User_FirstName');
    $worksheet3->write(0, 5, 'User_Address');
    $worksheet3->write(0, 6, 'User_Address1');
    $worksheet3->write(0, 7, 'User_ZIP');
    $worksheet3->write(0, 8, 'User_City');
    $worksheet3->write(0, 9, 'User_Country');    
    $worksheet3->write(0, 10, 'User_Phone');
    $worksheet3->write(0, 11, 'User_Fax');
    $worksheet3->write(0, 12, 'User_Email');    
    $worksheet3->write(0, 13, 'User_Status');

    $worksheet3->write(0, 14, 'Event_ID');
    
    $worksheet3->write(0, 15, 'Event_Name');
    $worksheet3->write(0, 16, 'Event_Date');
    $worksheet3->write(0, 17, 'Event_Time');
    $worksheet3->write(0, 18, 'Ort_id');
    $worksheet3->write(0, 19, 'Ort_Name');

    $worksheet3->write(0, 20, 'Category_ID');

    $worksheet3->write(0, 21, 'Category_Name');
    $worksheet3->write(0, 22, 'Category_Price');
    
    $worksheet3->write(0, 23, 'Seat_Price');
    $worksheet3->write(0, 24, 'Discount_Name');
    $worksheet3->write(0, 25, 'Discount_Type');
    $worksheet3->write(0, 26, 'Discount_Value');
    
    $i=1;
    while($row=ShopDB::fetch_assoc($res)){
      $worksheet3->write($i, 0, $row['seat_id']);
      $worksheet3->write($i, 1, $row['seat_order_id']);
      
      $worksheet3->write($i, 2, $row['user_id']);
      $worksheet3->write($i, 3, $row['user_lastname']);
      $worksheet3->write($i, 4, $row['user_firstname']);
      $worksheet3->write($i, 5, $row['user_address']);
      $worksheet3->write($i, 6, $row['user_address1']);
      $worksheet3->write($i, 7, $row['user_zip']);
      $worksheet3->write($i, 8, $row['user_city']);
      $worksheet3->write($i, 9, $row['user_country']);    
      $worksheet3->write($i, 10, $row['user_phone']);
      $worksheet3->write($i, 11,$row['user_fax']);
      $worksheet3->write($i, 12,$row['user_email']);    
      $worksheet3->write($i, 13,$row['user_status']);

      $worksheet3->write($i, 14,$row['event_id']);
    
      $worksheet3->write($i, 15,$row['event_name']);
      $worksheet3->write($i, 16,$row['event_date']);
      $worksheet3->write($i, 17,$row['event_time']);

      $worksheet3->write($i, 18,$row['ort_id']);
      $worksheet3->write($i, 19,$row['ort_name']);

      $worksheet3->write($i, 20,$row['category_id']);

      $worksheet3->write($i, 21,$row['category_name']);
      $worksheet3->write($i, 22,$row['category_price']);
    
      $worksheet3->write($i, 23,$row['seat_price']);
      if($row['discount_id']){
        $worksheet3->write($i, 24,$row['discount_name']);
        $worksheet3->write($i, 25,$row['discount_type']);
        $worksheet3->write($i, 26,$row['discount_value']);
      
      }
      $i++;
    }
   
    $workbook->close();

?>