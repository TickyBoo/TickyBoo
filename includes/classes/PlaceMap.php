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

require_once("classes/ShopDB.php");

if (!defined('PM_ZONE')) {
  define('PM_ZONE',0);
  define('PM_ROW',1);
  define('PM_SEAT',2);
  define('PM_CATEGORY',3);
  }

class PlaceMap{ //ZRS

  var $pm_id;
  var $pm_name;
  var $pm_ort_id;
    
  function PlaceMap ($pm_ort_id=0, $pm_name=0){
    if($pm_ort_id){
      $this->pm_ort_id=$pm_ort_id;
      $this->pm_name=$pm_name;
    }
  }
  
  
  function save (){
    global $_SHOP;
  
    $query='set '.
    $this->_set('pm_ort_id').
    $this->_set('pm_event_id').
    $this->_set('pm_image').
    $this->_set('pm_name');
    $query=substr($query,0,-1);
    
    if($this->pm_id){
      $query="update PlaceMap2 $query
  	      where pm_id='{$this->pm_id}' and pm_organizer_id={$_SHOP->organizer_id}";
    }else{
      $query="insert into PlaceMap2 $query, pm_organizer_id={$_SHOP->organizer_id}";
    }

    if(ShopDB::query($query)){
      if(!$this->pm_id){
        $this->pm_id=shopDB::insert_id();
      }
      return $this->pm_id;
    }else{
      return FALSE;
    }
  }

  function load ($pm_id){
    global $_SHOP;
  
    $query="select * from PlaceMap2,Ort where pm_ort_id=ort_id and pm_id='$pm_id' and (pm_organizer_id=0 or pm_organizer_id={$_SHOP->organizer_id})";
    if($res=ShopDB::query_one_row($query)){

      $new_pm=new PlaceMap;
      $new_pm->_fill($res);

      return $new_pm;
    }
  }

  function loadAll ($ort_id){
    global $_SHOP;

    $query="select * from PlaceMap2,Ort where pm_ort_id=ort_id and ort_id='$ort_id' and (pm_organizer_id=0 or pm_organizer_id={$_SHOP->organizer_id})";
    if($res=ShopDB::query($query)){
      while($data=shopDB::fetch_array($res)){
        $new_pm=new PlaceMap;
        $new_pm->_fill($data);
        
	$pms[]=$new_pm; 
      }	 
    }
    return $pms;
  }

  function delete (){
    global $_SHOP; 

    if(!ShopDB::begin()){
        echo '<div class=error>'.Cant_Start_transaction.'<div>';
        return FALSE;}

    $query="delete from PlaceMap2 where pm_id={$this->pm_id} and pm_organizer_id={$_SHOP->organizer_id} limit 1";
    if(!ShopDB::query($query)){
      ShopDB::rollback();
      echo '<div class=error>'.placemap_delete_failed.'<div>';
      return FALSE;
    }

    $query="delete from PlaceMapZone where pmz_pm_id={$this->pm_id} and pmz_organizer_id={$_SHOP->organizer_id}";
    if(!ShopDB::query($query)){
      ShopDB::rollback();
      echo '<div class=error>'.placemapzone_stat_delete_failed.'<div>';
      return FALSE;
    }

    $query="delete from PlaceMapPart where pmp_pm_id={$this->pm_id} and pmp_organizer_id={$_SHOP->organizer_id}";
    if(!ShopDB::query($query)){
      ShopDB::rollback();
      echo '<div class=error>'.event_stat_delete_failed.'<div>';
      return FALSE;
    }
      echo '<div class=error>'.Category_delete.'<div>';

    $query="delete from Category where category_pm_id={$this->pm_id} and category_organizer_id={$_SHOP->organizer_id}";
    if(!ShopDB::query($query)){
      ShopDB::rollback();
      echo '<div class=error>'.Category_delete_failed.'<div>';
      return FALSE;
    }

    $query="delete from Category_stat where cs_category_id={$this->pm_id} and cs_organizer_id={$_SHOP->organizer_id}";
    if(!ShopDB::query($query)){
      ShopDB::rollback();
      echo '<div class=error>'.Category_stat_delete_failed.'<div>';
      return FALSE;
    }

    ShopDB::commit();
    return TRUE;

  }

 

  function copy ($event_id=''){
    $old_id=$this->pm_id;
    unset($this->pm_id);
    
    if($event_id){
      $this->pm_event_id=$event_id;
    }
    
    if($new_id=$this->save()){
      require_once('classes/PlaceMapZone.php');
      require_once('classes/PlaceMapCategory.php');
      require_once('classes/PlaceMapPart.php');	


      if($zones=PlaceMapZone::loadAll($old_id)){
        foreach($zones as $zone){
	  unset($zone->pmz_id);
	  $zone->pmz_pm_id=$new_id;
	  $zone->pmz_event_id=$event_id;
	  $zone->save();
	}
      }
      if($zones=PlaceMapCategory::loadAll($old_id)){
        foreach($zones as $zone){
	  unset($zone->category_id);
 	  $zone->category_pm_id=$new_id;
  	  $zone->category_event_id=$event_id;
	  $zone->save();
	}
      }

      if($zones=PlaceMapPart::loadAll($old_id)){
        foreach($zones as $zone){
	  unset($zone->pmp_id);
 	  $zone->pmp_pm_id=$new_id;
  	  $zone->pmp_event_id=$event_id;
	  $zone->save();
	}
      }
    }
    
    return $new_id;
  }  
  
  function split ($pm_parts=0,$split_zones=true){
    require_once('classes/PlaceMapCategory.php');
    $index=PlaceMapCategory::_find_ident($this->pm_id);

    require_once('classes/PlaceMapPart.php');
    $parts=PlaceMapPart::loadAll($this->pm_id);
    
    foreach($parts as $part_small){
      if(!is_array($pm_parts) or !in_array($part_small->pmp_id,$pm_parts)){continue;}

      $part=PlaceMapPart::load_full($part_small->pmp_id);
      if($part->split($index,$cats,$old_cats,$split_zones)){
        $part->save();
      }
    }

    if($cats){
      foreach($cats as $cat){
        $cat->save();
      }
    }
      
    if($old_cats){
      foreach($old_cats as $cat){
        $cat->save();
      }
    }
  }

  function _set ($name,$value=0,$mandatory=FALSE){

    if($value){
      $val=$value;
    }else{
      $val=$this->$name;
    }

    if($val or $mandatory){
      return $name.'='.ShopDB::quote($val).',';   
    }
  }

  
  function _fill ($data){
    foreach($data as $k=>$v){
      $this->$k=$v;
    }
  }
}

?>