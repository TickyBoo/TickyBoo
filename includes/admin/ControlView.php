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

class ControlView extends AdminView{

function control_view (&$data){
  echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
  echo "<tr><td colspan='2' class='admin_list_title'>".con('control_view')."</td></tr>";
  
  
  $this->print_field('control_login',$data);
  $ids=explode(",",$data["control_event_ids"]);
  $set=array();
  if(!empty($ids) and $ids[0]!=""){
    echo "<tr><td class='admin_name' valign='top'>".con('control_event_ids')."</td>
          <td class='admin_value'>";
    foreach($ids as $id){
      $query="select event_name, event_date, event_time from Event where event_id="._esc($id);
      if($row=ShopDB::query_one_row($query)){
        $date=formatAdminDate($row["event_date"]);
        $time=formatTime($row["event_time"]);
        echo "<a class='link' href='view_event.php?action=view&event_id=".$row["event_id"]."'>".$row["event_name"]." ".$date." ".$time."</a><br>";
      }
    }
  }
  echo "</td></tr>\n";
  echo "</table>";
  echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}'>".admin_list."</a></center>";
  
}
function control_form (&$data,&$err,$title,$add='add'){
global $_SHOP;
  echo "<form method='POST' action='{$_SERVER['PHP_SELF']}'>\n";
  echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
  echo "<tr><td class='admin_list_title' colspan='2'>".$title."</td></tr>"; 
  if(!$data['control_login']){
    $this->print_input('control_login',$data,$err,30,100);
  }else{
    $this->print_field('control_login',$data);
  }
  if($add=='add'){
    echo "<tr> <td class='admin_name'>".password."</td>
         <td class='admin_value'><input type='password' name='password1' size='10'  maxlength='10'><span class='err'>{$err['password']}</span></td></tr>
         <tr> <td class='admin_name'>".password2."</td>
         <td class='admin_value'><input type='password' name='password2' size='10'  maxlength='10'></td></tr>";
  }
  if($add=='update'){
    echo "<tr> <td class='admin_name'>".old_password."</td>
         <td><input type='password' name='old_password' size='10'  maxlength='10'>
	       <span class='err'>{$err['old_password']}</span></td></tr>
         <tr> <td class='admin_name'>".new_password."</td>
         <td class='admin_value'><input type='password' name='new_password1' size='10'  maxlength='10'><span class='err'>{$err['new_password']}</span></td> </tr>
	       <tr> <td class='admin_name'>".new_password."</td>
         <td class='admin_value'><input type='password' name='new_password2' size='10'  maxlength='10'></td></tr>";
  }
  
  if($data["control_event_ids"] and $data["control_event_ids"]!=""){
    $event=explode(",",$data["control_event_ids"]); 
  }
  
  $query="select event_id,event_name,event_date,event_time from Event 
          order by event_date,event_time";
  if(!$res=ShopDB::query($query)){
    user_error(shopDB::error());
    return;
  }
    
  echo "<tr><td  class='admin_name' width='40%' valign='top'>".con('control_event_ids')."</td>
        <td class='admin_value'>";//&nbsp;</td></tr><tr><td class='admin_value' colspan='2' align='center'>";
  echo "<select multiple size='10' name='control_event_ids[]'>";
  while($row=shopDB::fetch_array($res)){
    $sel=(in_array($row["event_id"], $event))?"selected":"";
    $date=formatAdminDate($row["event_date"]);
    $time=formatTime($row["event_time"]);
    echo "<option value='".$row["event_id"]."' $sel>$date $time ". $row["event_name"]."</option>";
  }
  echo "</select></td></tr>\n";
  
  if($data['control_login']){
    echo "<input type='hidden' name='control_login' value='{$data['control_login']}'/>\n";
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

 function control_list (){
  global $_SHOP;
  $query="SELECT * FROM Control";
  if(!$res=ShopDB::query($query)){
    user_error(shopDB::error());
    return;
  }
  
  $alt=0;
  echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='3' border=0>\n";
  echo "<tr><td class='admin_list_title' colspan='2' align='center'>".control_title."</td></tr>\n";
  
  
  while($row=shopDB::fetch_assoc($res)){
    echo "<tr class='admin_list_row_$alt'>";
    echo "<td class='admin_list_item' width='550' >{$row['control_login']}</td>\n";
    echo "<td class='admin_list_item' nowrap='nowrap' align='right'  >
          <a class='link' href='{$_SERVER['PHP_SELF']}?action=view&control_login={$row['control_login']}'><img src='images/view.png' border='0' alt='".view."' title='".view."'></a>\n";
    echo "<a class='link' href='{$_SERVER['PHP_SELF']}?action=edit&control_login={$row['control_login']}'><img src='images/edit.gif' border='0' alt='".edit."' title='".edit."'></a>\n";
    echo "<a class='link' href='javascript:if(confirm(\"".delete_item."\")){location.href=\"{$_SERVER['PHP_SELF']}?action=remove&control_login={$row['control_login']}\";}'><img src='images/trash.png' border='0' alt='".remove."' title='".remove."'></a>\n";
    echo "</td></tr>";
    $alt=($alt+1)%2;
  }
  echo "</table>\n";
  
  echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}?action=add'>".add."</a></center>";
}

function post_events (&$data){
 if(isset($data['control_event_ids'])){
   $first=TRUE;
   foreach($data['control_event_ids'] as $id){
     if($first){$ids.=$id;}else{$ids.=",".$id;}
     $first=FALSE;
  }
 }
 return $ids;
}

function draw () { 
global $_SHOP;
if($_POST['action']=='insert'){
  if(!$this->control_check($_POST,$err)){
    $this->control_form($_POST,$err,control_add_title);
  }else{
    $ids=$this->post_events ($_POST);
    $query="INSERT INTO Control (control_login, control_password, control_event_ids)".
           " VALUES ("._ESC($_POST['control_login']).","._ESC(md5($_POST['password1'])).","._esc($ids).")";
    if(!ShopDB::query($query)){
      user_error(shopDB::error());
      return 0;
    }
    $this->control_list();
  }
 }else if($_POST['action']=='update'){  
  if(!$this->control_check($_POST,$err)){
    $this->control_form($_POST,$err,control_update_title,'update');
  }else{
    if(isset($_POST["old_password"]) and isset($_POST["new_password1"]) and 
       isset($_POST['control_login'])){
      $query="UPDATE Control set 
           control_password="._ESC(md5($_POST['new_password1']))."
           where control_login="._esc($_POST["control_login"])."
	         and control_password="._ESC(md5($_POST['old_password']))."";
    
      if(!ShopDB::query($query)){
        user_error(shopDB::error());
        return FALSE;
      }
      
    }
     $ids=$this->post_events($_POST);
    
     $query="UPDATE Control set 
           control_event_ids='$ids' where control_login="._esc($_POST["control_login"]);
      if(!ShopDB::query($query)){
        user_error(shopDB::error());
        return FALSE;
      }
     
    
   $this->control_list();
  }
}else if($_GET['action']=='add'){
  $this->control_form($row,$err,control_add_title);
 
 }else if($_GET['action']=='edit'){
  $query="SELECT * FROM Control WHERE control_login="._esc($_GET['control_login']);
  if(!$row=ShopDB::query_one_row($query)){
    user_error(shopDB::error());
    return 0;
  }
  
  $this->control_form($row,$err,control_update_title,'update');
}else 
if($_GET['action']=='remove' and $_GET['control_login']){
  $query="DELETE FROM Control WHERE control_login="._esc($_GET['control_login']);
  if(!ShopDB::query($query)){
    user_error(shopDB::error());
    return 0;
  }
  $this->control_list();
}else if($_GET['action']=='view'){
  $query="SELECT * FROM Control WHERE control_login="._esc($_GET['control_login']);
  if(!$row=ShopDB::query_one_row($query)){
    user_error(shopDB::error());
    return 0;
  }
  $this->control_view($row);
 }else{ 
  $this->control_list();
 }
}

function control_check (&$data, &$err){
  
  if(empty($data['control_login'])){$err['control_login']=mandatory;}
  
  if($nickname=$data['control_login'] and $data["action"]=='insert'){
    $query="select Count(*) as count from Control where control_login="._esc($nickname);
    if(!$res=ShopDB::query_one_row($query)){
      user_error(shopDB::error());
      return 0;
    }
    if($res["count"]>0){$err['control_login']=already_exist;}
  }
  if($data["action"]=='insert'){
    if(empty($data['password1']) or empty($data['password2'])){$err['password']=invalid;}
    if($data['password1'] and $data['password2']){
       if($data['password1']!=$data['password2']){$err['password']=pass_not_egal;}
      if(strlen($data['password1'])<5){$err['password']=pass_too_short;}
    }
   }
   if($data["action"]=='update'){ 
     if($pass=$data["old_password"]){
       $query="select control_password from Control where control_login="._esc($data["control_login"]);
       if(!$row=ShopDB::query_one_row($query)){
          user_error(shopDB::error());
          return 0;
        }
       if(md5($pass)!=$row["control_password"]){ 
         $err["old_password"]=login_invalid;
       }else{ 
         if($data['new_password1']!=$data['new_password2']){$err['new_password']=pass_not_egal;}
        if(strlen($data['new_password1'])<5){$err['new_password']=pass_too_short;}
         
       }
       
     }
   }
  return empty($err);

}

}
?>