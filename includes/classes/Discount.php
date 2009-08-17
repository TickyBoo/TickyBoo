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

class Discount {
  var $discount_id;
  var $discount_type;
  var $discount_value;
  var $discount_name;
  var $discount_event_id;

  function Discount ($discount_id,$discount_type,$discount_value,$discount_name,$discount_event_id){
     $this->discount_id=$discount_id;  
     $this->discount_type=$discount_type;  
     $this->discount_value=$discount_value;  
     $this->discount_name=$discount_name;  
     $this->discount_event_id=$discount_event_id;  
  }
  
  //static
  function load ($id){
    require_once "classes/ShopDB.php";
    
    $query="SELECT * FROM Discount WHERE discount_id="._esc($id);
    if(!$row=ShopDB::query_one_row($query)){
      user_error(shopDB::error());
      return FALSE;
    }
    
    return new Discount (
      $row['discount_id'],
      $row['discount_type'],
      $row['discount_value'],
      $row['discount_name'],
      $row['discount_event_id']
    );
  }

  function load_all (){
    $query="SELECT * FROM Discount";
    if($res=ShopDB::query($query)){
      $discounts = array();
      while($event_d=shopDB::fetch_array($res)){
        $discounts[]=new Discount (
          $row['discount_id'],
          $row['discount_type'],
          $row['discount_value'],
          $row['discount_name'],
          $row['discount_event_id']
        );
      }
      return $events;
    }
  }
  
  function apply_to ($price){
    if($this->discount_type=='fixe'){
      return $price-$this->discount_value;
    }elseif($this->discount_type=='percent'){
      return $price*(1.0-$this->discount_value/100.0);
    }else{
      user_error("unknown discount type ".$disc['discount_type']);
      return FALSE;
    }  
  }
  
  function total_value ($price,$qty=1){
    if($this->discount_type=='fixe'){
      return $qty*$this->discount_value;
    }elseif($this->discount_type=='percent'){
      return $qty*$price*$this->discount_value/100.0;
    }else{
      user_error("unknown discount type ".$disc['discount_type']);
      return FALSE;
    }  
  }
}
?>