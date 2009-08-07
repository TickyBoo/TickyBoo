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


require_once('classes/ShopDB.php');

class Category_stat{

  var $cs_category_id;
  var $cs_free;
  var $cs_total;

  function Category_stat($cs_category_id=0,$cs_total=0) {
    if($cs_category_id){
      $this->cs_category_id=$cs_category_id;
      $this->cs_total=$cs_total;
      $this->cs_free=$cs_total;
    }
  }
  
  function save(){
    $query="insert into Category_stat
    set cs_category_id="._esc($this->cs_category_id).",
    cs_free="._esc($this->cs_free).",
    cs_total="._esc($this->cs_total);
    
    if(ShopDB::query($query)){
      return TRUE;
    }
  }

  function dec ($cs_category_id,$count){
  	global $_SHOP;
    $query="UPDATE Category_stat SET cs_free=cs_free-$count 
            WHERE cs_category_id="._esc($cs_category_id)." LIMIT 1";
    if(!ShopDB::query($query) or shopDB::affected_rows()!=1){
      return FALSE;
    }else{
      return TRUE;
    }   
  }

  function inc ($cs_category_id,$count){
    $query="UPDATE Category_stat SET cs_free=cs_free+$count 
            WHERE cs_category_id="._esc($cs_category_id)." LIMIT 1";
    if(!ShopDB::query($query) or shopDB::affected_rows()!=1){
      return FALSE;
    }else{
      return TRUE;
    }  
  }

}
?>