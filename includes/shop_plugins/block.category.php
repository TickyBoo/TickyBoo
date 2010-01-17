<?php
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2010 Christopher Jenkins, Niels, Lou. All rights reserved.
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

function smarty_block_category ($params, $content, &$smarty,&$repeat) {

  if ($repeat) {

    if($params['placemap']){
      $from = 'from Category LEFT JOIN PlaceMap2 ON category_pm_id=pm_id';
    }else{
      $from = 'from Category';
    }
    $where = 'where 1=1';

    if($params['category_id']){
      $where .= " and category_id="._esc($params['category_id']);
    }

     if($params['order']){
      $order_by="order by "._esc($params['order'],false);
    }


    if($params['event_id']){
      $where .= " and category_event_id="._esc($params['event_id']);
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

    $cat=shopDB::fetch_assoc($res);

  }else{
    $res=array_pop($smarty->_SHOP_db_res);
    $cat=shopDB::fetch_assoc($res);
  }

  $repeat=!empty($cat);

  if($cat){
    $smarty->assign("shop_category",$cat);

    $smarty->_SHOP_db_res[]=$res;
  }

  return $content;
}


?>