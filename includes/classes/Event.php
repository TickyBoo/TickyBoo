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



class Event {

 function select ($only_published=TRUE,$with_stats=FALSE){
   global $_SHOP;

   $pub=($only_published)? "and event_status='pub'":"";

   $date=date("Y-m-d");

   if($with_stats){
     $query="select * from Event LEFT JOIN Ort ON event_ort_id=ort_id, LEFT JOIN Event_stat ON event_id=es_event_id WHERE Event.event_date >=$date
            $pub order by event_date,event_time";
   }else{
     $query="select * from Event LEFT JOIN Ort ON event_ort_id=ort_id
     where Event.event_date >=$date $pub order by event_date,event_time";
   }

   if($res=ShopDB::query($query)){
     return $res;
   }else{
     return FALSE;
   }
 }

 function load ($id,$only_published=TRUE){
   global $_SHOP;
   $pub='';
   if($only_published){
     $pub="and event_status='pub'";
   }

   $query="select * from Event LEFT JOIN Ort ON event_ort_id=ort_id
           where Event.event_id="._esc($id)." $pub limit 1";

   if($res=ShopDB::query_one_row($query)){
     $event = new Event;
     $event->_fill($res);
     return $event;
   }else{
     return FALSE;
   }

 }

 function load_all_sub ($event_main_id){
   $query="select * from Event where event_rep='sub' and event_main_id="._esc($event_main_id);
   if($res=ShopDB::query($query)){
     while($event_d=shopDB::fetch_array($res)){
       $event=new Event;
       $event->_fill($event_d);
       $events[]=$event;
     }
     return $events;
   }
 }

 function save (){
   global $_SHOP;

   $query='set '.
           $this->_set('event_text').
           $this->_set('event_short_text').
           $this->_set('event_url').
           $this->_set('event_image').
           $this->_set('event_name').
           $this->_set('event_ort_id').
           $this->_set('event_categories_nr').
           $this->_set('event_date').
           $this->_set('event_time').
           $this->_set('event_open').
           $this->_set('event_order_limit').
           $this->_set('event_payment').
           $this->_set('event_template').
           $this->_set('event_type').
           $this->_set('event_group_id').
           $this->_set('event_mp3').
           $this->_set('event_rep');

   if($this->event_rep=='main,sub'){
     $query.=$this->_set('event_main_id',$this->event_id);
   }else{
     $query.=$this->_set('event_main_id');
   }

   if($this->event_id){
      $query="update Event $query event_status='{$this->event_status}' where event_id={$this->event_id}";
      if($this->event_rep=='main'){
        $this->update_subs();
      }
   }else{
      $query = "insert into Event $query event_status='unpub'";
   }

   if(ShopDB::query($query)){
     if($this->event_id){
       return $this->event_id;
     }else{

       $this->event_id=shopDB::insert_id();

       if($this->event_pm_id){
         require_once('classes/PlaceMap.php');
         $pm=PlaceMap::load($this->event_pm_id);

         if($new_pm_id=$pm->copy($this->event_id)){
           $query="update Event set event_pm_id=$new_pm_id where event_id={$this->event_id}";
           ShopDB::query($query);
         }
       }

       return $this->event_id;
     }
   }
 }


  //LA FONCTION DELETE EST PUISSANTE!
  function delete (){
    global $_SHOP;

    if($this->event_status=='pub' ){
        echo '<div class=error>'.Status_is_pub.'<div>';
        return FALSE;}


    if($this->event_rep=='main'){
      $query="select count(*) from Event where event_status!='trash' and event_main_id={$this->event_id}";
      if(!$count=ShopDB::query_one_row($query, false) or $count[0]>0){
        echo '<div class=error>'.delete_subs_first.'<div>';
        return FALSE;
      }
    }else if($this->event_status=='nosal' and $this->event_pm_id){
            echo '<div class=error>'.To_Trash.'<div>';
			return $this->toTrash();
		}


    if(!ShopDB::begin()){
        echo '<div class=error>'.Cant_Start_transaction.'<div>';
        return FALSE;}

    if($this->event_status!='trash'){
			//check if there are non-free seats
			$query="select count(*) from Seat where seat_event_id={$this->event_id} and seat_status!='free' FOR UPDATE";
			if(!$count=ShopDB::query_one_row($query, false) or $count[0]>0){
				ShopDB::rollback();
				echo '<div class=error>'.seats_not_free.'<div>';
				return FALSE;
			}
		}

    $query="delete from Event where event_id={$this->event_id} and event_status!='pub' limit 1";

    if(!ShopDB::query($query) or !shopDB::affected_rows()==1){
      ShopDB::rollback();
      echo '<div class=error>'.event_delete_failed.'<div>';
      return FALSE;
    }

    $query="delete from Seat where seat_event_id={$this->event_id}";
    if(!ShopDB::query($query)){
      ShopDB::rollback();
      echo '<div class=error>'.seats_delete_failed.'<div>';
      return FALSE;
    }

    $query="delete from Event_stat where es_event_id={$this->event_id}";
    if(!ShopDB::query($query)){
      ShopDB::rollback();
      echo '<div class=error>'.event_stat_delete_failed.'<div>';
      return FALSE;
    }

    require_once('classes/PlaceMap.php');
    if($this->event_pm_id and $pm=PlaceMap::load($this->event_pm_id)){
      if (!$pm->delete()){
        return FALSE;
      }
    }

    $query="delete from Discount where discount_event_id={$this->event_id}";
    if(!ShopDB::query($query)){
      ShopDB::rollback();
      echo '<div class=error>'.discount_delete_failed.'<div>';
      return FALSE;
    }

    ShopDB::commit();
    return TRUE;
  }

function publish (&$stats,&$pmps,$dry_run=FALSE){
    global $_SHOP;

    require_once('classes/Seat.php');
    require_once('classes/PlaceMapCategory.php');
    require_once('classes/PlaceMapPart.php');
    require_once('classes/Category_stat.php');
    require_once('classes/Event_stat.php');

    if(!$dry_run){ShopDB::begin();}

    if($this->event_pm_id and ($this->event_rep=='sub' or $this->event_rep=='main,sub')){
      $parts=PlaceMapPart::loadAll_full($this->event_pm_id);
      if(!empty($parts)){
        foreach($parts as $part){
         if (! $part->publish($this->event_id, 0, $stats, $pmps, $dry_run)) {
           return $this->_abort('publish1');}
          if(!$dry_run and !($part->save() and $part->save_original())) {
            return $this->_abort('publish2');}
        }
      }

      $cats=PlaceMapCategory::loadAll_event($this->event_id);
      if(!$cats){
        return $this->_abort('');
      }

      foreach($cats as $cat_ident=>$cat){
        if($cat->category_numbering=='none' and $cat->category_size>0){
          for($i=0;$i<$cat->category_size;$i++){
						if(!$dry_run){
							if( !Seat::publish($this->event_id,0,0,0,0,$cat->category_id)) {
                 return $this->_abort('publish4');
                 }
              }
							$stats[$cat->category_ident]++;
					}
        }
      }

      if($stats){
				foreach($stats as $category_ident=>$cs_total){
					$cat=$cats[$category_ident];
					$cs=new Category_stat($cat->category_id,$cs_total);
					if(!$dry_run){$cs->save() or $this->_abort(publish5);}
					$es_total+=$cs_total;

					$cat->category_status='pub';
					$cat->category_size=$cs_total;
					$cat->category_pmp_id=$pmps[$category_ident][0];
					if(!$dry_run){$cat->save() or $this->_abort(publish5_5);}
				}
			}

      $es=new Event_stat($this->event_id,$es_total,0);
      if(!$dry_run){$es->save() or $this->_abort(publish6);}
    }
    $this->event_status='pub';

    if(!$dry_run){$this->save() or $this->_abort(publish7);}

    if($dry_run or ShopDB::commit()){
      return TRUE;
    }

  }

  function stop_sales (){
    return $this->_change_state('pub','nosal');
  }

  function restart_sales (){
    return $this->_change_state('nosal','pub');
  }

  function _change_state ($old_s,$new_s){
    require_once('classes/PlaceMapCategory.php');

    if($this->event_status!=$old_s){
       echo "<div class=error>".oldstate_not_correct."</div>";
       return FALSE;}

    if(!ShopDB::begin()){
      echo '<div class=error>'.cant_Start_transaction.'<div>';
      return FALSE;
      }

    if(($this->event_rep=='sub' or $this->event_rep=='main,sub') and
        $cats=PlaceMapCategory::loadAll_event($this->event_id)){
      foreach($cats as $cat){
        if($cat->category_status!=$old_s){
          return $this->_abort(cat_state_change_error);
        }
        $cat->category_status=$new_s;

	      if(!$cat->save()){
          return $this->_abort(error_cat_save_changes);
        }
      }
    }

    $this->event_status=$new_s;

    if(!$this->save()){
      return $this->_abort(error_event_save_changes);
    }

    if(!ShopDB::commit()){
      return $this->_abort(error_event_commit);
      }

    return TRUE;
  }

  function _change_state_subs ($old_s,$new_s){
    $ok=TRUE;

    if($this->event_rep=='main' and $subs=Event::load_all_sub($this->event_id)){
      foreach($subs as $sub){
        $ok=($sub->_change_status($old_s,$new_s) and $ok);
      }
    }

    return $ok;
  }

  function _abort ($str=''){
    if ($str) {
      echo "<div class=error>$str</div>";
    }
    ShopDB::rollback();
    return false; // exit;
  }

  function _fill ($data){
    foreach($data as $k=>$v){
      $this->$k=$v;
    }
  }

  function _set ($name,$value=0,$mandatory=FALSE){

    if($value){
      $val=$value;
    }else{
      $val=$this->$name;
    }

    if($val or $mandatory){
      return $name.'='._esc($val).',';
    }
  }


  function update_subs (){
    global $_SHOP;

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
        $query="update Event set $name="._esc($this->$name)." where $name="._esc($old->$name)." and event_rep='sub' and event_main_id='{$this->event_id}'";
        ShopDB::query($query);
      }
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

	  ShopDB::begin();

		$query="update Event set event_status='trash'
						where event_id='{$this->event_id}'";

		if(!ShopDB::query($query)){
		  ShopDB::rollback();
			return FALSE;
		}

		$query="update Category set category_status='trash' where category_event_id='".$this->event_id."'";
		if(!ShopDB::query($query)){
		  ShopDB::rollback();
			return FALSE;
		}

		$query="update Seat set seat_status='trash' where seat_event_id='".$this->event_id."'";
		if(!ShopDB::query($query)){
		  ShopDB::rollback();
			return FALSE;
		}

		ShopDB::commit();
	  return TRUE;
	}

	function emptyTrash(){
    global $_SHOP;

		$query="select seat_event_id,count(order_id) as count
						from Seat LEFT JOIN `Order` ON  order_id=seat_order_id
						where seat_status='trash'
						group by seat_event_id";

		if(!$res=ShopDB::query($query)){
			return FALSE;
		}

		while($data=shopDB::fetch_array($res)){
      if(!$data['count'] and $event=Event::load($data['seat_event_id'],FALSE)){
			  $event->delete();
			}
		}

		$query="select event_id,count(order_id) as count
		        from Event,Seat,`Order`
						where event_id=seat_event_id and
						order_id=seat_order_id and
						event_status='trash'
						group by event_id";


		if(!$res=ShopDB::query($query)){
			return FALSE;
		}

		while($data=shopDB::fetch_array($res)){
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

		while($data=shopDB::fetch_array($res)){
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
}
?>