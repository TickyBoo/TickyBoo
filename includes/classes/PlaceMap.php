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
    
  function PlaceMap ($pm_ort_id=null, $pm_name=null){
    if($pm_ort_id){
      $this->pm_ort_id=$pm_ort_id;
      $this->pm_name=$pm_name;
    }
  }
  
  
  function save (){
    global $_SHOP;
    $this->pm_ort_id =($this->pm_ort_id===0)?null:$this->pm_ort_id;
    $this->pm_event_id   =($this->pm_event_id===0)?null:$this->pm_event_id;

    $query='set '.
    $this->_set('pm_ort_id').
    $this->_set('pm_event_id').
    $this->_set('pm_image').
    $this->_set('pm_name');
    $query=substr($query,0,-1);
    
    if($this->pm_id){
      $query="update PlaceMap2 $query
  	      where pm_id='{$this->pm_id}' ";
    }else{
      $query="insert into PlaceMap2 $query";
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
  
    $query="select * from PlaceMap2, Ort where pm_ort_id=ort_id and pm_id='$pm_id'";
    if($res=ShopDB::query_one_row($query)){

      $new_pm=new PlaceMap;
      $new_pm->_fill($res);

      return $new_pm;
    }
  }

  function loadAll ($ort_id){
    global $_SHOP;

    $query="select * from PlaceMap2,Ort where pm_ort_id=ort_id and ort_id='$ort_id'";
    if($res=ShopDB::query($query)){
      while($data=shopDB::fetch_array($res)){
        $new_pm=new PlaceMap;
        $new_pm->_fill($data);
        $pms[]=$new_pm; 
      }	 
    }
    return $pms;
  }
  
  function _abort ($str=''){
    if ($str) {
      echo "<div class=error>$str</div>";
    }
    ShopDB::rollback($str);
    return false; // exit;
  }
  
  function delete ($pm_id = -1){
    global $_SHOP; 
    
    $pm_id = (int)$pm_id;
    
    if ($pm_id== -1) $pm_id = $this->pm_id;
    
    if(!ShopDB::begin('delete Placmap: '.$pm_id)){
        echo '<div class=error>'.con('Cant_Start_transaction').'</div>';
        return FALSE;
    }

    $query="delete from PlaceMap2 where pm_id={$pm_id} limit 1";
    if(!ShopDB::query($query)){
      return placemap::_abort(con('placemap_delete_failed'));;
    }

    $query="delete from PlaceMapZone where pmz_pm_id={$pm_id}";
    if(!ShopDB::query($query)){
      return  placemap::_abort(con('placemapzone_stat_delete_failed'));
    }

    $query="delete from PlaceMapPart where pmp_pm_id={$pm_id} ";
    if(!ShopDB::query($query)){
      return placemap::_abort(con('PlaceMapPart_delete_failed'));
    }
    
    $query="DELETE c.*, cs.*
            FROM Category c LEFT JOIN Category_stat cs
            ON c.category_id = cs.cs_category_id
            WHERE c.category_pm_id={$pm_id}";
    if(!ShopDB::query($query)){
      return placemap::_abort(con('Category_delete_failed'));
    }

    ShopDB::commit('PlaceMap deleted');
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
    if(!is_array($pm_parts)) { return false}
    require_once('classes/PlaceMapCategory.php');
    require_once('classes/PlaceMapPart.php');

    $index=PlaceMapCategory::_find_ident($this->pm_id);
    $parts=PlaceMapPart::loadAll($this->pm_id);
    
    foreach($parts as $part_small){
      if(!in_array($part_small->pmp_id, $pm_parts)){continue;}

      $part=PlaceMapPart::load_full($part_small->pmp_id);
      if($part->split($index, $cats, $old_cats, $split_zones)){
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

  function _set ($name, $value=null, $mandatory=FALSE){

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