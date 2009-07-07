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

require_once('classes/ShopDB.php');

class Event_stat{

  var $es_event_id;
  var $es_free;
  var $es_total;

  function Event_stat($es_event_id=0,$es_total=0) {
    if($es_event_id){
      $this->es_event_id=$es_event_id;
      $this->es_total=$es_total;
      $this->es_free=$es_total;
    }
  }
  
  function save(){

    $query="insert into Event_stat
    set es_event_id={$this->es_event_id},
    es_free={$this->es_free},
    es_total={$this->es_total}";
    
    if(ShopDB::query($query)){
      return TRUE;
    }
  }

  function dec ($es_event_id,$count){
  	global $_SHOP;
    $query="UPDATE Event_stat SET es_free=es_free-$count 
            WHERE es_event_id='$es_event_id' LIMIT 1";
    if(!ShopDB::query($query) or shopDB::affected_rows()!=1){
      return FALSE;
    }else{
      return TRUE;
    }   
  }

  function inc ($es_event_id,$count){
    $query="UPDATE Event_stat SET es_free=es_free+$count 
            WHERE es_event_id='$es_event_id' LIMIT 1";
    if(!ShopDB::query($query) or shopDB::affected_rows()!=1){
      return FALSE;
    }else{
      return TRUE;
    }  
  }
}
?>