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

require_once "classes/ShopDB.php";

define("SEAT_ERR_INTERNAL",1);
define("SEAT_ERR_OCCUPIED",2);
define("SEAT_ERR_TOOMUCH",3);

class Seat {

  // Selects and reserves seats... Very Complex
  function reservate ($sid,$event_id,$category_id,$seats,$numbering,$reserved){

    global $_SHOP;   
    $_SHOP->seat_error=0;
     
    $time=time()+$_SHOP->res_delay;
	
  	// if reserved is enabled it lets you book reserved seats handy for splitting big booking.
  	if($reserved==true) {
  	  $status=" and seat_status IN ('free','resp') ";
  	}else{
  	  $status=" and seat_status='free' ";
  	}

    if(!ShopDB::begin('Reservate seats')){
      $_SHOP->place_error=array('errno'=>PLACE_ERR_INTERNAL,'place'=>'seat:62');
      return FALSE;
    }

    //numbering none: choose any $seats seats ($seats is a number)
    // Open seating....
	  if($numbering=='none'){
        
      Seat::expire_category($category_id);
      
      $query="SELECT seat_id FROM Seat 
              WHERE seat_event_id='$event_id'
              and seat_category_id='$category_id' 
	      and seat_status='free' 
	      LIMIT $seats
	      FOR UPDATE";

      if(!$res=ShopDB::query($query)){
        ShopDB::rollback('cant lock seats');
        $_SHOP->place_error=array('errno'=>PLACE_ERR_INTERNAL,'place'=>'seat:80');
        return FALSE;
      }
      
      //register selected seats ids
      while($row=shopDB::fetch_array($res)){
        $seats_id[]=$row['seat_id'];
      }
      
      //is there less seats available that asked for? dono, return error
      if(count($seats_id)<$seats){
        ShopDB::rollback('Not engough seats to reservate');
        $_SHOP->place_error=array('errno'=>PLACE_ERR_TOOMUCH,'remains'=>count($seats_id));
        return FALSE;
      }

    } elseif($numbering=='both' or $numbering=='rows' or $numbering=='seat') {
      $seats_id=$seats;

// IN
      foreach($seats_id as $seat_id){
        $query="SELECT seat_id,seat_pmp_id FROM Seat 
                WHERE seat_event_id='$event_id'
                and seat_category_id='$category_id'
		            and seat_id='$seat_id' $status
		            LIMIT 1 FOR UPDATE";
        if(!$res=ShopDB::query($query)){
          ShopDB::rollback('cant lock seat');
          $_SHOP->place_error=array('errno'=>PLACE_ERR_INTERNAL,'place'=>'seat:154');
          return FALSE;
        }
	
  	  if(!$row=shopDB::fetch_array($res)){
          ShopDB::rollback('Cant find seat');
          $_SHOP->place_error=array('errno'=>PLACE_ERR_OCCUPIED);
      	  return FALSE;
      	}else{
      	  $pmps_id[$row['seat_pmp_id']]=1;
      	}
      }
      
    //some strange thing happens
    }else{
      user_error("unknown place_numbering $numbering category $category_id");
      ShopDB::rollback("unknown place_numbering $numbering category $category_id");
      $_SHOP->place_error=array('errno'=>PLACE_ERR_INTERNAL,'place'=>'seat:171');
      return FALSE;
    }
    
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// RESERVE CODE
    //here we have seats_ids to reservate
    //reserving them one by one
    foreach($seats_id as $seat_id){
      $query="UPDATE Seat set seat_status='res', seat_ts='$time',
	      seat_sid='$sid' where 
              seat_id='$seat_id' 
              and seat_event_id='$event_id'
              and seat_category_id='$category_id' 
	      		$status";
	      
     if(!ShopDB::query($query)){ 
        ShopDB::rollback('cant update seat');
        $_SHOP->place_error=array('errno'=>PLACE_ERR_INTERNAL,'place'=>'seat:189');
        return FALSE;
      }else{
        //place taken by someone in the middle
        if(shopDB::affected_rows()!=1){
          ShopDB::rollback('seat not changed');
          $_SHOP->place_error=array('errno'=>PLACE_ERR_OCCUPIED);
          return FALSE;
        }
      }
    }
    
    //invalidate cache
    if(is_array($pmps_id)){
      require_once('classes/PlaceMapPart.php');
      foreach($pmps_id as $pmp_id=>$v){
        PlaceMapPart::clear_cache($pmp_id);
      }
    }  

    //commit the reservation
    if(!ShopDB::commit('Seats reservated')){
      $_SHOP->place_error=array('errno'=>PLACE_ERR_INTERNAL,'place'=>'seat:211');
      return FALSE;
    }

    return $seats_id;
  }

  //the order is cancelled -> moves places to 'free' status and 
  //updates stats
  //$seats = array(array('seat_id'=>,'event_id'=>,category_id=>,pmp_id=>))


  function cancel ($seats,$user_id,$nocommit=FALSE){

    global $_SHOP;
	if($nocommit==FALSE){
    	if(!ShopDB::begin()){
      		return FALSE;
    	}
    }

    foreach($seats as $seat){
      $query="UPDATE `Seat` set seat_status='free', 
		seat_ts=NULL,
		seat_sid=NULL,
		seat_user_id=NULL,
		seat_order_id=NULL,
		seat_price=NULL,
		seat_discount_id=NULL,
		seat_code=NULL
              where seat_id='{$seat['seat_id']}'
  	        and seat_event_id='{$seat['event_id']}' 
	        and seat_category_id='{$seat['category_id']}'";
    //echo "<div class=info>$query</div>";
		
      if(!ShopDB::query($query)){ //echo a;
        ShopDB::rollback();
        return FALSE;
      }else{
        if(shopDB::affected_rows()!=1){//echo b;
           ShopDB::rollback();
           return FALSE;
        }
      }
      $event_stat[$seat['event_id']]++;
      $category_stat[$seat['category_id']]++;
      $pmp_check[$seat['pmp_id']]=1;
    }

    foreach($category_stat as $cat=>$count){
      $query="UPDATE `Category_stat` SET cs_free=cs_free+$count 
              WHERE cs_category_id='$cat'";
      if(!ShopDB::query($query)){
        ShopDB::rollback();//echo c;
        return FALSE;
      }   
    }

    foreach($event_stat as $event=>$count){
      $query="UPDATE `Event_stat` SET es_free=es_free+$count 
              WHERE es_event_id='$event'";
      if(!ShopDB::query($query)){
        ShopDB::rollback();//echo d;
        return FALSE;
      }   
    }

    if(!empty($pmp_check)){
      require_once('classes/PlaceMapPart.php');
      foreach($pmp_check as $pmp_id=>$v){
        PlaceMapPart::clear_cache($pmp_id);
      }    
    }
	if($nocommit==TRUE){
		return TRUE;	
    }
    if($commit and !ShopDB::commit()){ //echo e;
    	return FALSE;
    }

    return TRUE;
  }


  function free ($sid,$event_id,$category_id,$seats){
    global $_SHOP;   
     
    if(!ShopDB::begin('free seats')){ return FALSE;}
    
    foreach($seats as $seat_id){

      $query="select seat_pmp_id 
              from `Seat` 
	      where seat_id='$seat_id' 
	      and seat_sid='$sid' 
	      and seat_status='res'
              and seat_event_id='$event_id' 
	      and seat_category_id='$category_id' 
	      FOR UPDATE";

      if(!$row=ShopDB::query_one_row($query)){
        ShopDB::rollback('cant lock seats');
        return FALSE;          
      }else{
        $pmps_id[$row['seat_pmp_id']]=1;
      }
      
      $query="UPDATE `Seat` 
              set seat_status='free', 
	      seat_ts=NULL, 
	      seat_sid=NULL
              where seat_id='$seat_id' 
	      and seat_sid='$sid' 
	      and seat_status='res'
              and seat_event_id='$event_id' 
	      and seat_category_id='$category_id'";

      if(!ShopDB::query($query)){
        ShopDB::rollback('cant update seats');
        return FALSE;          

      }else{
        if(shopDB::affected_rows()!=1){
          ShopDB::rollback('seat not changed');
          return FALSE;
        }
      }
    }
    
    //invalidate cache
    if(!empty($pmps_id)){
      require_once('classes/PlaceMapPart.php');
      foreach($pmps_id as $pmp_id=>$v){
        PlaceMapPart::clear_cache($pmp_id);
      }
    }
    
    if(!ShopDB::commit('Seats freeed')){
      ShopDB::rollback();
      return FALSE;
    }

    return TRUE; 
  }
  
  function load_pmp_all ($pmp_id){
    global $_SHOP;
  
    $query="select seat_id,seat_status,seat_ts from Seat where seat_pmp_id=$pmp_id";
    if($res=ShopDB::query($query)){
      while($seat=shopDB::fetch_array($res)){
        $pmp[$seat['seat_id']]=$seat;
      }
    }
    return $pmp;
  }

  function expire_pmp ($pmp_id){
    global $_SHOP;
  
    $time=time();
    $query="UPDATE Seat SET seat_status='free', seat_ts=NULL, seat_sid=NULL
    	     where seat_status='res' and seat_pmp_id='$pmp_id' and seat_ts<'$time'";
    ShopDB::query($query);
    //echo rem_exp;
  }

  function expire_category ($category_id){
    global $_SHOP;

    $time=time();
    $query="UPDATE Seat set seat_status='free', seat_ts=NULL, seat_sid=NULL
    	     where seat_status='res' and seat_category_id='$category_id' and seat_ts<'$time'";
    ShopDB::query($query);
    //echo rem_exp;
  }


  function publish ($seat_event_id,
                    $seat_row_nr,$seat_nr,
		                $seat_zone_id,$seat_pmp_id,$seat_category_id
		    )
  {
    global $_SHOP; 
   
    $query="INSERT INTO Seat SET
	 seat_event_id='$seat_event_id',
  	 seat_row_nr='$seat_row_nr',
  	 seat_nr='$seat_nr',
  	 seat_zone_id='$seat_zone_id',
  	 seat_pmp_id='$seat_pmp_id',
  	 seat_category_id='$seat_category_id',
  	 seat_status='free'";

    if(ShopDB::query($query)){
      return ShopDB::insert_id();
    }
  }

  function _abort ($str=''){
    if ($str) {
      echo "<div class=error>$str</div>";
    }
    ShopDB::rollback($str);
    return false; // exit;
  }
}

?>