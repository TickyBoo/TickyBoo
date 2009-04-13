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

function smarty_block_event ($params, $content, &$smarty,&$repeat) {
	global $_SHOP;

  if ($repeat) {
    $from='Event';
    $where="where event_status='pub'";		
		
    if($params['order']){
			$params['order']=shopDB::escape_string($params['order']);
      $order_by="order by {$params['order']}";
    }
    
    if($params['event_id']){
			$params['event_id']=shopDB::escape_string($params['event_id']);
			$where .= " and event_id='{$params['event_id']}'";
    }

    if($params['ort']){
      $from.=' LEFT JOIN Ort ON ort_id=event_ort_id';
      //$where .= " and ort_id=event_ort_id";      
    }

    if($params['place_map']){
      $from.=' LEFT JOIN PlaceMap2 ON pm_event_id=event_id';
    }
  
    if($params['stats']){
      $from.=',Event_stat';
      $where .= " and event_id=es_event_id";
    }

    if($params['cats']){
      $from.=',Category,Category_stat';
      $where .= " AND event_id=category_event_id AND cs_category_id=category_id ";
    }

    if($params['event_group']){
			$params['event_group']=shopDB::escape_string($params['event_group']);
      $from.=',Event_group';
      $where .= " and Event_group.event_group_id='{$params['event_group']}' and Event.event_group_id=Event_group.event_group_id";
    }

    if($params['join_event_group']){
      $from.=' LEFT JOIN Event_group ON Event.event_group_id=Event_group.event_group_id';
    }

    if($params['start_date']){
			$params['start_date']=shopDB::escape_string($params['start_date']);
      $where .= " and event_date>='{$params['start_date']}'";
    }
    if($params['end_date']){
			$params['end_date']=shopDB::escape_string($params['end_date']);
      $where .= " and event_date<='{$params['end_date']}'";
    }

    if($params['limit']){
			$params['limit']=shopDB::escape_string($params['limit']);
      $limit='limit '.$params['limit'];
    }

    if($params['event_type']){
      $types=explode(",",$params['event_type']);
      $first=true;
      foreach($types as $type){
				$type=shopDB::escape_string($type);
				if($first){
        $par.="'$type'";
	      $first=false;
       }else{
        $par.=",'$type'";
       }
      }
      $where.=" and FIELD(event_type,$par)>0";
    }

    if($params['sub']){
      $where.=" and event_rep LIKE '%sub%'";

      if($params['event_main_id']){
				$params['event_main_id']=shopDB::escape_string($params['event_main_id']);
        $where.=" and event_main_id='{$params['event_main_id']}'";
      }
    }

    if($params['main']){
      $where.=" and event_rep LIKE '%main%'";
    }

    if($params['load_organizer']){
      $from.=',Organizer';
    }

    if($params['first']){
			$params['first']=(int)$params['first'];
      $limit='limit '.$params['first'];
      if($params['length']){
				$params['length']=(int)$params['length'];
        $limit.=','.$params['length'];
      }
    }else if($params['length']){
			$params['length']=(int)$params['length'];
      $limit='limit 0,'.$params['length'];
    }
  
		if($limit){
		  $cfr='SQL_CALC_FOUND_ROWS';
		}
		
    $query="select $cfr * from $from $where $order_by $limit";
    $res=ShopDB::query($query);

	  $part_count=shopDB::num_rows($res);

		if($cfr){
		  $query='SELECT FOUND_ROWS();';
      if($row=ShopDB::query_one_row($query)){
			  $tot_count=$row[0];
			}
		}else{
		  $tot_count=$part_count;
		}

    $event=shopDB::fetch_array($res);

  }else{
    $res_a=array_pop($smarty->_SHOP_db_res);

		$res=$res_a[0];
		$tot_count=$res_a[1];
		$part_count=$res_a[2];
		
    $event=shopDB::fetch_array($res);
  }

  $repeat=!empty($event);

  if($event){

		$event['tot_count']=$tot_count;
    $event['part_count']=$part_count;

    $smarty->assign("shop_event",$event);

    $smarty->_SHOP_db_res[]=array($res,$tot_count,$part_count);
  }

  return $content;
}

?>