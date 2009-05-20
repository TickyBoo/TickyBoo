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



/** 
 * POSSIBLE LEGACY VERSION NOT SURE ITS USED ANYMORE
 * IN Proccess of checking!
 */
class User{
  var $user_id;
  var $user_firstname;
  var $user_lastname;
  var $is_guest;
  
  function login ($username,$password){  
    $query="select * from auth where username='$username' and password='".md5($password)."'";
    if($result=ShopDB::query($query) and $obj=shopDB::fetch_object($result)){
      $obj->is_guest=0;
      $_SESSION['SHOP_USER_ID']=$obj;
      return $obj->user_id;
    }
    return FALSE;
  }

  function logout (){
    unset($_SESSION['SHOP_USER_ID']);
  }

  function is_logged (){
    if(!isset($_SESSION['SHOP_USER_ID'])){
      return 0;
    }
    return 1;
  }

  function _create_user ($guest,$status){
    global $_USER_ERROR;

    if(!isset($guest['user_lastname'])){$err['user_lastname']=mandatory;}
    if(!isset($guest['user_firstname'])){$err['user_firstname']=mandatory;}
    if(!isset($guest['user_addresse'])){$err['user_addresse']=mandatory;}
    if(!isset($guest['user_zip'])){$err['user_zip']=mandatory;}
    if(!isset($guest['user_city'])){$err['user_city']=mandatory;}
    if(!isset($guest['user_state'])){$err['user_state']=mandatory;}
    if(!isset($guest['user_country'])){$err['user_country']=mandatory;}
    if(!isset($guest['user_email'])){$err['user_email']=mandatory;}
    if(!isset($guest['check_condition'])){$err['check_condition']=mandatory;}
    if(!isset($guest['check_use'])){$err['check_use']=mandatory;}


    if($err){
      $_USER_ERROR=$err;
      return FALSE;
    }else{
      $_USER_ERROR=0;
    }

    $query="insert into User ( user_lastname, user_firstname, user_address,".
    "user_address1,user_zip,user_city,user_state,user_country,user_email,user_phone,user_fax,user_status)".
    " VALUES (".
    _esc($guest['user_lastname']).",".
    _esc($guest['user_firstname']).",".
    _esc($guest['user_addresse']).",".
    _esc($guest['user_addresse1']).",".
    _esc($guest['user_zip']).",".
    _esc($guest['user_city']).",".
    _esc($guest['user_state']).",".
    _esc($guest['user_country']).",".
    _esc($guest['user_email']).",".
    _esc($guest['user_phone']).",".
    _esc($guest['user_fax']).",".
    "'$status')";


    if($result=ShopDB::query($query) and $user=shopDB::insert_id()){
      return $user;
    }
  }

  function create_guest ($guest){
    return _create_user($guest,3);
  }

  function create_member ($member){
    global $_USER_ERROR;

    if(isset($member['password1']) and isset($member['password2']) and
       $member['password1']==$member['password2'] and
       strlen($member['password1'])>4){
      if(!isset($member['user_email'])){$_USER_ERROR['user_email']=mandatory;}

      $query="select count(*) from auth where username='".$member['user_email']."'";
      if($result=ShopDB::query($query) and $row=shopDB::fetch_array($result) and $row[0]>0){
        $_USER_ERROR['user_email']=alreadyexist;
        return FALSE;
      }

      if(!$user_id=_create_user($member,2)){/*The 2 here refrences the status */
        return FALSE;
      }
      $active = md5(uniqid(rand(), true));
      $query="insert into auth (username,password,user_id,active) VALUES (".
      _esc($member['user_email']).",".
      _esc(md5($member['password1'])).",".
      _esc($user_id).",'$active')";

      if(!ShopDB::query($query)){
      	return FALSE;
      }
      // new part
      $email=$member['user_email'];
      $engine= new TemplateEngine();

      $tpl=$engine->getTemplate('Signup_email'); // 'Signup_email';
      $email=&new htmlMimeMail();

      $link="".$_SHOP->root."index.php?register_user=on&action=activate&x=$user_id&y=$active";
      $row['link']=$link;
      $tpl->build($email,$row);

      if($email->send($tpl->to)){
      	return true;
      }

      return $user_id;
    }else{
      $_USER_ERROR['password']=invalid;
      return FALSE;
    }
  }

  function shop_user_error (){
    global $_USER_ERROR;
    return $_USER_ERROR;
  }


  function load_user ($user_id){
    $query="select * from User where user_id='$user_id'";
    if(!$user=ShopDB::query_one_row($query)){
      return FALSE;
    }
    return $user;
  }
  
  function cleanup($user_id = 0, $delete=false, $inclTrash=true){
    $where= ($user_id)?"and user_id = ".(int)$user_id:"" ;
    $trash= (!$delete and $inclTrash)?"and order_status<>'trash'":"";
		$query="select user_id
						from `User` left join `Order` on (order_user_id = user_id $trash)
						where user_status= 3
            $where
            group by user_id
            having count(order_id)=0";

    if($result=ShopDB::query($query)) {
  	  $count=shopDB::num_rows($result);
  	  If ($delete) {
        while($data=shopDB::fetch_assoc($result)){
          //  print_r($data);
            ShopDB::query("delete from `User` where user_id =".$data["user_id"] );
        }
      }
      return $count;
    }
    return -1;
  }
}
?>