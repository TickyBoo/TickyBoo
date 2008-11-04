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

function smarty_block_event_group ($params, $content, &$smarty,&$repeat) {
	global $_SHOP;
	
	
  if ($repeat) {
    $from='from Event_group';
    $where="where 1";
  
    if($params['order']){
      $order_by="order by {$params['order']}";
    } 
    
    if($params['group_id']){
     $where .= " and event_group_id='{$params['group_id']}'";
    }

    $where .= " and event_group_organizer_id='{$_SHOP->organizer_id}'";   

    if($params['limit']){
      $limit='limit '.$params['limit'];
    }
    
    if($params['group_status']){
     $where .= " and event_group_status='{$params['group_status']}'";
    }
    if($params['organizer_ids']){
      $where.=" and FIELD(event_group_organizer_id,{$params['organizer_ids']})>0";      
    }
    if($params['first']){
      $limit='limit '.$params['first'];
      if($params['length']){
        $limit.=','.$params['length'];
      }
    }else if($params['length']){
      $limit='limit 0,'.$params['length'];
    }
  
    $query="select * $from $where $order_by $limit";
    $res=ShopDB::query($query);
    
    $event=shopDB::fetch_array($res);
    
  }else{
    $res=array_pop($smarty->_SHOP_db_res);
    $event=shopDB::fetch_array($res);
  }

  $repeat=!empty($event);

  if($event){
    $smarty->assign("shop_event_group",$event);  
    
    $smarty->_SHOP_db_res[]=$res; 
  }
  
  return $content;
}


?>