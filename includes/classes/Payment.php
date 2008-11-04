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

class Payment {

  function load ($payment_id){
    $query="select * from Payment where payment_id='$payment_id'";
    if($res=ShopDB::query_one_row($query)){
      $pay=new Payment;
      $pay->_fill($res);
      
      return $pay;
    }
  }
  
  function load_by_atts ($payment_mode, $payment_send, $payment_organizer_id){
    $query="select * from Payment where payment_mode='$payment_mode' and 
	    payment_send='$payment_send' and payment_organizer_id=$payment_organizer_id";
     if($res=ShopDB::query_one_row($query)){
      $pay=new Payment;
      $pay->_fill($res);
      
      return $pay;
    }
  }
 
  function apply_to ($total){
    return round($this->payment_fee_fixe+($total/100.00)*$this->payment_fee_percent,2);
  } 
 
  function _fill ($data){
    foreach($data as $k=>$v){
      $this->$k=$v;
    }
  } 
}
?>