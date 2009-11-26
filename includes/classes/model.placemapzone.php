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

if (!defined('ft_check')) {die('System intrusion ');}
class PlaceMapZone Extends Model {
  protected $_idName    = 'pmz_id';
  protected $_tableName = 'PlaceMapZone';
  protected $_columns   = array( '#pmz_id','*pmz_pm_id','*pmz_ident','*pmz_name', 'pmz_color','pmz_short_name');

  var $pmz_id;
  var $pmz_pm_id;
  var $pmz_ident;
  var $pmz_name;
  var $pmz_color;

  function create ($pmz_pm_id=null, $pmz_name=null, $pmz_short_name=null, $pmz_color=null){
    $new = new PlaceMapZone;
    if($pmz_pm_id){
      $new->pmz_pm_id=$pmz_pm_id;
      $new->pmz_name=$pmz_name;
      $new->pmz_short_name=$pmz_short_name;
      $new->pmz_color=$pmz_color;
    }
    return $new;
  }

   function load ($pmz_id){
    global $_SHOP;
    $query="select *
            from PlaceMapZone
            where pmz_id="._esc($pmz_id);

    if($res=ShopDB::query_one_row($query)){
      $new_pmz=new PlaceMapZone;
      $new_pmz->_fill($res);

      return $new_pmz;
    }
  }

  function loadAll ($pm_id){
    global $_SHOP;

    $query="select *
            from PlaceMapZone
            where pmz_pm_id="._esc($pm_id);

    if($res=ShopDB::query($query)){
      while($data=shopDB::fetch_assoc($res)){
        $new_pmz=new PlaceMapZone;
        $new_pmz->_fill($data);
        $zones[$new_pmz->pmz_ident]=$new_pmz;
      }
    }

    return $zones;
  }

  function save (){
     if(!$this->pmz_ident){$this->_find_ident();}
     return parent::save();
  }

  function delete ($pmz_id){
    global $_SHOP;

		if(!$zone=PlaceMapZone::load($pmz_id)){
		  return;
		}
    $seats = shopDB::query_one_row("select count(*) from Seats
                                   where seat_zone_id ={$zone_id}", false);
    if ($seats[0]>0) {
      echo '<div class=error>'.con('Zone_delete_failed_seats_exists').'</div>';
      return false;
    }

    if(ShopDB::begin('delete zone: '.$zone_id)){
      $query="delete from PlaceMapZone where pmz_id=$pmz_id limit 1";
      ShopDB::query($query);

      if($pmps=PlaceMapPart::loadAll($zone->pmz_pm_id) and is_array($pmps)){
        foreach($pmps as $pmp){
          if($pmp->delete_zone($zone->pmz_ident)){
            $pmp->save();
          }
        }
      }
      return ShopDB::commit('Zone deleted');
    }
  }

  /* ??? this code need to be checked !!!! */
  function _find_ident (){
    global $_SHOP;

    $query="select pmz_ident
            from PlaceMapZone
            where pmz_pm_id={$this->pmz_pm_id}";
    if(!$res=ShopDB::query($query)){return;}
    while($i=shopDB::fetch_array($res)){
      $ident[$i['pmz_ident']]=1;
    }

    $pmz_ident=1;
    while($ident[$pmz_ident]){$pmz_ident++;}
    $this->pmz_ident=$pmz_ident;
  }
}

?>