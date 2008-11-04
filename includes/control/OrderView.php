<?php
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
require_once("page_classes/CountriesList.php");
require_once("page_classes/AUIComponent.php");

require_once("functions/datetime_func.php");
//require_once("functions/order_func.php");
//require_once("classes/Place.php");
//require_once("classes/Order.php");

class OrderView extends AUIComponent{
  var $page_length=15;
function order_details ($order_id){
  global $_SHOP;
  $query="select * from `Order`,User where order_id='$order_id' and order_user_id=user_id";
  if(!$order=ShopDB::query_one_row($query)){
    echo "<div class='error'>".order_not_found." $order_id</div>";
    return;
  }
  $status=$this->print_order_status($order);
  $order["order_status"]=$status;

  
  echo "<table class='admin_form' width='100%' cellspacing='0' cellpadding='2'>\n";
  echo "<tr><td class='admin_list_title' colspan='2'>".
         order_nr."  ".$order_id."</td></tr>";   
   
  $this->print_field('order_tickets_nr',$order);
  $this->print_field('order_total_price',$order);
  $this->print_field('order_date',$order);
  $this->print_field('order_shipment_mode',$order);	 
  $this->print_field('order_payment_mode',$order);	 
  $this->print_field('order_fee',$order);	 
  $this->print_field('order_status',$order);
  echo "</table><br>\n";
  



  $query="select * from Seat LEFT JOIN Discount ON seat_discount_id=discount_id,
          Event,Category,PlaceMapZone where seat_order_id='".$order_id."'
  	   AND seat_event_id=event_id AND 
	   seat_category_id=category_id and 
	   seat_zone_id=pmz_id and
	   event_organizer_id='{$_SHOP->organizer_id}'";
  if(!$res=ShopDB::query($query)){
     user_error(shopDB::error());
     return;
  }
  echo "<table class='admin_form' width='100%' cellspacing='0' cellpadding='2'>\n";
  echo "<tr><td class='admin_list_title' colspan='7'>".tickets."</td></tr>";   
  $alt=0; 
  while($ticket=shopDB::fetch_assoc($res)){
    if((!$ticket["category_numbering"]) or $ticket["category_numbering"]=='both'){
      $place=$ticket["seat_row_nr"]."-".$ticket["seat_nr"];
    }else if($ticket["category_numbering"]=='rows'){
      $place=place_row." ".$ticket["seat_row_nr"]; 
    }else if($ticket["category_numbering"]=='seat'){
      $place=place_seat." ".$ticket["seat_nr"]; 
    }else{
      $place='---';
    }

 
   echo "<tr class='admin_list_row_$alt'>
    	   <td class='admin_list_item'>".$ticket["seat_id"]."</td>
    	   <td class='admin_list_item'>".$ticket["event_name"]."</td>
    	   <td class='admin_list_item'>".$ticket["category_name"]."</td>
    	   <td class='admin_list_item'>".$ticket["pmz_name"]."</td>

    	   <td class='admin_list_item'>$place</td>
	   <td class='admin_list_item'>".$ticket["discount_name"]."</td>

    	   <td class='admin_list_item' align='right'>".$ticket["seat_price"]."</td>
    	   <td class='admin_list_item' align='right'>".
	   $this->print_place_status($ticket["seat_status"])."</td>
	   
	   <tr>\n";
    $alt=($alt+1)%2;
	   
  }
  echo "</table><br>\n";
  $country=new CountriesList();
  $order["user_country_name"]=$country->getCountry($order["user_country"]);
  $status=$this->print_status($order["user_status"]);
  $order["user_status"]=$status;
  echo "<table class='admin_form' width='100%' cellspacing='0' cellpadding='2' border='0'>\n";
  echo "<tr><td class='admin_list_title' colspan='2'>".user." ".$order["user_id"]."</td></tr>";   
   
  $this->print_field('user_lastname',$order);
  $this->print_field('user_firstname',$order);
  $this->print_field('user_address',$order);
  $this->print_field('user_address1',$order);	 
  $this->print_field('user_zip',$order);
  $this->print_field('user_city',$order);
  $this->print_field('user_country_name',$order);
  $this->print_field('user_phone',$order);
  $this->print_field('user_fax',$order);
  $this->print_field('user_email',$order);
  $this->print_field('user_status',$order);
  
  echo "</table>\n";
  
  
}

function link ($action,$order_id,$img,$confirm=FALSE,$con_msg='',$param=null){
  if($confirm){
    $param['action1']=$action;
    $param['order_id1']=$order_id;
    
    foreach($param as $key=>$val){
      $par.=$psep."$key=$val";
      $psep="&";
    }
    
    return "<a href='javascript:if(confirm(\"".$this->con($con_msg)."\")){location.href=\"".$_SERVER['PHP_SELF']."?$par\";}'>".
         "<img border='0' src='images/$img'></a>";
  }
  return "<a href='".$_SERVER['PHP_SELF']."?action=$action&order_id=$order_id'>".
         "<img border='0' src='images/$img'></a>";
}

function get_limit ($page,$count){
  if(!$page){ $page=1; }
  $limit["start"]=($page-1)*$this->page_length;
  $limit["end"]=$this->page_length;
  return $limit;

}

function get_nav ($page,$count,$condition){
  if(!isset($page)){ $page=1; }
 
  echo "<table border='0' width='500'><tr><td align='center'>";
  echo "<a class='link' href='".$_SERVER["PHP_SELF"]."?$condition&page=1'>".firstpage."</a>";
  
  if($page>1){
    $prev=$page-1;
    echo "&nbsp;<a class='link' href='".$_SERVER["PHP_SELF"]."?$condition&page=$prev'>".previouspage."</a>";
  }
  $num_pages=ceil($count/$this->page_length);
  echo "&nbsp;[";
  for ($i=floor(($page-1)/10)*10+1;$i<=min(ceil($page/10)*10,$num_pages);$i++){
    if($i==$page){
      echo "&nbsp;<b>$i</b>";
    }else{
      echo "&nbsp;<a class='link' href='".$_SERVER["PHP_SELF"]."?$condition&page=$i'>$i</a>";
    }
  }
  echo "&nbsp;]&nbsp;";
  $next=$page+1;
    if($next*$this->page_length<$count){
      echo "&nbsp;<a class='link' href='".$_SERVER["PHP_SELF"]."?$condition&page=$next'>".nextpage."</a>";
    }
  echo "&nbsp;<a class='link' href='".$_SERVER["PHP_SELF"]."?$condition&page=$num_pages'>".lastpage."</a>";
    
  echo "</td></tr></table>";
}




function draw (){
  global $_SHOP;
 if($_GET['action']=='details'){
    $this->order_details($_GET["order_id"]); 
  }
}

function print_status ($user_status){
  if($user_status=='1'){
    return sale_point;
  }else if ($user_status=='2'){
    return member;
  }else if($user_status=='3'){
    return guest;
  }
}

function print_order_status ($order){
  switch($order['order_status']){
    case 'ord':   return "<font color='blue'>".ordered."</font>";
    case 'send':  return "<font color='red'>".sended."</font>";
    case 'payed': return "<font color='green'>".payed."</font>";
    case 'cancel':return "<font color='#787878'>".canceled."</font>";
    case 'reemit':return "<font color='#787878'>".reemited."</font> ( 
    <a href='{$_SERVER['PHP_SELF']}?action=details&order_id={$order['order_reemited_id']}'>
    {$order['order_reemited_id']}</a> )";
  }    
}

function print_place_status ($place_status){
  switch($place_status){
    case 'free':  return "<font color='green'>".free."</font>";
    case 'res':  return "<font color='orange'>".reserved."</font>";
    case 'com': return "<font color='red'>".com."</font>";
    case 'check':return "<font color='blue'>".checked."</font>";
   }    
}

  function print_field ($name, &$data){
    echo "<tr><td class='admin_name' width='20%'>".$this->con($name)."</td>
    <td class='admin_value'>
    {$data[$name]}
    </td></tr>\n";
  } 


  function print_input ($name, &$data, &$err){
    echo "<tr><td class='admin_name'  width='20%'>".$this->con($name)."</td>
    <td class='admin_value'><input type='text' name='$name' value='".htmlentities($data[$name],ENT_QUOTES)."' size='$size' maxlength='$max'>
    <span class='admin_err'>{$err[$name]}</span>
    </td></tr>\n";
  } 
   function con($name){
    if(defined($name)){
      return constant($name);
    }else{
      return $name;
    }
  }

}
?>