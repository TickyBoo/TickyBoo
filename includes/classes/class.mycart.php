<?PHP
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2010 Christopher Jenkins, Niels, Lou. All rights reserved.
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

//corbeil system v0.1beta

if (!defined('ft_check')) {die('System intrusion ');}

class Cart {

	// sessions.docx for cart layout
  var $event_items; //array, indexed by event_id

  function add_place ($event_id, $cat_id, $place_id){
    if(!isset($this->event_items[$event_id])){
      $this->event_items[$event_id]=new EventItem($event_id);
      $newitem=1;
    }

    $event =& $this->event_items[$event_id];
    if($res=$event->add_place($cat_id, $place_id)){
      return $res;
    }else{
      if($newitem){
        unset($this->event_items[$event_id]);
      }
      return FALSE;
    }
  }

  function remove_place ($event_id, $cat_id, $place_id){

    if(!$event_id){
      $res=array();
      foreach(array_keys($this->event_items) as $id){
        $res=array_merge($res,$this->remove_place($id,null,null));
      }
      return $res;
    }elseif(isset($this->event_items[$event_id])){
      $event =& $this->event_items[$event_id];
      $res = $event->remove_place($cat_id, $place_id);

      if($event->is_empty()){
        unset($this->event_items[$event_id]);
      }

      return $res;
    }else{
      return 0;
    }
  }

  function total_price(){
    $total_price=0;
    foreach($this->event_items as $event){
      $total_price+=$event->total_price();
    }
    return $total_price;
  }

  function use_alt (){
    $use_alt=0;
    foreach($this->event_items as $event){
      $use_alt+=$event->use_alt();
    }
    if($use_alt>=1){
    	return true;
    }else{
   		return false;
  	}

  }
  function min_date (){
    $min_date=true;
    foreach($this->event_items as $event){
      $min_date=min($event->event_date.' '.$event->event_time, $min_date);
    }
    return $min_date;
  }

  function total_places ($event_id=0,$cat_id=0,$only_valid=TRUE){
    if($event_id and isset($this->event_items[$event_id])){
      $item=&$this->event_items[$event_id];
      return $item->total_places($event_id,$cat_id,$only_valid);
    }elseif(!$event_id){
      $total_places=0;
      foreach($this->event_items as $event){
        $total_places+=$event->total_places($event_id,$cat_id,$only_valid);
      }
      return $total_places;
    }else{
      return 0;
    }
  }

  function load_info (){
    if($this->invalid){return FALSE;}

    foreach(array_keys($this->event_items) as $event_id){
      $event =& $this->event_items[$event_id];
      if(!$event->load_info()){
        $this->invalid=TRUE;
	      return FALSE;
      }
    }
    return TRUE;
  }

  function is_empty (){
    return count($this->event_items)==0;
  }

  function can_checkout (){
    if(count($this->event_items)==0){return FALSE;}
    foreach($this->event_items as $item){
      if($item->can_checkout()){return TRUE;}
    }
    return FALSE;
  }

  //BOOL iter_func($event_item,$cat_item,$place_item[,$data])
  //returns 1=continue iterate or 0=stop
  function iterate ($iter_func, &$data){
    foreach($this->event_items as $event_item){
      foreach($event_item->cat_items as $cat_item){
        foreach($cat_item->place_items as $place_item){
          call_user_func_array($iter_func,array(&$event_item,&$cat_item,&$place_item,&$data));
        }
      }
    }
  }


  function overview (){
    global $_SHOP;

    $data=array('valid'=>0,
                'expired'=>0,
                'minttl'=>$_SHOP->cart_delay,
                'secttl'=>$_SHOP->cart_delay);
    $classname = "Cart";
    $this->iterate(array($classname,'_overview'),$data);
    return $data;
  }


  function _overview ($event_item,$cat_item,$place_item,&$data){
    if($place_item->is_expired()){
      $data['expired']++;
    }else{
      $data['valid']+=$place_item->count();
      $data['minttl']=min($data['minttl'],$place_item->ttl());
      $data['secttl']=min($data['secttl'],$place_item->ttlsec());
    }
    return TRUE;
  }

  function set_discounts ($event_id,$cat_id,$item_id,$disc){
    if($event=&$this->event_items[$event_id]){
      return $event->set_discounts($event_id,$cat_id,$item_id,$disc);
    }else{
      return FALSE;
    }
  }
}

class EventItem {
  var $cat_items; //array
  var $event_id;
  var $event_name;
  var $event_time;
  var $event_ort;
  var $event_date;
  var $event_use_alt;
  var $not_load=1;

  function EventItem ($id){
    $this->event_id=$id;
  }

  function add_place ($cat_id, $place_id){
  	// if catagory doenst exsist create new..
    if(!isset($this->cat_items[$cat_id])){
      $this->cat_items[$cat_id]=new CatItem($this->event_id,$cat_id);
    }
    //new catergory in array, fills the cat_items object.
    $cat =& $this->cat_items[$cat_id];
    //returns the added place id
    return $cat->add_place($place_id);
  }

  function remove_place ($cat_id, $place_id){

    if(!$cat_id){
      $res=array();
      foreach(array_keys($this->cat_items) as $id){
        if($arr=$this->remove_place($id,null)){
          $res=array_merge($res,$arr);
        }
      }
      return $res;
    }else

    if(isset($this->cat_items[$cat_id])){
      $cat =& $this->cat_items[$cat_id];

      $res=$cat->remove_place($place_id);
      if($cat->is_empty()){
        unset($this->cat_items[$cat_id]);
      }
      return $res;
    }else{
      return 0;
    }
  }

  function total_price (){
    $total_price=0;
    foreach($this->cat_items as $cat){
      $total_price+=$cat->total_price();
    }
    return $total_price;
  }

  function use_alt (){
    $alt_event=0;
    return $this->event_use_alt;
  }

  function total_places ($event_id=0,$cat_id=0,$only_valid=TRUE){
    if($cat_id and isset($this->cat_items[$cat_id])){
      $item=&$this->cat_items[$cat_id];
      return $item->total_places($event_id,$cat_id,$only_valid);
    }elseif(!$cat_id){
      $total_places=0;
      foreach($this->cat_items as $cat){
        $total_places+=$cat->total_places($event_id,$cat_id,$only_valid);
      }
      return $total_places;
    }else{
      return 0;
    }
  }

  function load_info (){
    if($this->invalid){return FALSE;}
    global $_SHOP;
    if($this->not_load){

      	$qry="select * from Event, Ort where event_id='{$this->event_id}' and event_ort_id=ort_id";
		//echo $qry;
      	if($result=ShopDB::query($qry) and $obj=shopDB::fetch_object($result)){
	        $this->event_name=$obj->event_name;
		    	$this->event_date=$obj->event_date;
			    $this->event_time=$obj->event_time;
			    $this->event_ort_id=$obj->event_ort_id;
	        $this->event_ort_name=$obj->ort_name;
	        $this->event_ort_city=$obj->ort_city;
	        $this->event_order_limit=$obj->event_order_limit;
	        // Loads event checker.
	  		  $this->event_use_alt= check_event($this->event_date);
      	}else{
		//echo shopDB::error();
        	$this->invalid = TRUE;
			    return FALSE;
        }
      	$this->not_load=0;
    }

    foreach(array_keys($this->cat_items) as $cat_id){
      $cat =& $this->cat_items[$cat_id];
      if(!$cat->load_info()){
        $this->invalid = TRUE;
	      return FALSE;
      }
    }

    return TRUE;
  }

  function is_empty (){
    return count($this->cat_items)==0;
  }


  function can_checkout (){
    foreach($this->cat_items as $item){
      if($item->can_checkout()){
        return TRUE;
      }
    }
    return FALSE;
  }

  function set_discounts ($event_id,$cat_id,$item_id,$disc){
    $cat=&$this->cat_items[$cat_id];
    if(isset($cat)){
      return $cat->set_discounts($event_id,$cat_id,$item_id,$disc);
    }else{
      return FALSE;
    }
  }

}

class CatItem {
  var $place_items; //array
  var $cat_id;
  var $cat_price;
  var $cat_name;
  var $not_load=1;

  function CatItem ($event_id,$id){
    global $_SHOP;
    $this->cat_id=$id;
    $this->event_id=$event_id;
  }


  function add_place ($places_id){
	// if no places create place array.
    if(!$this->place_items){
      $this->place_items=array();
    }

    foreach($this->place_items as $k=>$v){
      if($v->is_expired()){ unset($this->place_items[$k]); }
    }

    if(is_array($places_id) and !empty($places_id)){
      array_push($this->place_items,new PlaceItem($this->event_id,$this->cat_id,$places_id));
    }else{
      return FALSE;
    }

    $id=end(array_keys($this->place_items));
    $item =& $this->place_items[$id];
    $item->id=$id;

    return $item;
  }

  function remove_place ($place_item_id){

    if(!isset($place_item_id)){
      $res=array();
      foreach($this->place_items as $item_id=>$item){
        $res=array_merge($res,$this->remove_place($item_id));
      }
      $this->place_items=array();
      return $res;
    }else
    if(isset($this->place_items[$place_item_id])){
      $place=$this->place_items[$place_item_id];
      unset($this->place_items[$place_item_id]);
      if($place->is_expired()){
        return 0;
      }else{
        return $place->places_id;
      }
    }else{
      return 0;
    }
  }

  function total_price (){
    foreach($this->place_items as $item){
      $total+=$item->total_price($this->cat_price);
    }
    return $total;

  }

  function total_places ($event_id=0,$cat_id=0,$only_valid=TRUE){
    $total=0;
    foreach($this->place_items as $item){
      $total+=$item->total_places($event_id,$cat_id,$only_valid);
    }
    return $total;
  }

  function load_info (){
    if($this->invalid){return FALSE;}

    global $_SHOP;

    if($this->not_load){
      $qry="select * from Category where category_id='{$this->cat_id}' and category_event_id='{$this->event_id}'";
      if($result=ShopDB::query($qry) and $obj=shopDB::fetch_object($result)){
        $this->cat_name=$obj->category_name;
	      $this->cat_price=$obj->category_price;
        $this->cat_event_id=$obj->category_event_id;
	      $this->cat_numbering=$obj->category_numbering;
      }else{
        $this->invalid=TRUE;
	      return FALSE;
      }

      $this->not_load=0;
    }

    foreach(array_keys($this->place_items) as $place_id){
      $place =& $this->place_items[$place_id];
      if(!$place->load_info()){
        $this->invalid=TRUE;
	      return FALSE;
      }
    }
    return TRUE;
  }

  function is_empty (){
    return count($this->place_items)==0;
  }

  function can_checkout (){
    foreach($this->place_items as $item){
      if(!$item->is_expired()){
        return TRUE;
      }
    }
    return FALSE;
  }

  function set_discounts ($event_id,$cat_id,$item_id,$disc){
    if($item=&$this->place_items[$item_id]){
      return $item->set_discounts($event_id,$cat_id,$item_id,$disc);
    }else{
      return FALSE;
    }
  }

}

class PlaceItem {
  var $places_nr;
  var $places_id;
  var $not_load=1;
  var $ts;

  function PlaceItem ($event_id,$category_id,$places_id){
    global $_SHOP;
    $this->places_id=$places_id;
    $this->ts=time()+$_SHOP->cart_delay;
    $this->event_id=$event_id;
    $this->category_id=$category_id;
  }

  function count (){
    return count($this->places_id);
  }

  function is_expired (){
    return time()>$this->ts;
  }

  function ttl (){
    return intval(floor(($this->ts-time())/60));
  }
  function ttlsec (){
    return intval(floor(($this->ts-time())));
  }

  function total_price ($price){
    if($this->is_expired()){
      return 0;
    }else{
      $res= $this->count()*$price;
      if($discs=$this->discounts){
        foreach($discs as $discount){
	  if($discount){
            $res-=$discount->total_value($price,1);
	  }
	}
      }
      return $res;
    }
  }

  function total_places ($event_id=0,$cat_id=0,$only_valid=TRUE){
    if(!$only_valid or !$this->is_expired()){
      return $this->count();
    } else {
      return 0;
    }
  }

  function load_info (){
    if($this->invalid){return FALSE;}

    global $_SHOP;
    if($this->not_load){
      $this->places_nr=array();
      foreach($this->places_id as $place_id){
       $qry="select seat_row_nr, seat_nr
             from Seat
             where seat_id='$place_id'
             and seat_category_id='{$this->category_id}'
             and seat_event_id='{$this->event_id}'";
//echo $qry;
        if($result=ShopDB::query($qry) and $obj=shopDB::fetch_object($result)){
          array_push($this->places_nr,array($obj->seat_row_nr,$obj->seat_nr));
        }else{
//echo shopDB::error();
      	  $this->invalid=TRUE;
      	  return FALSE;
      	}
      }
      $this->not_load=0;
    }
    return TRUE;
  }

  //disc[]=array(object Discount,null,null,object Discount ...
  function set_discounts ($event_id,$cat_id,$item_id,$disc){
    if(is_array($disc) and count($disc)==$this->count()){
      $this->discounts=$disc;
      return TRUE;
    }else{
      return FALSE;
    }
  }
}
?>