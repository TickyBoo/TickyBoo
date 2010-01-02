<?php
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

function smarty_block_handling ($params, $content, &$smarty, &$repeat) {

	global $_SHOP;

  if ($repeat) {
    $from='FROM Handling';
    $where="WHERE 1=1 ";

  	if($params['event_date']){
  		$use_alt=check_event($params['event_date']);
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
     $where .= " and handling_id="._esc((int)$params['handling_id']);
    }

    if($params['sp']){
     $where .= " and handling_sale_mode LIKE '%sp%'";
    }

    if($params['www']){
     $where .= " and handling_sale_mode LIKE '%www%'";
    }

    // We use the reserve button in the shop.
    if($_SHOP->shopconfig_restime > 0 && !$params['www']){
      $where .= " OR handling_id = 1";
    }

    $limit= ($params['limit'])?'limit '.$params['limit']:'';

    $query="select * $from $where $order_by $limit";

    $res=ShopDB::query($query);

    $pay=shopDB::fetch_assoc($res);

  }else{
    $res=array_pop($smarty->_SHOP_db_res);
    $pay=shopDB::fetch_assoc($res);
  }


  $repeat=!empty($pay);

// Loads the payment file from class's which defines the extra parmiters when someone pays or goes to pay.
  if($pay){

		// if handling_extra exsists unserialize it...
	  	if($pay['handling_extra']){
			  $pay['extra'] = unserialize($pay['handling_extra']);

		  }

	    $smarty->assign("shop_handling",$pay);

	    $smarty->_SHOP_db_res[]=$res;
	  }



  return $content;
}

?>