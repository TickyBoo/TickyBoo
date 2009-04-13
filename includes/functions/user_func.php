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

function login ($username,$password){  
  $query="select * from auth where username='$username' and password='".md5($password)."'";
  //echo $query;
  if($result=ShopDB::query($query) and $obj=shopDB::fetch_object($result)){
    $_SESSION['SHOP_USER_ID']=$obj->user_id;
    return $obj->user_id;
  }
  return FALSE;
}

function logout (){  
  unset($_SESSION['SHOP_USER_ID']);
}


function _create_user ($guest,$status,&$err,$short,$mandatory=0){

	if(!$mandatory){
	  $mandatory=array('user_lastname','user_firstname','user_addresse',
		'user_zip','user_city','user_country');
		if(!$short){
			$mandatory[]='user_email';
			$mandatory[]='check_condition';
		}
	}
	
	foreach($mandatory as $field){
		if(empty($guest[$field])){$err[$field]=mandatory;}
	}
	
	if(!empty($guest['user_email'])){
		if(!eregi("^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$", $guest['user_email'])){
    	$err['user_email']=not_valid_email;
		}		
	}

	
/*
if(empty($guest['user_lastname'])){$err['user_lastname']=mandatory;}
  if(empty($guest['user_firstname'])){$err['user_firstname']=mandatory;}
  if(empty($guest['user_addresse'])){$err['user_addresse']=mandatory;}
  if(empty($guest['user_zip'])){$err['user_zip']=mandatory;}
  if(empty($guest['user_city'])){$err['user_city']=mandatory;}
  if(empty($guest['user_country'])){$err['user_country']=mandatory;}
  if(!$short){
    if(empty($guest['user_email'])){$err['user_email']=mandatory;}
    if(empty($guest['check_condition'])){$err['check_condition']=mandatory;}
	if(empty($guest['check_use'])){$err['check_use']=mandatory;}
    $email=$guest['user_email'];
    $check_mail= eregi("^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$", $email);//"
    if(!$check_mail){$err['user_email']=not_valid_email;}
  } 
*/	
	
  if(!empty($err)){
    return FALSE;
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
  
  //echo $query;
  if($result=ShopDB::query($query) and $user=shopDB::insert_id()){
    return $user;
  }
}

///////////////////////////
/////UPDATE FUNCTION///////
///////////////////////////
function _update_user ($guest,$status,&$err,$short,$mandatory=0){

	if(!$mandatory){
	  $mandatory=array('user_lastname','user_firstname','user_addresse',
		'user_zip','user_city','user_country');
		if(!$short){
			$mandatory[]='user_email';
		}
	}
	
	foreach($mandatory as $field){
		if(empty($guest[$field])){$err[$field]=mandatory;}
	}
	
	if(!empty($guest['user_email'])){
		if(!eregi("^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$", $guest['user_email'])){
    	$err['user_email']=not_valid_email;
		}		
	}

	
	if(empty($guest['user_lastname'])){$err['user_lastname']="Required";}
	if(empty($guest['user_firstname'])){$err['user_firstname']="Required";}
	if(empty($guest['user_addresse'])){$err['user_addresse']="Required";}
	if(empty($guest['user_zip'])){$err['user_zip']="Required";}
	if(empty($guest['user_city'])){$err['user_city']="Required";}
	if(empty($guest['user_country'])){$err['user_country']="Required";}
	if(!$short){
		if(empty($guest['user_email'])){$err['user_email']="Required";}
		$email=$guest['user_email'];
		$check_mail= eregi("^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$", $email);//"
		if(!$check_mail){$err['user_email']="Your Email should be something@somthing.this";}
	  }
	
  if(!empty($err)){
    return FALSE;
  }
  
  $query="UPDATE User SET ".
  "user_lastname="._esc($guest['user_lastname']).",".
  "user_firstname="._esc($guest['user_firstname']).",".
  "user_address="._esc($guest['user_addresse']).",".
  "user_address1="._esc($guest['user_addresse1']).",".
  "user_zip="._esc($guest['user_zip']).",".
  "user_city="._esc($guest['user_city']).",".
  "user_state="._esc($guest['user_state']).",".
  "user_country="._esc($guest['user_country']).",".
  "user_email="._esc($guest['user_email']).",".
  "user_phone="._esc($guest['user_phone']).",".
  "user_fax="._esc($guest['user_fax'])." WHERE user_id=".$guest['user_id']."";   
  
  //echo $query;
  if($result=ShopDB::query($query)){
    return $guest['user_id'];
  }
}

function create_guest (&$guest,&$err,$short=FALSE,$mandatory=0){
  return _create_user($guest,3,$err,$short,$mandatory);
}

function create_member (&$member,&$err,$mandatory=0){
global $_SHOP;

  if(!empty($member['password1']) and !empty($member['password2'])
     and $member['password1']==$member['password2'] and
     strlen($member['password1'])>4) {
		  if(empty($member['user_email'])){
			   $err['user_email']=mandatory;
		  }
      $query="select count(*) from auth where username='".$member['user_email']."'";
		  if($result=ShopDB::query($query) and $row=shopDB::fetch_array($result) and $row[0]>0){
        	$err['user_email']=alreadyexist;
         	return FALSE;
      }
      if(!$user_id=_create_user($member,2,$err,FALSE,$mandatory)){
          echo 'No user id';
        	return FALSE;
      }
	    $active = md5(uniqid(rand(), true));
      $query="INSERT INTO `auth` (username,password,user_id,active) VALUES (".
              _esc($member['user_email']).",".
              _esc(md5($member['password1'])).",".
              _esc($user_id).",'$active')";
       

	    if(!ShopDB::query($query)){
           echo 'No inserting into auth';
           return FALSE;
   	  }
   	  $query="select * from auth,User where username=".ShopDB::quote($member['user_email'])." and auth.user_id=User.user_id";
  	  if(!$row=ShopDB::query_one_row($query)){
   		  return FALSE;
   	  }
       	// New Method of sending actiavtion email using template
     	require_once("classes/TemplateEngine.php");
    	require_once("classes/htmlMimeMail.php");

     	$email=$_POST['user_email'];
  		$engine= new TemplateEngine();
	  	if(empty($tpl)){
				$tpl='Signup_email';
			}
  		$tpl=$engine->getTemplate($tpl);
	  	$email=&new htmlMimeMail();

  		$link= $_SHOP->root."index.php?register_user=on&action=activate&x=".$user_id."&y=".$active;
  		$row['link']=$link;
  		$tpl->build($email,$row);
      // echo      $user_id;
  		if($email->send($tpl->to)){
        	return $user_id;
	  	}else{
		  	echo("Email Error");
		  }

  }else{
     $err['password']=invalid;
     return FALSE;
  }
}

function update_member2 (&$member,&$err,$mandatory=0){

  if(!empty($member['user_id']) or !empty($member['password1']))
  {
  /////////////////////////
  ///Check user password///
  /////////////////////////
	$query="SELECT username FROM auth WHERE user_id='".$member['user_id']."' and password='".md5($member['password1'])."'";
	if(!$result=ShopDB::query($query) or !$user=shopDB::fetch_assoc($result)){
	  $err['password']="Incorrect Password";
	  return FALSE;
	}	
		
	if(empty($member['user_email'])){
		$err['user_email']=mandatory;
	}
	/*
	$query="select count(*) from auth where username='".$member['user_email']."'";
	if($result=ShopDB::query($query) and $row=shopDB::fetch_array($result) and $row[0]>0){
		$err['user_email']=alreadyexist;
		return FALSE;
	}
	*/
	if(!$user_id=_update_user($member,2,$err,FALSE,$mandatory)){
		return FALSE;
	}
	//$active = md5(uniqid(rand(), true));
	$query="UPDATE auth SET username="._esc($member['user_email'])." WHERE user_id=".$member['user_id']."";
   
	if(!ShopDB::query($query)){
	  return FALSE;
	}
	/*$body = "Thank you for registering at the User Registration site. To activate your account, please click on this link:\n\n";
	$body .= "http://www.pepper.noctem.co.uk/index.php?register_user=on&action=activate&x=$user_id&y=$active";
	mail($_POST['user_email'], 'Registration Confirmation', $body, 'From: admin@noctem.co.uk');*/
	
	return $user_id;
  }else{
       $err['password']="Incorrect password";
       return FALSE;
     }
}



function _esc ($str){
  return "'".shopDB::escape_string($str)."'";
}

function load_user ($user_id){
  $query="select * from User where user_id="._esc($user_id);
  if(!$user=ShopDB::query_one_row($query)){
    return FALSE;
  }
  
  return $user;
}

function auth_username(){
  //this value is set in functions/init.php
  return $_SESSION['_SHOP_AUTH_USER_NAME'];
}

function auth_userdata(){
  //this value is set in functions/init.php
  return $_SESSION['_SHOP_AUTH_USER_DATA'];
}
?>