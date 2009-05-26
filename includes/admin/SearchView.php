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


require_once("admin/AdminView.php");
//require_once("functions/datetime_func.php");

class SearchView extends AdminView{



function search_form (&$data){
  global $_SHOP;

  echo "<form method='GET' action='{$_SERVER['PHP_SELF']}'>\n";
  echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
  echo "<tr><td class='admin_list_title' colspan='2'>".con("search_title_user")."</td></tr>"; 
  $this->print_input('user_lastname',$data, $err,25,100);
  $this->print_input('user_firstname',$data, $err,25,100);
  $this->print_input('user_zip',$data, $err,25,100);
  $this->print_input('user_city',$data, $err,25,100);
  $this->print_input('user_phone',$data, $err,25,100);
  $this->print_input('user_email',$data, $err,25,100);
  echo "<tr><td class='admin_name'>".user_status."</td><td class='admin_value'>
        <select name='user_status'><option value='0'>------</option>
	 <option value='1'>".sale_point."</option><option value='2'>".member."</option>
	 <option value='3'>".guest."</option></select></td></tr>";
  
  echo "<tr><td align='center' class='admin_value' colspan='2'>
        <input type='hidden' name='action' value='search'/>\n

  <input type='submit' name='submit' value='".search."'>
  <input type='reset' name='reset' value='".res."'></td></tr>";
  echo "</table></form>\n";
  
  echo "<br><form method='GET' action='{$_SERVER['PHP_SELF']}'>\n";
  echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
  echo "<tr><td class='admin_list_title' colspan='2'>".con("search_title_place")."</td></tr>"; 
  $query="select event_id,event_name,event_date,event_time from Event where event_rep LIKE '%sub%'
          and event_pm_id IS NOT NULL order by event_date,event_time";
  if(!$res=ShopDB::query($query)){
    user_error(shopDB::error());
    return;
  }
  echo "<tr><td class='admin_name'>".event_list."</td><td class='admin_value'>
        <select name='event_id'><option value='' selected>".choice_please."</option>";
  while($event=shopDB::fetch_assoc($res)){
    $date=formatAdminDate($event["event_date"]);
    $time=formatTime($event["event_time"]);	
    echo "<option value='{$event["event_id"]}'>".$event["event_name"]." - $date - $time </option>";
  }  
    
  echo "</select></td></tr>";
  
  $this->print_input('seat_row_nr',$data, $err,4,4);
  $this->print_input('seat_nr',$data, $err,4,4);
 
  echo "<tr><td align='center' class='admin_value' colspan='2'>
        <input type='hidden' name='action' value='search_place'/>\n

  <input type='submit' name='submit' value='".search."'>
  <input type='reset' name='reset' value='".res."'></td></tr>";
  echo "</table></form>\n";
  
  echo "<br><form method='GET' action='view_order.php'>\n";
  echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
  echo "<tr><td class='admin_list_title' colspan='2'>".con("search_title_order")."</td></tr>"; 
  $this->print_input('order_id',$data, $err,11,11);
  echo "<tr><td align='center' class='admin_value' colspan='2'>
  <input type='hidden' name='action' value='details'/>\n
  <input type='submit' name='submit' value='".search."'>
  <input type='reset' name='reset' value='".res."'></td></tr>";
  echo "</table></form>\n";

  echo "<br><form method='GET' action='{$_SERVER['PHP_SELF']}'>\n";
  echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
  echo "<tr><td class='admin_list_title' colspan='2'>".con("search_title_codebar")."</td></tr>"; 
  $this->print_input('codebar',$data, $err,25,21);
  echo "<tr><td align='center' class='admin_value' colspan='2'>
  <input type='hidden' name='action' value='search_codebar'/>\n
  <input type='submit' name='submit' value='".search."'>
  <input type='reset' name='reset' value='".res."'></td></tr>";
  echo "</table></form>\n";
  
  
}

function result_list (&$data,$query_type){
  $query="select * from User where ";
  $first=1;
  foreach($query_type as $value){
    if(!$first){ $query.=" AND "; }
    $query.=$value;
    $first=0;
  }
  $this->execute_query($query);
}

function result_place (&$data,$query_type){
  $query="select * from Seat,Category,Event,User,`Order` where ";
  $first=1;
  foreach($query_type as $value){
    if(!$first){ $query.=" AND "; }
    $query.=$value;
    $first=0;
  }
  $query.=" AND seat_event_id=event_id AND seat_category_id=category_id 
            AND seat_user_id=user_id AND seat_order_id=order_id";
  $this->execute_place($query);
}

function execute_place ($query){
  if(!$res=ShopDB::query($query)){
     user_error(shopDB::error());
     return;
  }
  echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='4'>\n";
  echo "<tr><td colspan='7' class='admin_list_title'>".search_result."</td></tr>";   
  echo "<tr> <td class='admin_list_item'>".event."</td>
          <td class='admin_list_item'>".category."</td>
          <td class='admin_list_item'>".place."</td>
          <td class='admin_list_item'>".price."</td>
	  <td class='admin_list_item'>".user."</td>
	  <td class='admin_list_item'>".bs."</td>
          <td class='admin_list_item'>".status."</td>

	  </tr>" ;

   $alt=0;
   while($row=shopDB::fetch_assoc($res)){
    $flag=1;
    if((!$row["category_numbering"]) or $row["category_numbering"]=='both'){
      $place=$row["seat_row_nr"]."-".$row["seat_nr"];
    }else if($row["category_numbering"]=='rows'){
      $place=place_row." ".$row["seat_row_nr"]; 
    }else if($row["category_numbering"]=='seat'){
      $place=place_seat." ".$row["seat_nr"]; 
    }else{
      $place='---';
    }
    
    echo "<tr class='admin_list_row_$alt'>
          <td class='admin_list_item'>".$row["event_name"]."</td>
          <td class='admin_list_item'>".$row["category_name"]."</td>
          <td class='admin_list_item'>".$place."</td>
          <td class='admin_list_item'>".$row["seat_price"]."</td>
	  
	  <td class='admin_list_item'>
          <a class='link' href='view_user.php?user_id=".
          $row["user_id"]."'>".$row["user_lastname"]." ".
          $row["user_firstname"]."</a></td>
	  <td class='admin_list_item'>
          <a class='link' href='view_order.php?action=details&order_id=".
          $row["order_id"]."'>".$row["order_id"]."</a></td>
          <td class='admin_list_item'>".$this->print_order_status($row["order_status"])."</td>

	  </tr>" ;
    $alt=($alt+1)%2;
  }
  
  if(!$flag){
    echo "<tr><td class='admin_list_item' align='center' style='font-size:30px;color:red;'>".no_result."</td></tr>";
  }
  echo "</table>";
}


function execute_query ($query){
  if(!$res=ShopDB::query($query)){
     user_error(shopDB::error());
     return;
  }
  echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='4'>\n";
  echo "<tr><td colspan='4' class='admin_list_title'>".search_result."</td></tr>";   
   $alt=0;
  while($row=shopDB::fetch_assoc($res)){
    $flag=1;
    echo "<tr class='admin_list_row_$alt'>
          <td class='admin_list_item'>".$row["user_id"]."</td>
	  <td class='admin_list_item'>
          <a class='link' href='view_user.php?user_id=".
          $row["user_id"]."'>".$row["user_lastname"]." ".
          $row["user_firstname"]."</a></td><td class='admin_list_item'>".$row["user_city"]."</td>
	  <td class='admin_list_item'>".$this->print_status($row["user_status"])."</td></tr>" ;
    $alt=($alt+1)%2;
  }
  
  if(!$flag){
    echo "<tr><td class='admin_list_item' align='center'  style='font-size:30px;color:red;'>".no_result."</td></tr>";
  }
  echo "</table>";
}
function search_check (&$data){  
  if(!($data["user_lastname"] or $data["user_firstname"] or $data["user_zip"]
      or $data["user_city"] or $data["user_phone"] or $data["user_email"] or $data["user_status"])){
    return FALSE;
  }
  
  if($data["user_lastname"]){
    $query["user_lastname"]= "user_lastname LIKE ".ShopDB::quote($data['user_lastname'].'%')." ";
  }
  if($data["user_firstname"]){
    $query["user_firstname"]= "user_firstname LIKE ".ShopDB::quote($data['user_firstname'].'%')." ";
  }
  if($data["user_zip"]){
    $query["user_zip"]= "user_zip LIKE ".ShopDB::quote($data['user_zip'].'%')." ";
  }
  if($data["user_city"]){
    $query["user_city"]= "user_city LIKE ".ShopDB::quote($data['user_city'].'%')." ";
  }
  if($data["user_phone"]){
    $query["user_phone"]= "user_phone LIKE ".ShopDB::quote($data['user_phone'].'%')." ";
  }
  if($data["user_email"]){
    $query["user_email"]= "user_email LIKE ".ShopDB::quote($data['user_email'].'%')." ";
  }
  if($data["user_status"]){
    $query["user_status"]= "user_status=".ShopDB::quote($data['user_status'])." ";
  }
  
  return $query;
}

function search_place_check (&$data){
  if(!isset($data["event_id"])){
    return FALSE;
  }
  
  if(!isset($data["seat_row_nr"])){
    return FALSE;
  }
  if($data["event_id"]){
    $query["event_id"]="event_id=".ShopDB::quote($data["event_id"]);
  }
  
  if($data["seat_row_nr"]){
    $query["seat_row_nr"]="seat_row_nr=".ShopDB::quote($data["seat_row_nr"]);
  }
  if($data["seat_nr"]){
    $query["seat_nr"]="seat_nr=".ShopDB::quote($data["seat_nr"]);
  }
  return $query;
  
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

function print_order_status ($order_status){
  if($order_status=='ord'){
    return "<font color='blue'>".ordered."</font>";
  }else if ($order_status=='send'){
    return "<font color='red'>".sended."</font>";
  }else if($order_status=='payed'){
    return "<font color='green'>".payed."</font>";
  }else if($order_status=='cancel'){
    return "<font color='#787878'>".canceled."</font>";
 }  
}


function result_codebar (){
  global $_SHOP;
  if(isset($_GET['codebar'])){
    $code=sscanf($_GET['codebar'],"%08d%s");

    $seat_id=$code[0];
    $ticket_code=$code[1];

    $query="select * from Seat LEFT JOIN Discount ON seat_discount_id=discount_id,
                          Category LEFT JOIN Color ON category_color=color_id,
	                        PlaceMapZone, Event, User,`Order`,	Organizer
            where 
	    seat_id='$seat_id' AND
            seat_category_id=category_id AND
	    seat_zone_id=pmz_id AND
	    seat_event_id=event_id AND
	    seat_user_id=user_id AND
            seat_order_id=order_id
	    AND seat_code='$ticket_code'";
	
	echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='4' >";
	echo "<tr><td colspan='2' class='admin_list_title'>".search_result."</td></tr>";   

      if(!$ticket=ShopDB::query_one_row($query)){
        echo "<tr><td colspan='2' class='err'>".ticket_not_found."</td></tr></table>";
        return 0;
      } 
/*
      if($ticket['category_numbering']=='both' or 
       $ticket['category_numbering']=='rows' ){
        if($ticket['seat_row_nr']!=$rang){
           echo "<tr><td colspan='2' class='err'>".ticket_not_found."</td></tr></table>";
           return 0;
       }
      }
    if($ticket['category_numbering']=='both'){
      if($ticket['seat_nr']!=$place){
           echo "<tr><td colspan='2' class='err'>".ticket_not_found."</td></tr></table>";
           return 0;
      }
    }
*/
    $this->print_field("seat_id",$ticket);
    echo "<tr><td class='admin_name' width='40%'>".con('order_id')."</td>
    <td class='admin_value'>
    <a class='link' href='view_order.php?order_id={$ticket["order_id"]}&action=details'>
    {$ticket["order_id"]}</a>
    </td></tr>\n";
    echo "<tr><td class='admin_name' width='40%'>".con('user')."</td>
    <td class='admin_value'>
    <a class='link' href='view_user.php?user_id={$ticket["user_id"]}'>
    {$ticket["user_firstname"]} {$ticket["user_lastname"]}</a>
    </td></tr>\n";
    
    $this->print_field("event_name",$ticket);
    $this->print_field("category_name",$ticket);
    $this->print_field("pmz_name",$ticket);

    $this->print_field("event_date",$ticket);
    $this->print_field("event_time",$ticket);

    echo "<tr><td class='admin_name' width='40%'>".con('place')."</td>
    <td class='admin_value'>";

    if($ticket['category_numbering']=='both'){
      $place_nr=place_nr." ".$ticket['seat_row_nr']."-".$ticket['seat_nr'];
    }else if($ticket['category_numbering']=='rows'){
      $place_nr=rang_nr." ".$ticket['seat_row_nr'];
    }else if($ticket['category_numbering']=='seat'){
      $place_nr=place_nr." ".$ticket['seat_nr'];
    }else if($ticket['category_numbering']=='none'){
      $place_nr=place_without_nr;
    }
    echo "$place_nr</td></tr>\n";

    $this->print_field("seat_price",$ticket);
    
    $this->print_field("discount_name",$ticket);
    $this->print_field("discount_type",$ticket);
    $this->print_field("discount_value",$ticket);
    
    $this->print_field("seat_status",$ticket);
    $this->print_field("seat_code",$ticket);
    

    if(isset($ticket[color_code])){  
        echo "<tr>
	<td class='admin_name' width='40%'>".con('color_code')."</td>
	<td  bgcolor='{$ticket[color_code]}' style='border: #999999 1px dashed;'> &nbsp </td></tr>";
    }
    echo "</table>";
  }
}
function draw () { 
if($_GET['action']=='search'){
  if(!$query_type=$this->search_check($_GET)){
    $this->search_form($_GET);
  }else{
    $this->result_list($_GET,$query_type);
  }
}else if($_GET['action']=='search_place'){
  if(!$query_type=$this->search_place_check($_GET)){
    $this->search_form($_GET);
  }else{
    $this->result_place($_GET,$query_type);
  }

}elseif($_GET['action']=='search_codebar'){
  $this->result_codebar();
}else{
  $this->search_form($_GET);
}
}

}
?>