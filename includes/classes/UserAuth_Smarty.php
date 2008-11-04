<?php
/**
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

class UserAuth_Smarty {

  var $logged;

  function UserAuth_Smarty (&$smarty){
    if(isset($_SESSION['_SHOP_USER_AUTH'])){
      $user=$_SESSION['_SHOP_USER_AUTH'];
    }else{
      $user=UserAuth_Smarty::_load();
      $_SESSION['_SHOP_USER_AUTH']=$user;
    }

    if($user){
      $this->_fill($user);
      $this->logged=true;

      $smarty->register_object("user_auth",$this);
      $smarty->assign_by_ref("user_auth",$this);
    }  
  }


  function _load (){
    require_once('functions/user_func.php');
    $auth=auth_userdata();
    
    require_once('classes/ShopDB.php');
    $query="select * 
            from User 
	    where user_id='{$auth['user_id']}'
	    limit 1";

    if($result=ShopDB::query($query) and $user=shopDB::fetch_assoc($result)){
      return $user;
    }
    
    return FALSE;
  
  }
  
    function get_users_f (){
    	require_once('classes/ShopDB.php');
		$sqli="SELECT user_id,user_firstname,user_lastname FROM `User` WHERE user_status=3"; 
		if(!$result=ShopDB::queryi($sqli)){echo("Error"); return;}	
		$options="";
		while ($row=shopDB::fetch_array($result)) {
			$id=$row["user_id"];
			//$selected = ($id==$selectid) ? ' selected="selected"' : '';  
			$firstname=$row["user_firstname"];
			$lastname=$row["user_lastname"];
			$options.="<OPTION VALUE=\"{$id}\" {$selected}>".$id." - ".$firstname." - ".$lastname."</OPTION>\n"; 
		}
		return $options;
	}


  
  function _fill ($user){
    $this->_clean();
    foreach($user as $k=>$v){
      $this->$k=$v;
    }
  }

  function _clean (){
    $user=(array)$this;
    foreach($user as $k=>$v){
      unset($this->$k);
    }
  }

  function set_prefs ($params,&$smarty){
    $this->set_prefs_f($params['prefs']);
  }
  
  function set_prefs_f ($prefs){
    require_once('functions/user_func.php');
    $auth=auth_userdata();
    
    $this->user_prefs=$prefs;
  
    require_once("classes/ShopDB.php");
    $query="update User set user_prefs='{$this->user_prefs}' 
            where user_id='{$auth['user_id']}'
	    limit 1";
	    
    if(ShopDB::query($query) and shopDB::affected_rows()==1){
       //print_r($_SESSION['_SHOP_USER_AUTH']);
       $_SESSION['_SHOP_USER_AUTH']['user_prefs']=$this->user_prefs;
       return TRUE;
    }
    return FALSE;
    	     
  }


}
?>