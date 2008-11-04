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

require_once("page_classes/AUIComponent.php");
require_once("classes/Event.php");
require_once ("functions/datetime_func.php");

class EventList extends AUIComponent {


function draw (){
  include_once("check_login.php");
  
  $next=$_SERVER['PHP_SELF'];//was categories.php
  
  $events=Event::select(TRUE,TRUE);
  
  echo "<table width='100%' class='event_list'>";
  echo "<tr><td colspan='4' class='event_list_title'>".events_list."</td></tr>";
  if($events){
     $rowNr=0;
     
     while($event=shopDB::fetch_object($events)){
      $edate=formatDate($event->event_date);
      $etime=formatTime($event->event_time);

      echo "<tr class='event_list_tr$rowNr'> 
       <td width='25%' class='event_list_td'>{$event->event_name}</td>
       <td width='25%' class='event_list_td'>$edate<br>$etime</td>
       <td width='25%' class='event_list_td'>{$event->ort_name}</td>";
       
       if($event->es_free>0){
         echo "<td width='25%' class='event_list_td'><a href='$next?event_id={$event->event_id}'>".
           order_now."</a></td><tr>";    
       }else{
         echo "<td width='25%' class='event_list_td'>".
           event_sold_out."</td><tr>";    
       }   

      $rowNr = ($rowNr+1)%2;
     }
     echo "</table>";   
  }else{
    echo user_error(shopDB::error());
  } 
}

}
?>