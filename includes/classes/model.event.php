<?PHP
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2009 Christopher Jenkins, Niels, Lou. All rights reserved.
 *
 * Original Design:
 *  phpMyTicket - ticket reservation system
 *   Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
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

if (!defined('ft_check')) {die('System intrusion ');}
class Event Extends Model {
  protected $_idName    = 'event_id';
  protected $_tableName = 'Event';
  protected $_columns   = array( '#event_id',
      '*event_name', 'event_text', 'event_short_text', 'event_url',
      'event_image', '*event_ort_id', '#event_pm_id', 'event_date', 'event_time',
      'event_open', 'event_end', '*event_status', '*event_order_limit', 'event_template',
      '#event_group_id', 'event_mp3', '*event_rep', '#event_main_id', 'event_type');


 function load ($id,$only_published=TRUE){
   $pub=($only_published)?"and event_status='pub'":'';
   $query="select * from Event LEFT JOIN Ort ON event_ort_id=ort_id
           where Event.event_id="._esc($id)."
           {$pub} limit 1";

   if($res=ShopDB::query_one_row($query)){
     $event = new Event;
     $event->_fill($res);
     //print_r($event);
     return $event;
   }else{
     return FALSE;
   }
 }

  function loadAllSubs ($event_main_id){
    $query="select * from Event
            where event_rep='sub'
            and event_main_id="._esc($event_main_id);
    if($res=ShopDB::query($query)){
      while($event_d=shopDB::fetch_assoc($res)){
        $event=new Event;
        $event->_fill($event_d, false);
        $events[]=$event;
      }
      return $events;
    }
  }

  function save (){
    $new = $this->id;
    if (!$this->event_status) $this->event_status='unpub';
    if (ShopDB::begin('Save event')) {
      if (parent::save()){

        if(!$new){
          if($this->event_rep=='main' && !$this->update_subs()) {
            return false;
          } elseif (ShopDB::commit('event Saved ')){
            return $this->event_id;
          }
        }else{
          if($this->event_pm_id){
            $pm=PlaceMap::load($this->event_pm_id);

            if($pm and $new_pm_id=$pm->copy($this->event_id)){
              $query="update Event set
                        event_pm_id={$new_pm_id}
                      where event_id={$this->event_id}";
              ShopDB::query($query);
            } else {
              return self::_abort(con('Cant find selected placemap.'));
            }
          }
          if (ShopDB::commit('event Saved ')){
            return $this->event_id;
          }        }
      } else {
        return self::_abort(con('Cant_save_event'));
      }
    }
  }


  //LA FONCTION DELETE EST PUISSANTE!
  function delete (){
    global $_SHOP;
    if($this->event_status=='pub' ){
        echo '<div class=error>'.con('Status_is_pub').'</div>';
        return FALSE;
    }

    if($this->event_rep=='main'){
      $query="select count(*)
              from Event
              where event_status!='trash'
              and   event_main_id="._esc($this->id);
      if(!$count=ShopDB::query_one_row($query, false) or $count[0]>0){
        echo '<div class=error>'.con('delete_subs_first').'</div>';
        return FALSE;
      }
    } elseif($this->event_status=='nosal' and $this->event_pm_id){
      echo '<div class=error>'.con('To_Trash').'</div>';
      return $this->toTrash();
    }

    if(ShopDB::begin('Delete event: '.$this->id )){

      if($this->event_status!='trash'){
        //check if there are non-free seats
        $query="select count(*)
                from Seat
                where seat_event_id="._esc($id)."
                and seat_status!='free'
                and seat_status!='trash'
                FOR UPDATE";
        if(!$count=ShopDB::query_one_row($query, false) or $count[0]>0){
          return $this->_abort(con('seats_not_free'));
        }
      }

      $query="delete from Seat
              where seat_event_id="._esc($this->id);
      if(!ShopDB::query($query)){
        return $this->_abort(con('seats_delete_failed'));
      }

      if($this->event_pm_id and $pm=PlaceMap::load($this->event_pm_id)){
        if (!$pm->delete()){
          return $this->_abort(con('Cant_delete_PlaceMap'));
        }
      }

      $query="delete from Discount
              where discount_event_id="._esc($this->id);
      if(!ShopDB::query($query)){
        return $this->_abort(con('discount_delete_failed'));
      }

      $query="DELETE e.*, es.*
              FROM Event e LEFT JOIN Event_stat es
              ON e.event_id = es.es_event_id
              WHERE e.event_id="._esc($this->id);
      if(!ShopDB::query($query)){
        return $this->_abort(con('event_delete_failed'));
      }

      if (!Order::toTrash()) {
        return false;
      }
      return ShopDB::commit('Event deleted');
    } else {
      return addWarning('Cant_Start_transaction');
    }

  }

  function publish (&$stats, &$pmps, $dry_run=FALSE){
    global $_SHOP;

    if(!$dry_run && ShopDB::begin('Publish Event')){
      return false;
    }

    if($this->event_pm_id and ($this->event_rep=='sub' or $this->event_rep=='main,sub')){
      if (!PlaceMap::publish($this->event_pm_id, $this->event_id, $stats, $pmps, $dry_run)) {
        return $this->_abort('publish1');
      }

      if($stats){
        foreach($stats as $category_ident=>$cs_total){
          $es_total+=$cs_total;
        }
      }
      if(!$dry_run){Event::create_stat($this->event_id,$es_total) or $this->_abort('publish6');}
    }
    $this->event_status='pub';

    if(!$dry_run){$this->save() or $this->_abort('publish7');}

    if($dry_run or ShopDB::commit('Event publised')){
      return TRUE;
    }

  }

  function stop_sales (){
    return $this->_change_state('pub','nosal');
  }

  function restart_sales (){
    return $this->_change_state('nosal','pub');
  }

  function _change_state ($old_s, $new_s){

    if($this->event_status!=$old_s){
       echo "<div class=error>".con('oldstate_not_correct')."</div>";
       return FALSE;}

    if(ShopDB::begin('change event_state')){
      $this->event_status=$new_s;

      if(!$this->save()){
        return $this->_abort(con('error_event_save_changes'));
      }
      return ShopDB::commit('Event_state changed');
    } else {
      echo '<div class=error>'.con('cant_Start_transaction').'</div>';
      return FALSE;
    }
  }

  function _change_state_subs ($old_s,$new_s){
    $ok=TRUE;

    if($this->event_rep=='main' and $subs=Event::loadAllSubs($this->event_id)){
      foreach($subs as $sub){
        $ok=($sub->_change_status($old_s,$new_s) and $ok);
      }
    }

    return $ok;
  }

  function update_subs (){
    global $_SHOP;
    if(ShopDB::begin('update subevents')){
      $old=Event::load($this->event_id,FALSE);

      $names[]='event_text';
      $names[]='event_short_text';
      $names[]='event_url';
      $names[]='event_image';
      $names[]='event_name';
      //$names[]='event_ort_id';
      //$names[]='event_categories_nr';
      //$names[]='event_date';
      $names[]='event_time';
      $names[]='event_open';
      $names[]='event_order_limit';
      $names[]='event_payment';
      $names[]='event_template';
      $names[]='event_type';
      $names[]='event_group_id';
      $names[]='event_mp3';
      //$names[]='event_rep';


      foreach($names as $name){
        if($this->$name != $old->$name){
          $query="update Event set
                    {$name}="._esc($this->$name)."
                  where {$name}="._esc($old->$name)."
                  and event_rep='sub'
                  and event_main_id="._esc($this->event_id);
          if (!ShopDB::query($query)) {
            return  $this->_abort(con('cant_update_sub_events'));
          }

        }
      }
      return ShopDB::commit('Updated subevents');
    }
  }

  function new_from_main ($event_main_id){
    if(!$sub=Event::load($event_main_id,FALSE)){
      echo $event_main_id;return;
    }
    unset($sub->event_id);
    $sub->event_main_id=$event_main_id;
    $sub->event_rep='sub';
    return $sub;
  }

  function toTrash(){
    global $_SHOP;

    if($this->event_status != 'nosal'){
      return FALSE;
    }

    if (ShopDB::begin('Trash Event')) {

      $query="update Event set
                event_status='trash'
              where event_id="._esc($this->event_id);

      if(!ShopDB::query($query)){
        return  $this->_abort(con('cant_trash_event'));
      }

      $query="update Seat set
                seat_status='trash'
              where seat_event_id="._esc($this->event_id);
      if(!ShopDB::query($query)){
        return $this->_abort(con('cant_trash_seats'));
      }

      if (!Order::toTrash()) {
        return false;
      }

      return ShopDB::commit('Event_trashed');
    };
  }

  function emptyTrash(){
    $query="select seat_event_id, count(order_id) as count
            from Seat LEFT JOIN `Order` ON  order_id=seat_order_id
            where seat_status='trash'
            group by seat_event_id";

    if(!$res=ShopDB::query($query)){
      return FALSE;
    }

    while($data=shopDB::fetch_assoc($res)){
      if(!$data['count'] and $event=Event::load($data['seat_event_id'],FALSE)){
        $event->delete();
      }
    }

    $query="select event_id, count(order_id) as count
            from Event,Seat,`Order`
            where event_id=seat_event_id and
            order_id=seat_order_id and
            event_status='trash'
            group by event_id";


    if(!$res=ShopDB::query($query)){
      return FALSE;
    }

    while($data=shopDB::fetch_assoc($res)){
      $all[$data['event_id']]=$data['count'];
    }

    $query="select event_id,count(order_id) as count
            from Event,Seat,`Order`
            where event_id=seat_event_id and
            order_id=seat_order_id and
            event_status='trash' and
            order_status='trash'
            group by event_id";

    if(!$res=ShopDB::query($query)){
      return FALSE;
    }

    while($data=shopDB::fetch_assoc($res)){
      $part[$data['event_id']]=$data['count'];
    }

    $counter=0;
    if(!empty($all)){
      foreach($all as $event_id=>$count){
        if($part[$event_id]==$count){

          $event=Event::load($event_id,FALSE);
          if($event->delete()){$counter++;}
        }
      }
    }

    return $counter;
  }

  function create_stat($es_event_id=0,$es_total=0,$es_free=-1) {
    if ($es_free==-1) $es_free= $es_total;
    $query="insert into Event_stat set
              es_event_id={$es_event_id},
              es_free={$es_free},
              es_total={$es_total}";
    if(ShopDB::query($query)){
      return TRUE;
    }
  }

  function dec_stat ($es_event_id,$count){
  	global $_SHOP;
    $query="UPDATE Event_stat SET es_free=es_free-$count
            WHERE es_event_id='$es_event_id' LIMIT 1";
    if(!ShopDB::query($query) or shopDB::affected_rows()!=1){
      return FALSE;
    }else{
      return TRUE;
    }
  }

  function inc_stat ($es_event_id,$count){
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