<?php
/*
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
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@noctem.co.uk if any conditions of this licencing isn't 
 * clear to you.
 
 */

require_once("classes/ShopDB.php");
require_once("admin/AdminView.php");
require_once("classes/Organizer.php");

class OrganizerView extends AdminView{

function get_currency (){
  return array("CHF"=>"CHF","Euro"=>"Euro");
}


function organizer_view (&$data){
  global $_SHOP;

  echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
  echo "<tr><td class='admin_list_title' colspan='2'>".$data["organizer_name"]."</td></tr>";   
//  $this->print_field('organizer_id',$data);
  $this->print_field('organizer_name',$data);
  $this->print_field('organizer_address',$data);
  $this->print_field('organizer_plz',$data);
  $this->print_field('organizer_ort',$data);
  $this->print_field('organizer_phone',$data );
  $this->print_field('organizer_fax',$data );
  $this->print_field('organizer_email',$data );
  $this->print_field('organizer_nickname',$data);
  $this->print_field('organizer_currency',$data);
  $this->view_file('organizer_logo' ,$data,$err,'img');

  $this->print_set('organizer_place',$data["organizer_place"],"Ort","ort_name","ort_id","view_ort.php");

  echo "</table>\n";
  if($_SHOP->is_admin){
    echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}'>".admin_list."</a></center>";
  }
}

function organizer_form (&$data, &$err,$title,$mode){
  global $_SHOP;

	echo "<form method='POST' action='{$_SERVER['PHP_SELF']}' enctype='multipart/form-data'>\n";
  echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
  echo "<tr><td class='admin_list_title' colspan='2'>".$title."</td></tr>";
//  $this->print_field_o('organizer_id',$data);
  
//  if($_SHOP->is_admin){// and $mode=='add'){
//    $this->print_input('organizer_nickname',$data, $err,10,10);
//  }else{
//    $this->print_field('organizer_nickname',$data, $err,10,10);
//  }  
  
  $this->print_input('organizer_name',$data, $err,25,100);
  $this->print_input('organizer_address',$data, $err,25,100);
  $this->print_input('organizer_plz',$data, $err,25,100);
  $this->print_input('organizer_ort',$data, $err,25,100);
  $this->print_input('organizer_state',$data, $err,25,100);
  echo "<tr><td class='admin_name'>" . organizer_country . "</td><td class='admin_value'>";
  $countries = new CountriesList();
  $countries->printForm('organizer_country', $data['organizer_country'], $err);
  echo "</td></tr>";

  $this->print_input('organizer_phone',$data, $err,25,100 );
  $this->print_input('organizer_fax',$data, $err,25,100 );
  $this->print_input('organizer_email',$data, $err,25,100 );
  $this->print_input('organizer_currency',$data, $err,4,3 );

#  $currency=$this->get_currency();
#  $sel[$data["organizer_currency"]]=" selected ";
#  echo "<tr><td class='admin_name'  width='40%'>".$this->con(organizer_currency)."</td>
#  <td class='admin_value'><select name='organizer_currency'>";
  
#  foreach($currency as $k=>$v){
#    echo "<option value='$k' ".$sel[$k].">$v</option>\n";
#  }
#  echo "</select><span class='err'>{$err["organizer_currency"]}</span></td></tr>\n";
  
  $this->print_file('organizer_logo',$data,$err);

//	echo "<br/>";
  
  if($mode=='add'){
    echo "<tr> <td class='admin_name'>".password."</td>
         <td class='admin_value'><input type='password' name='password1' size='10'  maxlength='10'><span class='err'>{$err['password']}</span></td></tr>
         <tr> <td class='admin_name'>".password2."</td>
         <td class='admin_value'><input type='password' name='password2' size='10'  maxlength='10'></td></tr>";
  }
  if($mode=='update'){
    echo "<tr> <td class='admin_name'>".old_password."</td>
         <td class='admin_value'><input type='password' name='old_password' size='10'  maxlength='10'>
	 <span class='err'>{$err['old_password']}</span></td></tr>
         <tr> <td class='admin_name'>".new_password."</td>
         <td class='admin_value'><input type='password' name='new_password1' size='10'  maxlength='10'><span class='err'>{$err['new_password']}</span></td> </tr>
	 <tr> <td class='admin_name'>".new_password."</td>
         <td class='admin_value'><input type='password' name='new_password2' size='10'  maxlength='10'></td></tr>";
  }

  if($data['organizer_id']){
    echo "<input type='hidden' name='organizer_id' value='{$data['organizer_id']}'/>\n";
    echo "<input type='hidden' name='action' value='update'/>\n";
  }else{
    echo "<input type='hidden' name='action' value='insert'/>\n";
  }
  
  echo "<tr><td align='center' class='admin_value' colspan='2'>
  <input type='submit' name='save' value='".save."'> &nbsp;";
  
  if($mode=='update' and $_SHOP->is_admin){
    echo "<input type='submit' name='copy' value='".copy."'>";
  }
  
  echo "<input type='reset' name='reset' value='".res."'></td></tr>";
  echo "</table>\n";

  if($_SHOP->is_admin){
    echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}'>".admin_list."</a></center>";
  }
}


function organizer_list (){
  $query="SELECT * FROM Organizer";
  if(!$res=ShopDB::query($query)){
    user_error(shopDB::error());
    return;
  }


  $alt=0;
  echo "<table class='admin_list' width='$this->width' cellpadding='5' cellspacing='0'>\n";
  echo "<tr><td class='admin_list_title' colspan='5' align='center'>".organizer_title."</td></tr>\n";

  while($row=shopDB::fetch_assoc($res)){
    echo "<tr class='admin_list_row_$alt'>";
    echo "<td class='admin_list_item' align='right'>{$row['organizer_id']}</td>\n";
    echo "<td class='admin_list_item' width='80%'>{$row['organizer_name']} ({$row['organizer_nickname']})</td>\n";
    echo "<td class='admin_list_item'><a class='link' href='{$_SERVER['PHP_SELF']}?action=view&organizer_id={$row['organizer_id']}'><img src='images/view.png' border='0' alt='".view."' title='".view."'></a></td>\n";
    echo "<td class='admin_list_item'><a class='link' href='{$_SERVER['PHP_SELF']}?action=edit&organizer_id={$row['organizer_id']}'><img src='images/edit.gif' border='0' alt='".edit."' title='".edit."'></a></td>\n";
    echo "<td class='admin_list_item'><a class='link' href='javascript:if(confirm(\"".delete_item."\")){location.href=\"{$_SERVER['PHP_SELF']}?action=remove&organizer_id={$row['organizer_id']}\";}'><img src='images/trash.png' border='0' alt='".remove."' title='".remove."'></a></td>\n";
    echo "</tr>";
    $alt=($alt+1)%2;
  }
  echo "</table>\n";
  
  echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}?action=add'>".add."</a></center>";
}

function draw () { 
global $_SHOP;

  // organizer user, can only update details of its record
  if(!$_SHOP->is_admin){

    if($_POST['action']=='update'){

      if(!$this->organizer_check($_POST,$err)){
        $this->organizer_form($_POST,$err,organizer_update_title,"update");
      }else{

        $query="UPDATE Organizer SET
        organizer_name='".$this->q($_POST['organizer_name'])."',
        organizer_address='".$this->q($_POST['organizer_address'])."',
        organizer_plz='".$this->q($_POST['organizer_plz'])."',
        organizer_ort='".$this->q($_POST['organizer_ort'])."',
        organizer_email='".$this->q($_POST['organizer_email'])."',
        organizer_fax='".$this->q($_POST['organizer_fax'])."',
        organizer_phone='".$this->q($_POST['organizer_phone'])."',
        organizer_currency='".$this->q($_POST['organizer_currency'])."',
        organizer_state='".$this->q($_POST['organizer_state'])."',
        organizer_country='".$this->q($_POST['organizer_country'])."'
        WHERE organizer_id='{$_SHOP->organizer_id}'";

        if(!ShopDB::query($query)){
          return 0;
        }

        if(!$this->logo_post($_POST,$_SHOP->organizer_id)){
          echo "<div class=error>".img_loading_problem."<div>";
        }


        if(isset($_POST["old_password"]) and isset($_POST["new_password1"])){
          $query="UPDATE Admin set
                     admin_password='".$this->q(md5($_POST['new_password1']))."'
                  where admin_id='{$_SHOP->organizer_id}'
  	              and admin_password='".$this->q(md5($_POST['old_password']))."'";

          if(!ShopDB::query($query)){
            return FALSE;
          }
        }

      }
    }

    $query="SELECT * FROM Organizer WHERE organizer_id='{$_SHOP->organizer_id}'";
    if(!$row=ShopDB::query_one_row($query)){
      return 0;
    }
    $this->organizer_form($row,$err,organizer_update_title,"update");

    return;
  }


  //admin user has more possibilities: add, remove, edit organizers, change login, copy

  if($_POST['action']=='insert'){
    if(!$this->organizer_check($_POST,$err)){
      $this->organizer_form($_POST,$err,organizer_add_title,"add");
    }else{
      $id=Organizer::create($_POST);
      if(!$this->logo_post($_POST,$id)){
        echo "<div class=error>".img_loading_problem."<div>";
      }
      $this->organizer_list();
    }
  }else
  if($_POST['action']=='update' and $_POST['save']){
    if(!$this->organizer_check($_POST,$err)){
      $this->organizer_form($_POST,$err,organizer_update_title,"update");
    }else{

      $query="UPDATE Organizer SET
      organizer_name='".$this->q($_POST['organizer_name'])."',
      organizer_address='".$this->q($_POST['organizer_address'])."',
      organizer_plz='".$this->q($_POST['organizer_plz'])."',
      organizer_email='".$this->q($_POST['organizer_email'])."',
      organizer_fax='".$this->q($_POST['organizer_fax'])."',
      organizer_phone='".$this->q($_POST['organizer_phone'])."',
      organizer_state='".$this->q($_POST['organizer_state'])."',
      organizer_country='".$this->q($_POST['organizer_country'])."',
      organizer_currency='".$this->q($_POST['organizer_currency'])."',
      WHERE organizer_id='".$this->q($_POST['organizer_id'])."'";

      if(!ShopDB::query($query)){
        return 0;
      }
        if(!$this->logo_post($_POST,$_POST['organizer_id'])){
          echo "<div class=error>".img_loading_problem."<div>";
        }


      if(isset($_POST["old_password"]) and isset($_POST["new_password1"]) and isset($_POST['organizer_nickname'])){
        $query="UPDATE Admin set
             admin_password='".$this->q(md5($_POST['new_password1']))."' where admin_id='{$_POST["organizer_id"]}'
  	   and admin_password='".$this->q(md5($_POST['old_password']))."'";

        if(!ShopDB::query($query)){
          return FALSE;
        }

      }

      //does nothing if nickname is not changed
      Organizer::rename($_POST['organizer_id'],$_POST['organizer_nickname']);

      $this->organizer_list();
    }
  }else
  if($_POST['action']=='update' and $_POST['copy']){
    if(!$this->organizer_check($_POST,$err)){
      $this->organizer_form($_POST,$err,organizer_update_title,"update");
    }else{
      Organizer::copy($_POST);
      $this->organizer_list();
    }
  }else
  if($_GET['action']=='add'){
    $this->organizer_form($row,$err,organizer_add_title,"add");
  }else
  if($_GET['action']=='edit'){
    $query="SELECT * FROM Organizer WHERE organizer_id='{$_GET['organizer_id']}'";
    if(!$row=ShopDB::query_one_row($query)){
      return 0;
    }
    $this->organizer_form($row,$err,organizer_update_title,"update");
  }else
  if($_GET['action']=='view'){
    $query="SELECT * FROM Organizer WHERE organizer_id='{$_GET['organizer_id']}'";
    if(!$row=ShopDB::query_one_row($query)){
      return 0;
    }
    $this->organizer_view($row);
  }else
  if($_GET['action']=='remove' and $_GET['organizer_id']>0 and $_SHOP->is_admin){
    Organizer::remove($_GET['organizer_id']);
    $this->organizer_list();
  }else{
    $this->organizer_list();
  }

}

function logo_post ($data,$organizer_id){
  global $_SHOP;

	return $this->file_post($data,$organizer_id, 'Organizer', 'organizer','_logo');

	
/*	
  if(!empty($_FILES['organizer_logo']) and !empty($_FILES['organizer_logo']['name']) and !empty($_FILES['organizer_logo']['tmp_name'])){
    if(!preg_match('/\.\w+$/',$_FILES['organizer_logo']['name'],$ext)){
      return FALSE;
    }
    
    $doc_name = "logo$organizer_id".$ext[0];
    $dir=$this->user_file("/web/files");
    $root="files";

    if(!move_uploaded_file ($_FILES['organizer_logo']['tmp_name'], $dir."/".$doc_name)){
      return FALSE;
    }
    chmod( $dir."/".$doc_name,$_SHOP->file_mode);
    
    $query="UPDATE Organizer SET organizer_logo='$root/$doc_name' WHERE organizer_id='$organizer_id'";
  }

  if($data['remove_organizer_logo']==1){
    $query="UPDATE Organizer SET organizer_logo='' WHERE organizer_id='$organizer_id'";
  }
  
  if($query){
    if(ShopDB::query($query)){
      return TRUE;
    }
  
    return FALSE;
  }else{
    return TRUE;
  }
	*/
}

function organizer_check (&$data, &$err){
  global $_SHOP;
  if(!$_SHOP->is_admin){
    $data["organizer_id"]=$_SHOP->organizer_id;
  }

  if(empty($data['organizer_name'])){$err['organizer_name']=mandatory;}
  if(empty($data['organizer_currency'])){$err['organizer_currency']=mandatory;}
  if(empty($data['organizer_address'])){$err['organizer_address']=mandatory;}
  if(empty($data['organizer_plz'])){$err['organizer_plz']=mandatory;}
  if(empty($data['organizer_ort'])){$err['organizer_ort']=mandatory;}
  if($_SHOP->is_admin){ 
    if(empty($data['organizer_nickname'])){
      $err['organizer_nickname']=mandatory;
    }elseif(strlen($data['organizer_nickname'])>10){
      $err['organizer_nickname']=invalid;
    }
  }  
  
  if($nickname=$data['organizer_nickname'] and $_SHOP->is_admin){
    $query="select Count(*) as count from Admin where admin_login='$nickname'";
    if(!$res=ShopDB::query_one_row($query)){
      user_error(shopDB::error());
      return 0;
    }
    if($res["count"]>0){
      if(!($res["count"]==1 and $data['organizer_id'])){
        $err['organizer_nickname']=already_exist;      
      }
    }
  }
  //if(empty($data['user_email'])){$err['user_email']=mandatory;}
  if($email=$data['organizer_email']){
    $check_mail= eregi("^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$", $email);
    if(!$check_mail){$err['organizer_email']=not_valid_email;} 
  }

  if(!$data["organizer_id"]){
    if(empty($data['password1']) or empty($data['password2'])){$err['password']=invalid;}
    if($data['password1'] and $data['password2']){
       if($data['password1']!=$data['password2']){$err['password']=pass_not_egal;}
      if(strlen($data['password1'])<5){$err['password']=pass_too_short;}
    }
   }
   if($data["organizer_id"]){ 
     if($pass=$data["old_password"]){
       $query="select admin_password from Admin where admin_id='{$data["organizer_id"]}'";
       if(!$row=ShopDB::query_one_row($query)){
          return 0;
        }
       if(md5($pass)!=$row["admin_password"]){ 
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