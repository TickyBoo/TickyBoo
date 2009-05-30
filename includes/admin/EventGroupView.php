<?php
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 * Copyright (C) 2007-2008 Christopher Jenkins. All rights reserved.
 *
 * Original Design:
 *	phpMyTicket - ticket reservation system
 * 	Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of fusionTicket.
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
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@noctem.co.uk if any conditions of this licencing isn't
 * clear to you.
 */

require_once("admin/AdminView.php");

class EventGroupView extends AdminView{

function event_group_form (&$data, &$err,$title){
  global $_SHOP;
  echo "<form method='POST' action='{$_SERVER['PHP_SELF']}' enctype='multipart/form-data'>\n";
  echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
  echo "<tr><td class='admin_list_title' colspan='2'>".$title."</td></tr>"; 
    
  $this->print_field('event_group_id',$data );
  $this->print_input('event_group_name',$data,$err,30,100);
  $this->print_date('event_group_start_date',$data,$err);
  $this->print_date('event_group_end_date',$data,$err);
  $this->select_types('event_group_type',$data,$err);
  
  $this->print_area('event_group_description',$data,$err);
 // $this->print_input('event_group_url',$data,$err,30,100);
 // $this->print_input('event_image',$data,$err,30,100);
  $this->print_file('event_group_image',$data,$err);  
    	

  if($data['event_group_id']){
    echo "<input type='hidden' name='event_group_id' value='{$data['event_group_id']}'/>\n";
    echo "<input type='hidden' name='action' value='update'/>\n";
  }else{
    echo "<input type='hidden' name='action' value='insert'/>\n";
  }
  
  echo "<tr><td align='center' class='admin_value' colspan='2'>
    <input type='submit' name='submit' value='".save."'>
  <input type='reset' name='reset' value='".res."'></td></tr>";
  echo "</table></form>\n";

  echo "<center><a class='link' href='{$_SERVER['PHP_SELF']}'>".admin_list."</a></center>";
}

function event_group_check (&$data, &$err){
  global $_SHOP;
  
  if(empty($data['event_group_name'])){$err['event_group_name']=mandatory;}
  if((isset($data['event_group_start_date-y']) and strlen($data['event_group_start_date-y'])>0) or 
     (isset($data['event_group_start_date-m']) and strlen($data['event_group_start_date-m'])>0)
      or (isset($data['event_group_start_date-d']) and strlen($data['event_group_start_date-d'])>0)){
    $y=$data['event_group_start_date-y'];
    $m=$data['event_group_start_date-m'];
    $d=$data['event_group_start_date-d'];

    if(!checkdate($m,$d,$y)){$err['event_group_start_date']=invalid;}
    else{$data['event_group_start_date']="$y-$m-$d";}
  }
  if((isset($data['event_group_end_date-y']) and strlen($data['event_group_end_date-y'])>0) 
  or (isset($data['event_group_end_date-m']) and strlen($data['event_group_end_date-m'])>0)
  or (isset($data['event_group_end_date-d']) and strlen($data['event_group_end_date-d'])>0)){
    $y=$data['event_group_end_date-y'];
    $m=$data['event_group_end_date-m'];
    $d=$data['event_group_end_date-d'];

    if(!checkdate($m,$d,$y)){$err['event_group_end_date']=invalid;}
    else{$data['event_group_end_date']="$y-$m-$d";}
  }

  return empty($err);
}

function event_group_list (){
  global $_SHOP;
//  $query="SELECT * FROM Event, Ort WHERE event_ort_id=ort_id";
  $query="select * from Event_group";

  if(!$res=ShopDB::query($query)){
    return;
  }
  
  $alt=0;
  echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='4'>\n";
  echo "<tr><td class='admin_list_title' colspan='8' align='center'>".con('event_group_title')."</td></tr>\n";
  
  while($row=shopDB::fetch_assoc($res)){

     echo "<tr class='admin_list_row_$alt'>"; 
//     echo "<td class='admin_list_item'>{$row['event_group_id']}</td>";
     echo "<td  class='admin_list_item' width='100%'>{$row['event_group_name']}</td>";
     echo "<td class='admin_list_item' nowrap>\n";
     if($row['event_group_status']=='unpub'){
       echo "<a class='link' href='javascript:if(confirm(\"".con('publish_event_group')."\")){location.href=\"view_event_group.php?action=publish&event_group_id={$row['event_group_id']}\";}'>
               <img src='images/publish.jpg' width=16 border='0' alt='".publish."' title='".publish."'></a>\n";
     }
     if($row['event_group_status']=='pub'){   
       echo "<a class='link' href='javascript:if(confirm(\"".con('unpublish_event_group')."\")){location.href=\"view_event_group.php?action=unpublish&event_group_id={$row['event_group_id']}\";}'>
             <img src='images/unpublish.jpg' width=16 border='0' alt='".unpublish."' title='".unpublish."'></a>\n";
     }

//     echo "<a class='link' href='view_event_group.php?action=view&event_group_id={$row['event_group_id']}'><img src='images/view.png' border='0' alt='".view."' title='".view."'></a>\n";
     echo "<a class='link' href='view_event_group.php?action=edit&event_group_id={$row['event_group_id']}'><img src='images/edit.gif' border='0' alt='".edit."' title='".edit."'></a>\n";
     echo "<a class='link' href='javascript:if(confirm(\"".delete_item."\")){location.href=\"view_event_group.php?action=remove&event_group_id={$row['event_group_id']}\";}'><img src='images/trash.png' border='0' alt='".remove."' title='".remove."'></a>";
     echo "</td></tr>";
     $alt=($alt+1)%2;
    
   }

  echo "</table>\n";
  
  echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}?action=add'>".add."</a></center>";
}

function photo_post ($data, $event_group_id){
  return $this->file_post($data,$event_group_id, 'Event_group', 'event_group','_image');
}


  function get_event_group_types (){
    $query="SHOW  COLUMNS  FROM Event_group LIKE  'event_group_type'";
    if(!$res=ShopDB::query_one_row($query)){return;}
    $types=explode("','",preg_replace("/(enum|set)\('(.+?)'\)/","\\2",$res[1]));
    return $types;
  }

  function select_types ($name,&$data,&$err){
    global $_SHOP;
    $sel[$data["$name"]]=" selected ";
    echo "<tr><td class='admin_name'  width='40%'>".con($name)."</td>
     <td class='admin_value'> <select name='$name'>";
    $types= & $_SHOP->event_group_type_enum;
  //print_r($types);
     foreach($types as $k=>$v){
       echo "<option value='".$v."' ".$sel[$v].">".con($v)."</option>\n";
      }
     echo "</select><span class='err'>{$err[$name]}</span></td></tr>\n";
  
  
  }
    function print_type ($name, &$data){
    echo "<tr><td class='admin_name' width='40%'>".con($name)."</td>
    <td class='admin_value'>
    ".con($data[$name])."
    </td></tr>\n";
  } 



function draw (){
global $_SHOP;

if($_POST['action']=='insert'){
  if(!$this->event_group_check($_POST,$err)){
    $this->event_group_form($_POST,$err,event_group_add_title);
  }else{
    if(isset($_POST['event_group_start_date'])){
      $start=_esc($_POST['event_group_start_date']);
    }else{
      $start="NULL";
    }
    if(isset($_POST['event_group_end_date'])){
      $end=_esc($_POST['event_group_end_date']);
    }else{
      $end="NULL";
    }

    $query="INSERT Event_group (
    event_group_name,
    event_group_start_date,
    event_group_end_date,
    event_group_type,    
    event_group_description
    )VALUES (
    "._ESC($_POST['event_group_name']).",
    $start,
    $end,
    "._ESC($_POST['event_group_type']).",
    "._ESC($_POST['event_group_description']).")";
     
    if(!ShopDB::query($query)){
      return 0;
    }

    $event_group_id=shopDB::insert_id();
    
    if(!$this->photo_post($_POST,$event_group_id)){
      echo "<div class=error>".img_loading_problem."<div>";
    }
    

    $this->event_group_list();
  }
}else
 if($_POST['action']=='update'){
  if(!$this->event_group_check($_POST,$err)){
    $this->event_group_form($_POST,$err,event_group_update_title);
  }    
  if(isset($_POST['event_group_start_date'])){
    $start=_esc($_POST['event_group_start_date']);
  }else{
    $start="NULL";
  }
  if(isset($_POST['event_group_end_date'])){
    $end=_esc($_POST['event_group_end_date']);
  }else{
    $end="NULL";
  }

   $query="UPDATE Event_group SET 
   event_group_name="._ESC($_POST['event_group_name']).",
   event_group_start_date=$start,
   event_group_end_date=$end,
   event_group_type="._ESC($_POST['event_group_type']).",
   event_group_description="._ESC($_POST['event_group_description'])."
   WHERE event_group_id="._ESC($_POST['event_group_id']);

    if(!ShopDB::query($query)){
    echo shopDB::error();
      return 0;
    }

    if(!$this->photo_post($_POST,$_POST['event_group_id'])){
      echo "<div class=error>".img_loading_problem."<div>";
    }

    $this->event_group_list();
  
}elseif($_GET['action']=='add'){
  $this->event_group_form($row,$err,event_group_add_title);
}else 
if($_GET['action']=='remove' and $_GET['event_group_id']>0){
  $query="DELETE FROM Event_group WHERE event_group_id="._esc((int)$_GET['event_group_id']);
  if(!ShopDB::query($query)){
    return 0;
  }
  $this->event_group_list();
}elseif($_GET['action']=='publish'){
  $query="UPDATE Event_group SET 
  event_group_status='pub'
  WHERE event_group_id="._esc((int)$_GET['event_group_id']);
  if(!ShopDB::query($query)){
    echo shopDB::error();
    return 0;
  }
  $this->event_group_list();
}elseif($_GET['action']=='unpublish'){
  $query="UPDATE Event_group SET 
  event_group_status='unpub'
  WHERE event_group_id="._esc($_GET['event_group_id']);
  if(!ShopDB::query($query)){
    echo shopDB::error();
    return 0;
  }
  $this->event_group_list();
  
  
}elseif($_GET['event_group_id']){
  $query="SELECT * FROM Event_group WHERE event_group_id="._esc((int)$_GET['event_group_id']);
  if(!$row=ShopDB::query_one_row($query)){
    return 0;
  }
  $this->event_group_form($row,$err,event_group_update_title);
}else {
  $this->event_group_list();
}
}
}
?>