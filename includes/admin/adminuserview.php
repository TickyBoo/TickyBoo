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

require_once("admin/AdminView.php");

class AdminUserView extends AdminView{

function admin_form (&$data,&$err,$title,$add='add'){
global $_SHOP;
  echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
  echo "<tr><td class='admin_list_title' colspan='2'>".$title."</td></tr>"; 
  echo "<form method='POST' action='{$_SERVER['PHP_SELF']}'>\n";

  $this->print_input('admin_login',$data,$err,30,100);
  if($add=='update'){
     echo "<input type='hidden' name='admin_id' value='{$data['admin_id']}'/>\n";
     echo "<input type='hidden' name='action' value='update'/>\n";
     echo "<tr> <td class='admin_name'>".con('old_password')."</td>
           <td class='admin_value'><input type='password' name='old_password' size='10'  maxlength='10'>
	         <span class='err'>{$err['old_password']}</span></td></tr>";
   } else {
    echo "<input type='hidden' name='action' value='insert'/>\n";
   }
     echo "<tr> <td class='admin_name'>".con('new_password')."</td>
          <td class='admin_value'><input type='password' name='password1' size='10'  maxlength='10'><span class='err'>{$err['password']}</span></td></tr>
          <tr> <td class='admin_name'>".con('password2')."</td>
          <td class='admin_value'><input type='password' name='password2' size='10'  maxlength='10'></td></tr>";

  
  echo "<tr><td align='center' class='admin_value' colspan='2'>
        <input type='submit' name='submit' value='".con('save')."'>
        <input type='reset' name='reset' value='".con('res')."'></td></tr>";
  echo "</table></form>\n";
  echo "<center><a class='link' href='{$_SERVER['PHP_SELF']}'>".con('admin_list')."</a></center>";
}

 function admin_list (){
  global $_SHOP;
  $query="SELECT * FROM Admin where admin_status="._esc($this->admintype);
  if(!$res=ShopDB::query($query)){
    user_error(shopDB::error());
    return;
  }
  
  $alt=0;
  echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='4' border=0>\n";
  echo "<tr><td class='admin_list_title' colspan='2' align='center'>".con($this->admintype.'_user_title')."</td></tr>\n";
  
  
  while($row=shopDB::fetch_assoc($res)){
    echo "<tr class='admin_list_row_$alt'>";
    echo "<td class='admin_list_item' width='550' >{$row['admin_login']}</td>\n";
    echo "<td class='admin_list_item' nowrap='nowrap' align='right'  >\n";
    echo "<a class='link' href='{$_SERVER['PHP_SELF']}?action=edit&admin_id={$row['admin_id']}'><img src='images/edit.gif' border='0' alt='".con("edit")."' title='".con("edit")."'></a>\n";
    echo "<a class='link' href='javascript:if(confirm(\"".con("delete_item")."\")){location.href=\"{$_SERVER['PHP_SELF']}?action=remove&admin_id={$row['admin_id']}\";}'><img src='images/trash.png' border='0' alt='".con("remove")."' title='".con("remove")."'></a>\n";
    echo "</td></tr>";
    $alt=($alt+1)%2;
  }
  echo "</table>\n";
  
  echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}?action=add'>".con('add')."</a></center>";
}

function draw ($admintype) {
  global $_SHOP;
  $this->admintype = $admintype;
  if($_POST['action']=='insert'){
    $_POST['admin_status'] = $admintype;
    if(!$this->admin_check($_POST,$err)){
      $this->admin_form($_POST,$err,con($admintype.'_user_add_title'));
    }else{
      $query="INSERT INTO Admin (admin_login, admin_password, admin_status)".
             " VALUES ("._ESC($_POST['admin_login']).","._ESC(md5($_POST['password1'])).","._esc($admintype).")";
      if(!ShopDB::query($query)){
        user_error(shopDB::error());
        return 0;
      }
      $this->admin_list();
    }
  }elseif($_POST['action']=='update'){
    $_POST['admin_status'] = $admintype;
    if(!$this->admin_check($_POST, $err, true)){
      $this->admin_form($_POST, $err, con($admintype.'_user_update_title') ,'update');
    }else{
      $query="UPDATE Admin set
           admin_login="._ESC($_POST['admin_login']).",
           admin_password="._ESC(md5($_POST['password1']))."
           where admin_id="._esc($_POST["admin_id"]);

      if(!ShopDB::query($query)){
        user_error(shopDB::error());
        return FALSE;
      }
    $this->admin_list();
    }
  }elseif($_GET['action']=='add'){
    $this->admin_form($row, $err, con($admintype.'_user_add_title'));

  }elseif($_GET['action']=='edit'){
    $query="SELECT * FROM Admin WHERE admin_id="._esc($_GET['admin_id']);
    if(!$row=ShopDB::query_one_row($query)){
      user_error(shopDB::error());
      return 0;
    }
    $this->admin_form($row,$err,con($admintype.'_user_update_title'),'update');
  }elseif($_GET['action']=='remove' and $_GET['admin_id']){
    $query="DELETE FROM Admin WHERE admin_id="._esc($_GET['admin_id']);
    if(!ShopDB::query($query)){
      user_error(shopDB::error());
      return 0;
    }
    $this->admin_list();
  }else{
    $this->admin_list();
  }
}

function admin_check (&$data, &$err){
  $nickname=$data['admin_login'];
  if(empty($data['admin_login'])){
    $err['admin_login']=mandatory;
    
  } elseif($data["action"]=='insert'){
    $query="select Count(*) as count from Admin where admin_login="._esc($nickname);
    if(!$res=ShopDB::query_one_row($query)){
      user_error(shopDB::error());
      return 0;
    }
    if($res["count"]>0){$err['admin_login']=con('already_exist');}
  }
  if($data["action"]=='update'){
    if($pass=$data["old_password"]){
       $query="select admin_password from Admin where admin_id="._esc($data["admin_id"]);
       if(!$row=ShopDB::query_one_row($query)){
          user_error(shopDB::error());
          $err["old_password"]=login_invalid;
          return 0;
        }
       if(md5($pass)!=$row["admin_password"]){
         $err["old_password"]=login_invalid;
       }
     }
  }
  if(empty($data['password1']) or empty($data['password2'])){
    $err['password']=invalid;
  } elseif($data['password1']!=$data['password2']){
    $err['password']=pass_not_egal;
  } elseif(strlen($data['password1'])<5){
    $err['password']=pass_too_short;
  }

  return empty($err);

}

}
?>