<?php
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


require_once('update/update.php');

function smarty_block_handling ($params, $content, &$smarty, &$repeat) {

	global $_SHOP;
	
  if ($repeat) {
    $from='FROM Handling';
    $where="WHERE handling_organizer_id='{$_SHOP->organizer_id}' ";
    
	if($params['event_date']){
		$update= new Update();
   		$use_alt=$update->check_event($params['event_date']);
	}
	if(!$params['handling_id']){  	
	   	if(!$use_alt){
	   		$where .= " AND handling_alt_only='No'";
		}
		if($use_alt){
			$where .= " AND handling_alt <= 3";
		}
	}
	
    if($params['order']){
      $order_by="order by {$params['order']}";
    } 
    
    if($params['handling_id']){
     $where .= " and handling_id='{$params['handling_id']}'";
    }

    if($params['sp']){
     $where .= " and handling_sale_mode LIKE '%sp%'";
    }

    if($params['www']){
     $where .= " and handling_sale_mode LIKE '%www%'";
    }
    
    if($params['limit']){
      $limit='limit '.$params['limit'];
    }

  
    $query="select * $from $where $order_by $limit";
    
    $res=ShopDB::query($query);
    
    $pay=shopDB::fetch_array($res);
    
  }else{
    $res=array_pop($smarty->_SHOP_db_res);
    $pay=shopDB::fetch_array($res);
  }

	
  $repeat=!empty($pay);

// Loads the payment file from class's which defines the extra parmiters when someone pays or goes to pay.
  if($pay){
	  	
		// if handling_extra exsists unserialize it...
	  	if($pay['handling_extra']){
			  $pay['extra']=unserialize($pay['handling_extra']);
		  }
	
			if($params['pm_exec']){
			  $pay['pm_return']=_exec_extra_method('pm_','payment',$params['pm_exec'], $pay, $smarty);
			}
	
			if($params['sm_exec']){
			  $pay['sm_return']=_exec_extra_method('sm_','shipment',$params['sm_exec'], $pay, $smarty);
			}
	
	    $smarty->assign("shop_handling",$pay);  
			
	    $smarty->_SHOP_db_res[]=$res;
	  }
	  
	  
  
  return $content;
}

function _exec_extra_method($prefix, $field, $name,&$pay, &$smarty){
// e.g. pm_paypal.php
  $e_class=$prefix.$pay['handling_'.$field];
  $e_file="classes/$e_class.php";
  //creates file class which will then run upon payment.
  if(_dyn_load($e_file)){
		$e=new $e_class;
		if(method_exists($e,$name)){
  		return $e->$name($pay,$smarty);
		}
	}
}


		function _myErrorHandler($errno, $errstr, $errfile, $errline) {
			if($errno!=2){
				echo "$errno $errstr $errfil $errline";
			}	
		}
	//Loads file
	function _dyn_load($name){
	
		set_error_handler('_myErrorHandler');
		$res=include_once($name);
		restore_error_handler();
	
		return $res;
	}	
?>