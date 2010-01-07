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
class Discount  Extends Model {
  protected $_idName    = 'discount_id';
  protected $_tableName = 'Discount';
  protected $_columns   = array( '#discount_id', '*discount_type', '*discount_value', '*discount_name',
                                 '#discount_event_id');
  function __construct($filldefs= false, $event_id=null){
    parent::__construct($filldefs);
    if ($filldefs) {
      $query="SELECT event_pm_id
              FROM event where event_id="._esc($event_id);
      if($row=ShopDB::query_one_row($query)){
        $row['discount_event_id'] = $event_id;
        $this->_fill($row);
      }
    }
  }


  function create ($discount_id,$discount_type,$discount_value,$discount_name,$discount_event_id){
    $new = new Discount;
    $new->discount_id=$discount_id;
    $new->discount_type=$discount_type;
    $new->discount_value=$discount_value;
    $new->discount_name=$discount_name;
    $new->discount_event_id=$discount_event_id;
    return $new;
  }

  //static
  function load ($id){
    $query="SELECT Discount.*, event_pm_id
            FROM Discount left join event on event_id=discount_event_id
            WHERE discount_id="._esc($id);
    if($row=ShopDB::query_one_row($query)){
      $new = new Discount;
      $new->_fill($row);
      return $new;
    }
  }

  function loadAll ($event_id){
    $query="SELECT *
            FROM Discount
            Where discount_event_id ="._esc($event_id);
    if($res=ShopDB::query($query)){
      $discounts = array();
      while($row=shopDB::fetch_assoc($res)){
        $new = new Discount;
        $new->_fill($row);
        $discounts[]= $new;
      }
      return $discounts;
    }
  }

  function delete(){
    if (ShopDB::begin('Delete discount')) {
      $query = "SELECT count(*) count
                from Seat
                where seat_discount_id="._esc($this->id);
      if (!$count = ShopDB::query_one_row($query) || $count['count'] != 0) {
        return addWarning('in_use');
      }
      if (!parent::delete()){
        return self::_abort('cant delete discount');
      } else
        return ShopDB::commit('Deleted discount');
    }
  }

  function copy($event_main_id, $event_sub_id) {
    $discs = self::LoadAll($event_main_id);
    //print_r($discs);
    foreach ($discs as $disc) {
      $disc->discount_event_id = $event_sub_id;
      unset($disc->discount_id);
      $disc->save();
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