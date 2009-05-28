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

require_once('classes/User.php');

class User_Smarty {

  var $logged;

  function User_Smarty (&$smarty)
  {
    if(isset($_SESSION['_SHOP_USER'])){
      $user=$_SESSION['_SHOP_USER'];
      $this->_fill($user);
      $this->logged=true;
    }
    $smarty->register_object("user",$this);
    $smarty->assign_by_ref("user",$this);

    
  }


  function login ($params,&$smarty){
  	$this->login_f($params['username'],$params['password'],$err);
   	$smarty->assign("login_error",$err);
  }

  function login_f ($username, $password, &$err){
    If ($user = User::Login($username, $password, $err)) {
    	$this->_fill($user);
    	$this->logged=true;
    	$this->is_member=true;
  	  $url = ($params['uri'])?$params['uri']:$_SERVER["REQUEST_URI"];
      echo "<script>window.location.href='{$url}';</script>";
      exit;
    }
  }

  function logout ($params,&$smarty){
    $this->logout_f();
  }

  function logout_f (){
    User::logout();
    $this->_clean();
  }


 /* User data gets subbmitted to here */ 
  function register ($params, &$smarty){
    if(!$this->register_f($params['ismember'], $params['data'], $err, $params['mandatory'], $params['secure'] )){
      $smarty->assign('user_errors',$err);
    }
  }

/*The next bit of code creates users */
  function register_f ($ismember, &$member,&$err, $mandatory_l=0, $secure=''){
    $type =($ismember)?2:3;
    if($res = User::register($type, $member, $err, convMandatory($mandatory_l) , $secure)){ /* $res == the returned $user_id from create_member in user_func.php */
  	  $url = "{$_SERVER["PHP_SELF"]}?action=activate";
      echo "<script>window.location.href='{$url}';</script>";
      exit;
    }  
    return false;
//    echo "error";
  }
///////////////////
//Update Member Function!
/////////////////////

  function update($params,&$smarty){
  	if(!$this->update_f($params['data'],$err,$params['mandatory'])){
  		$smarty->assign('user_errors',$err);
	  }
  }
  
  function update_f (&$member, &$err, $mandatory_l=0){
    If ($this->user_id <> $member['user_id']) {
      die('System error while changing user data');
    }
    $mandatory = convMandatory($mandatory_l);
    if (User::Update($member, $err, $mandatory_l=0)) {
      $user = User::load_user($this->user_id);
      $this->_fill($user);
      $this->logged=true;
      return true;
    } else {
      return false;
    }
  }

/////////////////
/////////////////  
  function forgot_password ($params,&$smarty){
    $this->forgot_password_f($params['email']);
  }

  function forgot_password_f ($email){
    return User::forgot_password($email);
  }
  
  function resend_activation($params,&$smarty){
  	$this->resend_activation_f($params['email']);
	}
	
	function resend_activation_f($email){
    User::resend_activation($email);
	}
  
  function _fill ($user){ ///????
    $this->_clean();
    foreach($user as $k=>$v){
      $this->$k=$v; /// What does this do? Sets User_Smary->$k as $v ?
    }
  }

  function _clean (){
    $user=(array)$this;
    foreach($user as $k=>$v){
      unset($this->$k);
    }
  }
  

  function Activate(){
    global $smarty;
    if (!isset($request['uar'])) {
      return false;
    }
    if (!User::Activate($request['uar'], $errors)) {
      $smarty->assign('errors',$errors);
      return true ;
    }
    return true;
  }
}
?>