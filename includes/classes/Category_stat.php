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

class Category_stat{

  var $cs_category_id;
  var $cs_free;
  var $cs_total;
  var $cs_organizer_id;

  function Category_stat($cs_category_id=0,$cs_total=0,$cs_organizer_id=0) {
    if($cs_category_id){
      $this->cs_category_id=$cs_category_id;
      $this->cs_total=$cs_total;
      $this->cs_free=$cs_total;
      $this->cs_organizer_id=$cs_organizer_id;
    }
  }
  
  function save(){
    $query="insert into Category_stat
    set cs_category_id={$this->cs_category_id},
    cs_free={$this->cs_free},
    cs_total={$this->cs_total},
    cs_organizer_id={$this->cs_organizer_id}";
    
    if(ShopDB::query($query)){
      return TRUE;
    }
  }

  function dec ($cs_category_id,$count){
  	global $_SHOP;
    $query="UPDATE Category_stat SET cs_free=cs_free-$count 
            WHERE cs_category_id='$cs_category_id' LIMIT 1";
    if(!ShopDB::queryi($query) or shopDB::affected_rows($_SHOP->link)!=1){
      return FALSE;
    }else{
      return TRUE;
    }   
  }

  function inc ($cs_category_id,$count){
    $query="UPDATE Category_stat SET cs_free=cs_free+$count 
            WHERE cs_category_id='$cs_category_id' LIMIT 1";
    if(!ShopDB::query($query) or shopDB::affected_rows()!=1){
      return FALSE;
    }else{
      return TRUE;
    }  
  }

}
?>