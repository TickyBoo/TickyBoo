<?php
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
require_once("classes/AUIComponent.php");


class ControlContent extends AUIComponent{


function draw (){
  
  if($_GET['event_id']){ $_SESSION['event']=$_GET['event_id'];
                         $_SESSION['event_name']=$_GET['event_name'];
			}

  if($_GET['action']=="search_form"){
    $this->search_form($_GET);
    return 1;
  }

  if($_GET['action']=='search'){
    if(!$query_type=$this->search_check($_GET)){
      $this->search_form($_GET);
      return 1;
    }else{
       $this->result_list($_GET,$query_type);
       return 1;
    }
  }else if($_GET['action']=='search_place'){
    if(!$query_type=$this->search_place_check($_GET)){
      $this->search_form($_GET);
      return 1;
    }else{
      $this->result_place($_GET,$query_type);
      return 1;
    }
  }
  if(!isset($_SESSION['event'])){
    $this->event_list();
    return 1;  
  }
  
  if($_GET['action']=="change_event"){
    unset($_SESSION['event']);
    unset($_SESSIOn['event_name']);
    $this->event_list();
    return 1;
  }
  
  
  echo "<center><div class='control_form'><br>{$_SESSION['event_name']}<br><br>
	<form method=POST action='control.php' name='f' 
	onSubmit='this.submit.disabled=true;return true;'>";
  echo "<input type=text name='codebar' value='' size='40'>&nbsp;";
  echo "<input type='submit' name='submit' value='".check."' >
  <input type='reset' name='reset' value='".res."'>";
  echo "</form><br></div></center><br><br>";
  
  
  if(isset($_POST['codebar'])){
    $code=sscanf($_POST['codebar'],"%08d%s");
//    $event_id=$code[0];
//    $rang=$code[1];
//    $place=$code[2];
//    $order_id=$code[3];
//    $ticket_code=$code[4];

    $seat_id=$code[0];
    $ticket_code=$code[1];

    $query="select * from Seat LEFT JOIN PlaceMapZone ON seat_zone_id=pmz_id,
	 					Category LEFT JOIN Color ON category_color=color_id
            where seat_category_id=category_id
            AND seat_id="._esc($seat_id)."
	          AND seat_code="._esc($ticket_code);

    if(!$ticket=ShopDB::query_one_row($query)){
        echo "<div class='err'><table width='100%'><tr><td width='150' align='center'><img src='images/attention.png'></td><td class='error' >".ticket_not_found."</td></tr></table></div>";
        return 0;
    } 

    if($ticket['seat_event_id']!=$_SESSION['event']){
      echo "<div class='err' ><table width='100%'><tr><td width='150' align='center'><img src='images/attention.png'></td><td class='error' >".not_valid_event."</td></tr></table></div>";
      return 0;
    }

/*
    if($ticket['category_numbering']=='both' or $ticket['category_numbering']=='rows' ){
      if($ticket['seat_row_nr']!=$rang){
         echo "<div class='err'><table width='100%'><tr><td width='150' align='center'><img src='images/attention.png'></td><td class='error' >".ticket_not_found."</td></tr></table></div>";
         return 0;
       }
    }

    if($ticket['category_numbering']=='both'){
      if($ticket['seat_nr']!=$place){
         echo "<div class='err'><table width='100%'><tr><td width='150' align='center'><img src='images/attention.png'></td><td class='error' >".ticket_not_found."</td></tr></table></div>";
         return 0;
      }
    }
*/
    if($ticket['seat_status']=='check'){
        echo "<div class='err'><table width='100%'><tr><td width='150' align='center'><img src='images/attention.png'></td><td class='error' >".ticket_already_checked."</td></tr></table></div>";
        return 0;
    } 

    if($ticket['seat_status']=='free'){
        echo "<div class='err'><table width='100%'><tr><td width='150' align='center'><img src='images/attention.png'></td><td class='error' >".place_not_commanded."</td></tr></table></div>";
        return 0;
    } 

    if($ticket['seat_status']=='res'){
        echo "<div class='err'><table width='100%'><tr><td width='150' align='center'><img src='images/attention.png'></td><td class='error' >".place_only_reserved."</td></tr></table></div>";
        return 0;
    } 
    
    if($ticket['category_numbering']=='both'){
      $place_nr=place_nr." ".$ticket['seat_row_nr']."-".$ticket['seat_nr'];
    }else if($ticket['category_numbering']=='rows'){
      $place_nr=rang_nr." ".$ticket['seat_row_nr'];    
    }else if($ticket['category_numbering']=='seat'){
      $place_nr=place_nr." ".$ticket['seat_nr'];    
    }else if($ticket['category_numbering']=='none'){
      $place_nr=place_without_nr;
    }
    
    echo "<table class='check' width='700' cellpadding='5'>
          <tr><td align='center' valign='middle' width='150'><img src='images/bigsmile.gif'></td>
	  <td align='center' class='success'><table border='0' width='350'><tr>
	  <td   align='center' class='success'>".check_success."</td></tr>
          <tr><td class='value' align='center'>{$ticket['category_name']} {$ticket['pmz_name']}</td></tr><tr><td  class='value' align='center'> $place_nr </td></tr>
          <tr><td colspan='2'> &nbsp; </td></tr>";

    if(isset($ticket[color_code])){  
        echo "<tr><td  bgcolor='{$ticket[color_code]}' style='border: #999999 1px dashed;'> &nbsp </td></tr>";
    }

    echo  "<tr><td > &nbsp; </td></tr></table></td></tr></table>";
     
    $query="UPDATE Seat set seat_status='check' where seat_id="._esc($ticket['seat_id']);

    if(!ShopDB::query($query)){
        echo "<div class='err'>".place_status_not_updated."</div>";
        return 0;
    } 
    
  }
}

function event_list (){
global $_SHOP;
if($_SHOP->event_ids !=''){
   $query="select * from Event,Ort where event_status!='unpub' AND 
    event_rep LIKE '%sub%' AND event_ort_id=ort_id 
  	  and FIELD(event_id,{$_SHOP->event_ids})>0 order by event_date, event_time ";
  if(!$events=ShopDB::query($query)){
      user_error(shopDB::error());
      return 0;
  }  

  echo "<table width='100%' class='event_list'  cellpadding='2'>";
  echo "<tr><td colspan='6' class='event_list_title' align='center'>".control_events_list."</td></tr>";
  echo "<tr class='event_list_subtitle'> 
  	 <td width='200' class='event_list_td'>".event."</td>
  	 <td width='200' class='event_list_td'>".date."</td>
  	 <td width='200' class='event_list_td'>".ort."</td>
	 <td width='50' class='event_list_td' align='right'>".checked."</td>
	 <td width='50' class='event_list_td' align='right'>".com."</td>
	 <td width='50' class='event_list_td' align='right'>".free."</td>";
   
  while($event=shopDB::fetch_assoc($events)){
    $query_ev="SELECT seat_status, COUNT(*) as count FROM Seat where 
               seat_event_id='{$event["event_id"]}' GROUP BY seat_status";
     if(!$status=ShopDB::query($query_ev)){
       return 0;
     }  
    
    $ev_stat=array();
    
    while($stat=shopDB::fetch_assoc($status)){
      $ev_stat[$stat["seat_status"]]=$stat["count"];
    }
    $edate=formatDate($event["event_date"]);
    $etime=formatTime($event["event_time"]);

    echo "<tr class='event_list_tr0'> 
  	   <td width='200' class='event_list_td' valign='top'>
           <a href='{$_SERVER['PHP_SELF']}?event_id={$event['event_id']}&event_name={$event['event_name']}'>
           {$event["event_name"]}</a></td>
  	 <td width='200' class='event_list_td' valign='top'>$edate - $etime</td>
  	 <td width='200' class='event_list_td' valign='top'>{$event["ort_name"]}-{$event["ort_city"]}</td>
	 <td width='50' class='event_list_td' align='right' valign='top'>".$ev_stat["check"]."</td>
	 <td width='50' class='event_list_td' align='right' valign='top'>".$ev_stat["com"]."</td>
	 <td width='50' class='event_list_td' align='right' valign='top'>".$ev_stat["free"]."</td>
	 </tr>";
  
  }
  echo "</table>"; 
 }else{
   echo con("no_event_sets");
 }
 }
 function search_form (&$data){
  global $_SHOP;
  echo "<form method='GET' action='{$_SERVER['PHP_SELF']}'>\n";
  echo "<table class='admin_form' width='100%' cellspacing='1' cellpadding='2'>\n";
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
  
  echo "<tr><td  class='admin_value' colspan='2'>
        <input type='hidden' name='action' value='search'/>\n

  <input type='submit' name='submit' value='".search."'>
  <input type='reset' name='reset' value='".res."'></td></tr>";
  echo "</table></form>\n";
  echo "<br><form method='GET' action='{$_SERVER['PHP_SELF']}'>\n";
  echo "<table class='admin_form' width='100%' cellspacing='1' cellpadding='4'>\n";
  echo "<tr><td class='admin_list_title' colspan='2'>".con("search_title_place")."</td></tr>"; 
  echo "<tr><td class='admin_name'>".event_list."</td><td class='admin_value'>
        <select name='event_id'><option value='' selected>".choice_please."</option>";

  if (!empty($_SHOP->event_ids)) {
  $query="select event_id,event_name,event_date,event_time from Event
          where event_status!='unpub' and event_rep LIKE '%sub%' 
  	  and FIELD(event_id,{$_SHOP->event_ids})>0 
	  order by event_date,event_time";
  if(!$res=ShopDB::query($query)){
    user_error(shopDB::error());
    return;
  }
  while($event=shopDB::fetch_assoc($res)){
    $date=formatAdminDate($event["event_date"]);
    $time=formatTime($event["event_time"]);	
    echo "<option value='{$event["event_id"]}'>".$event["event_name"]." - $date - $time </option>";
  }  
  }
    
  echo "</select></td></tr>";
  
  $this->print_input('seat_row_nr',$data, $err,4,4);
  $this->print_input('seat_nr',$data, $err,4,4);
 
  echo "<tr><td  class='admin_value' colspan='2'>
        <input type='hidden' name='action' value='search_place'/>\n

  <input type='submit' name='submit' value='".search."'>
  <input type='reset' name='reset' value='".res."'></td></tr>";
  echo "</table></form>\n";
  echo "<br><form method='GET' action='view_order.php'>\n";
  echo "<table class='admin_form' width='100%' cellspacing='1' cellpadding='4'>\n";
  echo "<tr><td class='admin_list_title' colspan='2'>".con("search_title_order")."</td></tr>"; 
  $this->print_input('order_id',$data, $err,11,11);
  echo "<tr><td  class='admin_value' colspan='2'>
  <input type='hidden' name='action' value='details'/>\n
  <input type='submit' name='submit' value='".search."'>
  <input type='reset' name='reset' value='".res."'></td></tr>";
  echo "</table></form>\n"; 
 }

  function print_field ($name, &$data){
    echo "<tr><td class='admin_name' width='20%'>".con($name)."</td>
    <td class='admin_value'>
    {$data[$name]}
    </td></tr>\n";
  } 


  function print_input ($name, &$data, &$err){
    echo "<tr><td class='admin_name'  width='20%'>".con($name)."</td>
    <td class='admin_value'><input type='text' name='$name' value='".htmlentities($data[$name],ENT_QUOTES)."' size='$size' maxlength='$max'>
    <span class='admin_err'>{$err[$name]}</span>
    </td></tr>\n";
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
  $query="select * from Seat,Category,PlaceMapZone,Event,User,`Order` where ";
  $first=1;
  foreach($query_type as $value){
    if(!$first){ $query.=" AND "; }
    $query.=$value;
    $first=0;
  }
  $query.=" AND seat_event_id=event_id AND seat_category_id=category_id 
            AND seat_zone_id=pmz_id 
            AND seat_user_id=user_id AND seat_order_id=order_id";
  $this->execute_place($query);
}

function execute_place ($query){
  if(!$res=ShopDB::query($query)){
     user_error(shopDB::error());
     return;
  }
  echo "<table class='admin_list' width='100%' cellspacing='1' cellpadding='2'>\n";
  echo "<tr><td colspan='7' class='admin_list_title'>".search_result."</td></tr>";   
  echo "<tr> <td class='admin_list_item'>".event."</td>
          <td class='admin_list_item'>".category."</td>
          <td class='admin_list_item'>".zone."</td>
 
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
          <td class='admin_list_item'>".$row["pmz_name"]."</td>

          <td class='admin_list_item'>".$place."</td>
          <td class='admin_list_item'>".$row["ticket_price"]."</td>
	  
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
  echo "<table class='admin_list' width='100%' cellspacing='0' cellpadding='2'>\n";
  echo "<tr><td colspan='6' class='admin_list_title'>".search_result."</td></tr>";   
   $alt=0;
  while($row=shopDB::fetch_assoc($res)){
    $flag=1;
    echo "<tr class='admin_list_row_$alt'>
          <td class='admin_list_item'>".$row["user_id"]."</td>
	  <td class='admin_list_item'>
          <a class='link' href='view_user.php?user_id=".
          $row["user_id"]."'>".$row["user_lastname"]." ".
          $row["user_firstname"]."</a></td>
	  <td class='admin_list_item'>".$row["user_address"]." ".$row["user_address1"]."</td>

	  <td class='admin_list_item'>".$row["user_zip"]."</td>
	  <td class='admin_list_item'>".$row["user_city"]."</td>
	  <td class='admin_list_item'>".$row["user_country"]."</td>

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
    $query["user_lastname"]= "user_lastname LIKE '".$data['user_lastname']."%' ";
  }
  if($data["user_firstname"]){
    $query["user_firstname"]= "user_firstname LIKE '".$data['user_firstname']."%' ";
  }
  if($data["user_zip"]){
    $query["user_zip"]= "user_zip LIKE '".$data['user_zip']."%' ";
  }
  if($data["user_city"]){
    $query["user_city"]= "user_city LIKE '".$data['user_city']."%' ";
  }
  if($data["user_phone"]){
    $query["user_phone"]= "user_phone LIKE '".$data['user_phone']."%' ";
  }
  if($data["user_email"]){
    $query["user_email"]= "user_email LIKE '".$data['user_email']."%' ";
  }
  if($data["user_status"]){
    $query["user_status"]= "user_status='".$data['user_status']."' ";
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
    $query["event_id"]="event_id='".$data["event_id"]."'";
  }
  
  if($data["seat_row_nr"]){
    $query["seat_row_nr"]="seat_row_nr='".$data["seat_row_nr"]."'";
  }
  if($data["seat_nr"]){
    $query["seat_nr"]="seat_nr='".$data["seat_nr"]."'";
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
  

}
?>