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

require_once('classes/ShopDB.php');

class Ort{
  
  function load ($ort_id){ 
    $query="select * from Ort where ort_id=$ort_id";
    if($res=ShopDB::query_one_row($query)){

      $ort=new Ort;
      $ort->_fill($res);

      return $ort;
    }
  }
  
  function save (){
    global $_SHOP;
    $query="SET ort_name=".ShopDB::quote($this->ort_name).",
               ort_address=".ShopDB::quote($this->ort_address).",
        	     ort_address1=".ShopDB::quote($this->ort_address1).",
        	     ort_zip=".ShopDB::quote($this->ort_zip).",
        	     ort_city=".ShopDB::quote($this->ort_city).",
        	     ort_country=".ShopDB::quote($this->ort_country).",
        	     ort_state=".ShopDB::quote($this->ort_state).",
        	     ort_pm=".ShopDB::quote($this->ort_pm).",
        	     ort_phone=".ShopDB::quote($this->ort_phone).",
        	     ort_fax=".ShopDB::quote($this->ort_fax).",
        	     ort_plan_nr=".ShopDB::quote($this->ort_plan_nr).",
        	     ort_url=".ShopDB::quote($this->ort_url);

    if($this->ort_id){
      $query="update Ort $query where ort_id={(int)$this->ort_id}";
    }else{
      $query="insert Ort $query";
    }
    if(ShopDB::query($query)){
      if(!$this->ort_id){
        $this->ort_id=shopDB::insert_id();
      }
      return $this->ort_id;
    }
  }
  
  function copy (){
    $old_id=$this->ort_id;
    unset($this->ort_id);
    
    $new_id=$this->save();
    
    require_once('classes/PlaceMap.php');
    if($pms=PlaceMap::loadAll($old_id)){
      foreach($pms as $pm){
        $pm->pm_ort_id=$new_id;
	      $pm->copy();
      }
    }
  }
  
  function delete () {
    global $_SHOP;
  
    require_once('classes/PlaceMap.php');

    if($pms=PlaceMap::loadAll($this->ort_id)){
      foreach($pms as $pm){
	      $pm->delete();
      }
    }
    
    $query="delete from Ort where ort_id='{$this->ort_id}' limit 1";
    ShopDB::query($query);
  }

  function _fill ($data){
    foreach($data as $k=>$v){
      $this->$k=$v;
    }
  }
}
?>