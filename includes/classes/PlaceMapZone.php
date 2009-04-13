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


class PlaceMapZone{ 

  var $pmz_id;
  var $pmz_pm_id;
  var $pmz_ident;
  var $pmz_name;
  var $pmz_color;
    
  function PlaceMapZone ($pmz_pm_id=0, $pmz_name=0, $pmz_short_name=0, $pmz_color=0){
    if($pmz_pm_id){
      $this->pmz_pm_id=$pmz_pm_id;
      $this->pmz_name=$pmz_name;
      $this->pmz_short_name=$pmz_short_name;
      $this->pmz_color=$pmz_color;
    }
  }
  
  function save (){
    global $_SHOP;
    if($this->pmz_id){

      $query="update PlaceMapZone set 
	    pmz_short_name=".ShopDB::quote($this->pmz_short_name).",
	    pmz_name=".ShopDB::quote($this->pmz_name).",
	    pmz_color=".ShopDB::quote($this->pmz_color)."
	    
	    where pmz_id='{$this->pmz_id}'";
    }else{
       if(!$this->pmz_ident){$this->_find_ident();}

       $query="insert into PlaceMapZone (
                 pmz_pm_id, 
    	         pmz_name, 
    	         pmz_short_name, 
	         pmz_ident, 
	         pmz_color
               ) VALUES (
	         $this->pmz_pm_id,
	         ".ShopDB::quote($this->pmz_name).",
	         ".ShopDB::quote($this->pmz_short_name).",
	         ".ShopDB::quote($this->pmz_ident).",
	         ".ShopDB::quote($this->pmz_color).")";
    }
   
    if(ShopDB::query($query)){
      if(!$this->pmz_id){
        $this->pmz_id=shopDB::insert_id();
      }
      
      return $this->pmz_id;
      
    }else{
      return FALSE;
    }
  }

  function load ($pmz_id){
    global $_SHOP;
    $query="select * from PlaceMapZone where pmz_id=$pmz_id";

    if($res=ShopDB::query_one_row($query)){
      $new_pmz=new PlaceMapZone;
      $new_pmz->_fill($res);        
    
      return $new_pmz;
    }
  }

  function loadAll ($pm_id){
    global $_SHOP;
    
    $query="select * from PlaceMapZone where pmz_pm_id='$pm_id'";

    if($res=ShopDB::query($query)){
      while($data=shopDB::fetch_array($res)){
        $new_pmz=new PlaceMapZone;
        $new_pmz->_fill($data);
        $zones[$new_pmz->pmz_ident]=$new_pmz;
      }        
    }   
   
    return $zones;
  }

  function delete ($pmz_id){
    global $_SHOP;
		
		if(!$zone=PlaceMapZone::load($pmz_id)){
		  return;
		}

		require_once('classes/PlaceMapPart.php');
		if($pmps=PlaceMapPart::loadAll($zone->pmz_pm_id) and is_array($pmps)){
  		foreach($pmps as $pmp){
  		  if($pmp->delete_zone($zone->pmz_ident)){
  				$pmp->save();
  			}
  		}
    }
	
  
    $query="delete from PlaceMapZone where pmz_id=$pmz_id limit 1";
    ShopDB::query($query);
  }

  function _find_ident (){
    global $_SHOP;

    $query="select pmz_ident from PlaceMapZone where pmz_pm_id={$this->pmz_pm_id}";
    if(!$res=ShopDB::query($query)){return;}
    while($i=shopDB::fetch_array($res)){
      $ident[$i['pmz_ident']]=1;
    }

    $pmz_ident=1;
    while($ident[$pmz_ident]){$pmz_ident++;}
    $this->pmz_ident=$pmz_ident;
  }

  function _fill ($data){
    foreach($data as $k=>$v){
      $this->$k=$v;
    }
  }
}

?>