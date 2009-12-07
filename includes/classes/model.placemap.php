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
 * the packaging of this file.p
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

class PlaceMap Extends Model {
  protected $_idName    = 'pm_id';
  protected $_tableName = 'PlaceMap2';
  protected $_columns   = array( '#pm_id','*pm_ort_id','#pm_event_id','*pm_name', 'pm_image');

  static function create ($pm_ort_id, $pm_name){
    $pm = new PlaceMap;
    $pm->pm_ort_id=$pm_ort_id;
    $pm->pm_name=$pm_name;
    return $pm;
  }

  function load ($pm_id){
    $query="select *
           from PlaceMap2 left join Ort on pm_ort_id=ort_id
           where pm_id="._esc($pm_id);
    if($res=ShopDB::query_one_row($query)){
      $new_pm=new PlaceMap;
      $new_pm->_fill($res);
      return $new_pm;
    }
  }

  function loadAll ($ort_id){
    $query="select *
            from PlaceMap2 left join Ort on pm_ort_id=ort_id
            where ort_id="._esc($ort_id);
    if($res=ShopDB::query($query)){
      while($data=shopDB::fetch_array($res)){
        $new_pm=new PlaceMap;
        $new_pm->_fill($data);
        $pms[]=$new_pm;
      }
    }
    return $pms;
  }


  function publish ($pm_id, $event_id, &$stats, &$pmps, $dry_run=FALSE){
    if(!$dry_run){ShopDB::begin('Publish placemap');}

    $parts=PlaceMapPart::loadAllFull($pm_id);
    if(!empty($parts)){
      foreach($parts as $part){
        if (! $part->publish($event_id, 0, $stats, $pmps, $dry_run)) {
          return self::_abort('pm.publish1');
        }
      }
    }

    $cats=PlaceMapCategory::loadAll($pm_id);
    if(!$cats){
      return self::_abort('No Categories found');
    }

    foreach($cats as $cat_ident=>$cat){
      if($cat->category_numbering=='none' and $cat->category_size>0){
        if(!$dry_run){
          for($i=0;$i<$cat->category_size;$i++){
            if( !Seat::publish($event_id,0,0,0,0,$cat->category_id)) {
               return self::_abort('pm.publish4.a');
            }
          }
       }
        $stats[$cat->category_ident]+= $cat->category_size;
//      } elseif ($cat->category_size ==0) {
//         return self::_abort('cant_publish_event_cat_no_size');
//      } elseif($cat->category_numbering !=='none' and !$cat->category_pmp_id){
//         return self::_abort('cant_publish_event_cat_not_connect');
      }
      if (!PlaceMapCategory::create_stat($cat->category_id, $stats[$cat->category_ident]))
        return self::_abort('pm.publish5');
    }

    if($dry_run or ShopDB::commit('placemap publised')){
      return TRUE;
    }

  }

  function delete (){

    if(ShopDB::begin('delete Placmap: '.$this->pm_id)){
      if (!$this->pm_id) return self::_abort('Cant_delete_without_id');
      if ($this->pm_event_id){
        $seats = shopDB::query_one_row("select count(*) from Seat
                                       where seat_event_id ={$this->pm_event_id}", false);
        if ($seats[0]>0) {
          return placemap::_abort('placemap_delete_failed_seats_exists');
        }
      }

      $query="delete from PlaceMapZone where pmz_pm_id={$this->pm_id}";
      if(!ShopDB::query($query)){
        return  placemap::_abort('placemapzone_stat_delete_failed');
      }

      $query="DELETE c.*, cs.*
              FROM Category c LEFT JOIN Category_stat cs
              ON c.category_id = cs.cs_category_id
              WHERE c.category_pm_id={$this->pm_id}";
      if(!ShopDB::query($query)){
        return placemap::_abort('Category_delete_failed');
      }

      $query="delete from PlaceMapPart where pmp_pm_id={$this->pm_id} ";
      if(!ShopDB::query($query)){
        return placemap::_abort('PlaceMapPart_delete_failed');
      }

      $query="delete from PlaceMap2 where pm_id={$this->pm_id} limit 1";
      if(!ShopDB::query($query)){
        return placemap::_abort('placemap_delete_failed');
      }

      RETURN ShopDB::commit('PlaceMap deleted');
    }
  }

  function copy ($event_id=''){
    $old_id=$this->pm_id;
    unset($this->pm_id);

    if($event_id){
      $this->pm_event_id=$event_id;
    }

    if(ShopDB::begin('copy Placmap to event: '.$event_id)){
      if($new_id=$this->save()){
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
      if (ShopDB::commit('copied Placmap to event: '.$event_id)){
        return $new_id;
      }
    }
  }

  function split ($pm_parts=0,$split_zones=true){
    if(!is_array($pm_parts)) { return false; }

    if(ShopDB::begin('Split Placmap')){
      $index=PlaceMapCategory::_find_ident($this->pm_id);
      $parts=PlaceMapPart::loadAll($this->pm_id);

      foreach($parts as $part_small){
        if(!in_array($part_small->pmp_id, $pm_parts)){continue;}

        $part=PlaceMapPart::loadFull($part_small->pmp_id);
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
    return ShopDB::commit('copied Placmap to event:');
  }

  function _fill($arr, $nocheck=true){
    $this->fillFilename($arr, 'pm_image');
    return parent::_fill($arr, $nocheck);
  }


}
?>