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
    
    $query="SELECT * FROM Discount WHERE discount_id='$id'";
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
  
  function apply_to ($price){
    if($this->discount_type=='fixe'){
      return $price-$this->discount_value;
    }else if($this->discount_type=='percent'){
      return $price*(1.0-$this->discount_value/100.0);
    }else{
      user_error("unknown discount type ".$disc['discount_type']);
      return FALSE;
    }  
  }
  
  function total_value ($price,$qty=1){
    if($this->discount_type=='fixe'){
      return $qty*$this->discount_value;
    }else if($this->discount_type=='percent'){
      return $qty*$price*$this->discount_value/100.0;
    }else{
      user_error("unknown discount type ".$disc['discount_type']);
      return FALSE;
    }  
  }
}
?>