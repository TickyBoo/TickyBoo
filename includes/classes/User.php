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
  var $is_member = false;

  function load_user ($user_id){
    $query="select User.*, auth.active
            from User left join auth on auth.user_id=User.user_id
            where User.user_id='$user_id'";
    if(!$user=ShopDB::query_one_row($query)){
      return FALSE;
    }
  	$user['is_member'] = ($user['user_status']==2);
    $user['active']    = (empty($user['active']));
  	$_SESSION['_SHOP_USER']=$user_id;
    return $user;
  }

  function login ($username, $password, &$err){
    if(!isset($username)|| !isset($password)){
      $err['msg'] = con('mand_all');
      return false;
    }
  	$sql = "SELECT *
  		FROM auth left join User on auth.user_id=User.user_id
  		WHERE auth.username="._esc($username)."
  		AND auth.password="._esc(md5($password))."
  		AND User.user_status=2
  		LIMIT 1";

  	if(!$res=ShopDB::query_one_row($sql)){
  		$err['msg'] =con('log_err_wrong_usr');
  		$err['info'] =con('log_err_wrong_usr_info');
  		return false;
  	}
  	if($res['active']) {
  		$err['msg'] =con('log_err_not_act');
  		$err['info'] =con('log_err_not_act_info');
  		return FALSE;
  	}
    unset($res['password']);
    unset($res['active']);
  	$res['is_member']=true;
  	$_SESSION['_SHOP_USER']=$res['user_id'];
  	return $res;
  }

  function logout (){
    unset($_SESSION['_SHOP_USER']);

  }

  function is_logged (){
    return (!isset($_SESSION['_SHOP_USER']))? 0: 1;
  }

  function validate_user ($status, $data, &$err, $mandatory=0, $secure, $short) {
  	if(!$mandatory){
  	  $mandatory=array('user_lastname','user_firstname','user_address',
                       'user_zip'     ,'user_city'     ,'user_country',
  			               'user_email');
 		}
    if (!isset($data['user_id'])) {
     $mandatory[]='check_condition';
    }
  	foreach($mandatory as $field){
  		if(empty($data[$field])){$err[$field]=con('mandatory');}
  	}

  	if(!empty($data['user_email'])){
  		if(!eregi("^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$", $data['user_email'])){
      	$err['user_email']=con('not_valid_email');
  		}
  	}

    User::check_NoSpam($secure, $data, $err);
    
    if (!$short and $status==2) {

      if(empty($data['password1'])) {
        if (empty($data['user_id'])){
          $err['password'] = con('mandatory');
        }
      } elseif (empty($data['password2'])) {
        $err['password'] = con('pwd_second_missing');
      } elseif (empty($data['password2'])) {
        $err['password'] = con('pwd_second_missing');
      } elseif (strlen($data['password1'])<=4) {
        $err['password'] = con('pwd_to_short') ;
      } elseif ($data['password1']!=$data['password2']) {
        $err['password'] = con('pwd_not_thesame');
      }
      if (!empty($data['user_id']) and empty($data['old_password'])){
        $err['old_password']=con('mandatory');
      }
    }
  }

  function register ($status, $data, &$err, $mandatory=0, $secure=0, $short=0){

    User::validate_user($status, $data, $err, $mandatory, $secure, $short);

    if ($status == 2) {
      $query="select count(*) as count from auth where username="._esc($member['user_email']);
      if($row = ShopDB::query_one_row($query) and $row['count']>0){
        $_USER_ERROR['user_email']=con('alreadyexist');
      }
    }

    if(!empty($err)){
      return FALSE;
    }

    $set = array();
    $fields = ShopDB::FieldList('User');
    unset($data['user_id']);
    $data['user_status'] = $status;
    foreach($fields as $field) {
      if (isset($data[$field])) {
        $set[] = "$field="._esc($data[$field]);
      }
    }
    $set = implode(",\n",$set);
    $query="INSERT User SET $set";

    if ($result=ShopDB::query($query) and $user_id = shopDB::insert_id()){
      if (in_array($status, array(2))) {
        If ($short and empty($data['password1'])) {
           $data['password1'] = substr( base_convert($active,15,36),0,8);
        }
        $active = md5(uniqid(rand(), true));

        $query="insert into auth (username, password, user_id, active) VALUES (".
                _esc($data['user_email']).",".
                _esc(md5($data['password1'])).",".
                _esc($user_id).",".
                _esc($active).")";

        if(!ShopDB::query($query)){
        	return FALSE;
        }
        if (!User::SendActivatieCode($data, $active, $myerror)) {
           $err = $myerror;
        }
      }
    }
    return $user_id;
  }

	function update (&$data, &$err, $mandatory=0, $short=0){

    	if(!empty($data['user_id'])) {
    
			/////////////////////////
			///Check user password///
			/////////////////////////

    		$query="SELECT username, password, user_status 
              	FROM User left join auth on auth.user_id=User.user_id
              	WHERE User.user_id="._esc((int)$data['user_id']);
              
    		if (!$user=ShopDB::query_one_row($query)){
        		die('System error while changing user data.');
    		} elseif($user['user_status']==2) {
        		if (empty($data['old_password'])) {
          		$err['old_password'] = con('mandatory');
        		} elseif ($user['password']!==md5($data['old_password']) ) {
      	  		$err['old_password']=con("incorrect_password");
            } elseif ($user ['username']<> $data['user_email'] and !isset($err['user_email'])) {
        			$query="select count(*) as count from auth where username="._esc($member['user_email']);
	            if ($row=ShopDB::query_one_row($query) and $row['count']>0){
						     $err['user_email']=con('alreadyexist') ;
        			}
        		}
      		}

      		User::validate_user($user['user_status'], $data, $err, $mandatory, 0,  $short);
      		//print_r($err);
      		if(!empty($err)){
        		return FALSE;
      		}

      		$set = array();
      		$user_id =$data['user_id'];
      		unset($data['user_id']);
      		$fields = ShopDB::FieldList('User');
      		foreach($fields as $field) {
        		if (isset($data[$field])) {
          			$set[] = "$field="._esc($data[$field]);
        		}
      		}

			if ($set) {
        		$set = implode(",\n",$set);

        		$query="UPDATE User SET $set
                	WHERE user_id="._esc((int)$user_id);
      			
				if(!ShopDB::query($query)){
      	  			return FALSE;
      			}
      		}

    		$set = array();
   			if ($user ['username']<> $data['user_email']) {
        		$set[] = "username="._esc($data['user_email']);
      		}
      		
      		if (!empty($data['password1'])) {
        		$set[] = "password="._esc($data['password1']);
      		}
      		
      		if ($set) {
        		$set = implode(',',$set);
      			$query="UPDATE auth SET $set
                	WHERE user_id="._esc((int)$user_id);

      			if(!ShopDB::query($query)){
      	  			return FALSE;
      			}
      		}
      		
//    		$data['is_member']=$data['user_status']==2;
//    		$_SESSION['_SHOP_USER']=$data;
      		return true;
    	}else{
      		die("Missing user id. System halted.");
    	}
  	}

  function Activate($userdata, &$errors){
    if (!is_base64_encoded($userdata)) {
      $errors =  con('act_uselink');
    } else {
      $userdata2 = base64_decode($userdata);

      list($x,$z,$y) = explode('|', $userdata2, 3);

      if (!isset($x) or !isset($y)) {
        $errors =  con('act_uselink');
      } else {
        $x = (int)    $x;
        $y = (string) $y;

        if ( ($x> 0) && (strlen($y) == 32)) {
          $query = "UPDATE auth SET active=NULL WHERE (user_id="._esc($x)." AND active="._esc($y).") LIMIT 1";
          if ($result = ShopDB::query($query) and shopDB::affected_rows() == 1) {
            return true;
          } else {
        		$errors = con('act_error') ;
          }
        } else {
          $errors = con('act_uselink') ;
        }
      }
    }
  }

	function resend_activation($email, &$errors){
		global $_SHOP;

    $query="SELECT user_id, active, user.* from auth left join User as auth.user_id=User.User_id where auth.username="._esc($email);
    if (!$row=ShopDB::query_one_row($query)) {
  		$errors = con("log_err_wrong_usr");
  	} elseif ($row['active']==null) {
  		$errors = con("log_err_isactive");
	 	} else {
  		$active = md5(uniqid(rand(), true));
  		$query="UPDATE `auth` SET active='$active' WHERE user_id=".$row['user_id']." LIMIT 1";
      unset($row['active']);

  		if(ShopDB::query($query) and shopDB::affected_rows()==1){
        User::SendActivatieCode($row, $active, $error);
  		}
  	}
	}

  private function SendActivatieCode($row, $active, &$errors){
    global $_USER_ERROR;
    // new part
    $email = $data['user_email'] ;
    If (!$tpl = TemplateEngine::getTemplate('Signup_email')) {
      return false;
    }
    $email=&new htmlMimeMail();
    $activation = base64_encode("$user_id|".date('c')."|$active");
    $row['link']=$_SHOP->root."activate.php?uar=$active";

    $tpl->build($email,$row);
    if($email->send($tpl->to)){
    	return true;
    } else {
  		$errors = con("log_err_mailnotsend");
    }
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

  function check_NoSpam($secure, $data, &$err) {
    If (!empty($secure)) {
      if (empty($data[$secure])) {
        $err[$secure] = con('mandatory');
      }
      elseif ($_SESSION['_NoSpam'][$secure] <> md5(strtoupper ($data[$secure]))) {
        $err[$secure] = con('invalid');
      }
    }
  }

  function forgot_password($email){
    global $_SHOP;

    $query="SELECT * from auth left join User on auth.user_id=User.user_id where auth.username="._esc($email);
    if(!$row=ShopDB::query_one_row($query)){
      echo 'username not found';
      return FALSE;
    }

    $pwd = substr( base_convert(md5(uniqid(rand())),15,36),0,8);
    $pwd_md5=md5($pwd);

    $query="UPDATE auth SET password="._esc($pwd_md5)." WHERE user_id="._esc($row['user_id'])." limit 1";

    if(shopDB::query($query) and shopDB::affected_rows()==1){

      $engine= new TemplateEngine();
      $email = new htmlMimeMail();

      $tpl=$engine->getTemplate('forgot_passwd');
//      $row = $this->values;
      $row['new_password']=$pwd;
      $tpl->build($email, $row);
      if($email->send($tpl->to)){
        return true;
      } else {
        echo 'cant send email:';
        print_r($email->errors);
      }
    } else
        echo 'cant set new password';

  }

}

function convMandatory($mandatory_l){
	if(!empty($mandatory_l)){
		if(preg_match_all('/\w+/',$mandatory_l,$matches)){
			$mandatory=$matches[0];
		}
	}
return $mandatory;
}


function check_email_mx($email){
    if(preg_match('#.+@(?<host>.+)#',$email,$match) > 0 and getmxrr($match['host'],$mxhosts)){
        // mx records gevonden

        $valid = false;

        // mx records overlopen op zoek naar een geldige
        while($host = next($mxhosts) and !$valid){
            // een IPv4 of IPv6 adres volstaat
            $valid = checkdnsrr($host, 'A') or checkdnsrr($host,'AAAA');
        }

        return $valid;
    }

    // geen geldig mail adres wegens geen
    // correcte hostname of geen mx records
    return false;
}

?>