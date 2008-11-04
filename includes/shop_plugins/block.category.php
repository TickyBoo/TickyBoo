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


function smarty_block_category ($params, $content, &$smarty,&$repeat) {

  if ($repeat) {
    
    if($params['placemap']){
      $from = 'from Category LEFT JOIN PlaceMap2 ON category_pm_id=pm_id';
    }else{
      $from = 'from Category';
    }
    $where = 'where 1';

    if($params['category_id']){
      $where .= " and category_id='{$params['category_id']}'";
    }
 
     if($params['order']){
      $order_by="order by {$params['order']}";
    } 
    
    
    if($params['event_id']){
      $where .= " and category_event_id='{$params['event_id']}'";
    }
  
    if($params['stats']){
      $from.=',Category_stat';
      $where.=' and category_id=cs_category_id';
    }
  

    if($params['event']){
      $from.=',Event,Ort';
      $where.=' and event_id=category_event_id and event_ort_id=ort_id';
    }

    $query="select * $from $where $order_by";
    $res=ShopDB::query($query);
    
    $cat=shopDB::fetch_array($res);
    
  }else{
    $res=array_pop($smarty->_SHOP_db_res);
    $cat=shopDB::fetch_array($res);
  }

  $repeat=!empty($cat);

  if($cat){
    $smarty->assign("shop_category",$cat);  
    
    $smarty->_SHOP_db_res[]=$res; 
  }
  
  return $content;
}


?>