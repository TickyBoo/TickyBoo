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

require_once "classes/ShopDB.php";

define("PLACE_ERR_INTERNAL",1);
define("PLACE_ERR_OCCUPIED",2);
define("PLACE_ERR_TOOMUCH",3);

class Place {

  

  function reservate ($sid,$event_id,$category_id,$places_id,$numbering){
    global $_SHOP;
    
    $deadlock_count=0;
    
    while($deadlock_count<$_SHOP->max_deadlocks){
      $res=Place::_reservate($sid,$event_id,$category_id,$places_id,$numbering);
      if($res){
        return $res;
      }else
      if(shopDB::errno()!=DB_DEADLOCK){ //deadlock
        ShopDB::rollback();
        return FALSE;
      }
      $deadlock_count++;
    }
    user_error("max deadlocks reached");
    
    return FALSE;
  }

  function _reservate ($sid,$event_id,$category_id,$places,$numbering){
    global $_SHOP;   
    $_SHOP->place_error=0;
     
    if(!ShopDB::begin()){
      $_SHOP->place_error=array('errno'=>PLACE_ERR_INTERNAL,'place'=>'place:73');
      return FALSE;
    }  
    
    $time=time()+$_SHOP->res_delay;
    
    if($numbering=='none'){
      $query="SELECT place_id FROM Place 
              WHERE place_event_id='$event_id'
              and place_category_id='$category_id' 
	      and place_status='free' LIMIT $places
	      FOR UPDATE";

      if(!$res=ShopDB::query($query)){
        $_SHOP->place_error=array('errno'=>PLACE_ERR_INTERNAL,'place'=>'place:87');
        return FALSE;
      }
      
      while($row=shopDB::fetch_array($res)){
        $places_id[]=$row['place_id'];
      }
      
      if(count($places_id)<$places){
        ShopDB::rollback();
        $_SHOP->place_error=array('errno'=>PLACE_ERR_TOOMUCH,'remains'=>count($places_id));
        return FALSE;
      }

    }else if($numbering=='rows'){
      foreach($places as $row){
        $rows[$row]++;
      }
      
    
      foreach($rows as $row_nr=>$count){
        $query="SELECT place_id FROM Place 
                WHERE place_event_id='$event_id'
                and place_category_id='$category_id'
		            and place_row_nr='$row_nr'
	              and place_status='free' LIMIT $count
	              FOR UPDATE";
        
        if(!$res=ShopDB::query($query)){
          $_SHOP->place_error=array('errno'=>PLACE_ERR_INTERNAL,'place'=>'place:115');
          return FALSE;
        }
      
        $row_count=0;
        while($row=shopDB::fetch_array($res)){
          $places_id[]=$row['place_id'];
          $row_count++;
	}
      
        if($row_count!=$count){
          ShopDB::rollback();
          $_SHOP->place_error=array('errno'=>PLACE_ERR_TOOMUCH,'remains'=>$row_count);
          return FALSE;
        }
      }
    }
    else if($numbering=='both')
    {
      $places_id=$places;
    }else{
      user_error("unknown place_numbering $numbering category $category_id");
      ShopDB::rollback();
      $_SHOP->place_error=array('errno'=>PLACE_ERR_INTERNAL,'place'=>'place:139');
      return FALSE;
    }
    
    

    foreach($places_id as $place_id){
      $query="UPDATE Place set place_status='res', place_ts='$time',
	      place_sid='$sid' where 
              place_id='$place_id' 
              and place_event_id='$event_id'
              and place_category_id='$category_id' and place_status='free'";
	      
     if(!ShopDB::query($query)){ 
        ShopDB::rollback();
        $_SHOP->place_error=array('errno'=>PLACE_ERR_INTERNAL,'place'=>'place:154');
	return FALSE;
      }else{
	if(shopDB::affected_rows()!=1){
          ShopDB::rollback();
          $_SHOP->place_error=array('errno'=>PLACE_ERR_OCCUPIED);
	  return FALSE;
	}
      }
    }
    
    if(!ShopDB::commit()){
      $_SHOP->place_error=array('errno'=>PLACE_ERR_INTERNAL,'place'=>'place:166');
      return FALSE;
    }
    
    return $places_id;
  }

  //places = array(array('place_id'=>,'event_id'=>,category_id=>))
  function command ($sid,$places,$user_id,$commit=FALSE){
    global $_SHOP;
    
    $deadlock_count=0;
    
    while($deadlock_count<$_SHOP->max_deadlocks){
    
      $res=Place::_command($sid,$places,$user_id,$commit);
      if($res){
        return TRUE;
      }else if(shopDB::errno()!=DB_DEADLOCK){ //deadlock
        return FALSE;
      }
      
      $deadlock_count++;
    }
    
    user_error("max deadlocks reached");
    return FALSE;
  }

  function _command ($sid,$places,$user_id,$commit){
    global $_SHOP;   
     
    if(!ShopDB::begin()){ 
      return FALSE;
    }  
        
    foreach($places as $place){
      $query="UPDATE Place set place_status='com', place_user_id='$user_id'
              where place_id='{$place['place_id']}'
	      and place_event_id='{$place['event_id']}' 
	      and place_category_id='{$place['category_id']}' 
	      and place_status='res' and place_sid='$sid'";
      if(!ShopDB::query($query)){ 
        return FALSE;
      }else{
        if(shopDB::affected_rows()!=1){
           return FALSE;
        }
      }
      $event_stat[$place['event_id']]++;
      $category_stat[$place['category_id']]++;
    }

    foreach($category_stat as $cat=>$count){
      $query="UPDATE Category_stat SET cs_free=cs_free-$count 
              WHERE cs_category_id='$cat'";
      if(!ShopDB::query($query)){
        return FALSE;
      }   
    }
    
    foreach($event_stat as $event=>$count){
      $query="UPDATE Event_stat SET es_free=es_free-$count 
              WHERE es_event_id='$event'";
      if(!ShopDB::query($query)){
        return FALSE;
      }   
    }
    
    
    if($commit and !ShopDB::query("commit")){
      return FALSE;
    }
    
    
    return TRUE;
  }

  //the order is cancelled -> moves places to 'free' status and 
  //updates stats
  //places = array(array('place_id'=>,'event_id'=>,category_id=>))
  function cancel ($places,$user_id,$commit=FALSE){
    global $_SHOP;
    
    $deadlock_count=0;
    while($deadlock_count<$_SHOP->max_deadlocks){
      $res=Place::_cancel($places,$user_id,$commit);
      if($res){
        return TRUE;
      }else
      if(shopDB::errno()!=DB_DEADLOCK){ //deadlock
        return FALSE;
      }
      $deadlock_count++;
    }
    user_error("max deadlocks reached");
    return FALSE;
  }

  function _cancel ($places,$user_id,$commit){
    global $_SHOP;   
    if(!ShopDB::begin()){ 
      return FALSE;
    }  

    foreach($places as $place){
      $query="UPDATE Place set place_status='free', 
                place_user_id='$user_id',
		place_ts=NULL,
		place_sid=NULL,
		place_user_id=0
              where place_id='{$place['place_id']}'
  	        and place_event_id='{$place['event_id']}' 
	        and place_category_id='{$place['category_id']}'";
    //echo "<div class=info>$query</div>";
		
      if(!ShopDB::query($query)){ 
        ShopDB::rollback();
        return FALSE;
      }else{
        if(shopDB::affected_rows()!=1){
           ShopDB::rollback();
           return FALSE;
        }
      }
      $event_stat[$place['event_id']]++;
      $category_stat[$place['category_id']]++;
    }

    foreach($category_stat as $cat=>$count){
      $query="UPDATE Category_stat SET cs_free=cs_free+$count 
              WHERE cs_category_id='$cat'";
      if(!ShopDB::query($query)){
        ShopDB::rollback();
        return FALSE;
      }   
    }

    foreach($event_stat as $event=>$count){
      $query="UPDATE Event_stat SET es_free=es_free+$count 
              WHERE es_event_id='$event'";
      if(!ShopDB::query($query)){
        ShopDB::rollback();
        return FALSE;
      }   
    }

    if($commit and !ShopDB::commit()){
      return FALSE;
    }

    return TRUE;
  }


  function free ($sid,$event_id,$category_id,$places){
    global $_SHOP;
    
    $deadlock_count=0;
    
    while($deadlock_count<$_SHOP->max_deadlocks){
      $res=Place::_free($sid,$event_id,$category_id,$places);
      if($res){
        return TRUE;

      }else if(shopDB::errno()!=DB_DEADLOCK){ //deadlock
        return FALSE;
      }

      $deadlock_count++;
    }
    
    user_error("max deadlocks reached");
    return FALSE;
  }
  
  function _free ($sid,$event_id,$category_id,$places){
    global $_SHOP;   
     
    if(!ShopDB::begin()){ return FALSE;}  
    
    foreach($places as $place_id){
      $query="UPDATE Place set place_status='free', place_ts=NULL, place_sid=NULL
              where place_id='$place_id' and place_sid='$sid' and place_status='res'
              and place_event_id='$event_id' and place_category_id='$category_id'";

      if(!ShopDB::query($query)){ 
        return FALSE;
      }else{
        if(shopDB::affected_rows()!=1){
          ShopDB::rollback();
          return FALSE;
        }
      }
    }
    
    if(!ShopDB::commit()){
      ShopDB::rollback();
      return FALSE;
    }

    return TRUE; 
  }
  
  function remove_expired (){
    global $_SHOP;   
         
    $time=time();

    $query="UPDATE Place set place_status='free', place_ts=NULL, place_sid=NULL
    	     where place_status='res' and place_ts<'$time'";

    $deadlock_count=0;

    do{
      if(ShopDB::query($query)){
        return TRUE;
      }
      $deadlock_count++;
    }while(shopDB::errno()==DB_DEADLOCK and $deadlock_count<$_SHOP->max_deadlocks);

    if(shopDB::errno()==DB_DEADLOCK){
      user_error("max deadlock reached");
    }

    user_error(shopDB::error());
    return FALSE;
   
  }
  
  function select_free ($cat_id){
    $query="select * from Place where place_category_id='$cat_id' 
            and place_status='free'";
    if($res=ShopDB::query($query)){
      return $res;
    }else{
      return FALSE;
    }
	    
  }


  function select ($cat_id){
    $query="select * from Place where place_category_id='$cat_id'";
    if($res=ShopDB::query($query)){
      return $res;
    }else{
      return FALSE;
    }
  }
}

?>