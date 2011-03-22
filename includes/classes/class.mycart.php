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
  private $event_list; //array, indexed by event_id
  private $cat_list;
  private $disc_list;
  private $items;

  function __construct(){
    $this->event_list = array(); //array, indexed by event_id
    $this->cat_list   = array();
    $this->disc_list  = array();
    $this->items      = array();
  }

  public function add($event_id, $cat_id, $seat_ids, $discount_id=0, $mode='mode_web', $reserved =false, $force=false){
    if(!isset($this->event_items[$event_id])){
      $this->event_items[$event_id]= $this->loadEvent($event_id);
    }
    if(!isset($this->cat_list[$cat_id])){
      $this->cat_list[$cat_id]= $this->loadCat($cat_id);
    }
    $this->items[] = new placeItem($this, $event_id, $cat_id, $seat_ids, $discount_id=0);

    $id=end(array_keys($this->items));
    $item =& $this->items[$id];
    $item->id=$id;
    $this->setDiscouns($event_id, $cat_id, $id, $discount_id);
    return true;
  }

  function setDiscouns($event_id, $cat_id, $id, $discount_id){
    $item = $this->items[$id];
    if (is_integer($discount_id)) {
      foreach( $item->seats as $key => &$value) {
        $value->discount_id = $discount_id;
      }
    } elseif(is_array($discount_id) and (count($disc)==$this->count()))  {
      foreach( $item->seats as $key => &$value) {
        $value->discount_id =$discount_id[$key];
      }
    }
  }

  public function remove($place_id, $event_id=null, $cat_id = null ){
    foreach ($this->items as  $key => $item ){
      $freeme = true;
      $freeme = $freeme && (($event_id==null) || ($event_id==$item->event_id));
      $freeme = $freeme && (($cat_id==null)   || ($cat_id  ==$item->category_id));
      $freeme = $freeme && (($place_id==null) || ($place_id==$item->id));
      if ($freeme) {
         $this->remove();
      }
    }
  }

  function totalPrice(){
    $total_price=0;
    foreach($this->items as $item){
      $total_price+=$item->total_price();
    }
    return $total_price;
  }

  function useAlter(){
    $use_alt=0;
    foreach($this->items as $event){
      $use_alt += $event->useAlter();
    }
    if($use_alt>=1){
    	return true;
    }else{
   		return false;
  	}
  }
  function minDate (){
    $min_date=true;
    foreach($this->items as $item){
      $event = $this->event_list[$item->event_id];
      $min_date=min($event['event_date'].' '.$event['event_time'], $min_date);
    }
    return $min_date;
  }

  function totalSeats($event_id=0,$cat_id=0,$only_valid=TRUE){
    $total_places=0;
    foreach ($this->items as  $key => $item ){
      $freeme = true;
      $freeme = $freeme && (($event_id==null) || ($event_id==$item->event_id));
      $freeme = $freeme && (($cat_id==null) || ($cat_id==$item->category_id));
      if ($freeme) {
        $total_places += $this->total_places ($only_valid=TRUE);
      }
    }
    return $total_places;
  }

  function isEmpty (){
    $count=0;
    foreach($this->items as $item) {
      if (!$item->isExpired ()) {
        $count++;
      }
    }
    return $count==0;
  }

  function canCheckout (){
    return !$this->isEmpty();
  }

  //BOOL iter_func($event_item,$cat_item,$place_item[,$data])
  //returns 1=continue iterate or 0=stop
  function iterate ($iter_func, &$data){
    foreach($this->items as $item){
      call_user_func_array($iter_func,array(&$this->event_list[$item->event_id], &$this->cat_list[$item->category_id], &$item, &$data));
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


  function _overview ($event_item, $cat_item, $place_item, &$data){
    if($place_item->isExpired()){
      $data['expired']++;
    }else{
      $data['valid']+=$place_item->count();
      $data['minttl']=min($data['minttl'],$place_item->ttl());
      $data['secttl']=min($data['secttl'],$place_item->ttlsec());
    }
    return TRUE;
  }

  protected function loadEvent($event_id){
    global $_SHOP;
    $qry="select event_name, event_date, event_time, event_ort_id, ort_name, ort_city, event_order_limit
            from Event left join Ort on event_ort_id=ort_id
            where event_id='{$event_id}' ";
    //echo $qry;
    $row = ShopDB::query_one_object($qry);
    $row->event_use_alt = check_event($row->event_date);
    return $row;
  }

  protected function loadCat ($event_id, $cat_id){
    $qry="select category_name, category_price, category_event_id,category_numbering,
           from Category where category_id='{$cat_id}' and category_event_id='{$event_id}'";
    return ShopDB::query_one_object($qry);
  }

  protected function loadDisc($discount_id){
    return Discount::load($discount_id);
  }
}


class PlaceItem {
  var $cart;
  var $event_id;
  var $category_id;
  var $discount_id;
  var $places_nr;
  var $seats;
  var $not_load=1;
  var $ts;

  function __construct ($cart, $event_id, $category_id, $seat_ids, $discount_id){
    global $_SHOP;

    $this->cart = $cart;
    $this->event_id=$event_id;
    $this->category_id=$category_id;
    $this->discount_id=$discount_id;

    if (is_array($seat_ids)) {
      $this->loadInfo($seat_ids);
    } else {
//      raise;
    }
    $this->ts=time()+$_SHOP->cart_delay;
  }

  function remove(){
    Seat::free($this->event_id, $this->category_id, $this->seats);
  }

  function usealtAlter (){
    return $this->cart->event_list[$this->event_id]['event_use_alt'];
  }

  function count (){
    return count($this->seats);
  }

  function isExpired (){
    return time()>$this->ts;
  }

  function ttl (){
    return intval(floor(($this->ts-time())/60));
  }
  function ttlsec (){
    return intval(floor(($this->ts-time())));
  }

  function totalPrice ($price){
    if($this->isExpired()){
      return 0;
    }else{
      $res= $this->count()*$price;
      if ($this->discount_id) {
        $discount = $this->cart->disc_list[$this->discount_id];
        $res-=$discount->total_value($price,1);
      }
      return $res;
    }
  }

  function totalSeats ($only_valid=TRUE){
    if(!$only_valid or !$this->isExpired()){
      return $this->count();
    } else {
      return 0;
    }
  }

  function loadInfo ($seats){
    global $_SHOP;
    if($this->not_load){
      $places = implode(', ', $seats);
      $qry="select seat_id, seat_row_nr, seat_nr
            from Seat
            where field(seat_id, {$places})
            and   seat_category_id="._esc($this->category_id)."
            and   seat_event_id="._esc($this->event_id);
      if($result=ShopDB::query($qry)) {
        while ($obj=shopDB::fetch_object($result)){
          $this->places_nr[$obj->seat_id] = array($obj->seat_row_nr,$obj->seat_nr);
        }
      } else{
    	  $this->invalid=TRUE;
    	  return FALSE;
      }
      $this->not_load=0;
    }
    return TRUE;
  }
}

/*
  function add_place ($places_id){
  // if no places create place array.
  if(!$this->place_items){
  $this->place_items=array();
  }

  foreach($this->place_items as $k=>$v){
  if($v->isExpired()){ unset($this->place_items[$k]); }
  }

  if(is_array($places_id) and !empty($places_id)){
  array_push($this->place_items,new PlaceItem($this->event_id,$this->cat_id,$places_id));
  }else{
  return FALSE;
  }


  return $item;
  }
*/
?>