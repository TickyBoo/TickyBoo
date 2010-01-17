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

function smarty_block_discount ($params, $content, &$smarty, &$repeat)
{
    if ($repeat) {
        $from = 'from Discount';
        $where = "where 1=1";

        if ($params['order']) {
            $order_by = "order by {$params['order']}";
        }

        if($params['category_id']){
        	$from .=",Event,Category ";
        	$where .= " AND discount_event_id=event_id ";
        	$where .= " AND event_id=category_event_id ";
        	$where .= " AND category_id="._esc($params['category_id']);
        }

        if ($params['event_id']) {
            $where .= " and discount_event_id=" . ShopDB::quote($params['event_id']);
        }

        if ($params['discount_id']) {
            $where .= " and discount_id=" . ShopDB::quote($params['discount_id']);
        }

        if ($params['discount_name']) {
            $d_names = explode(",", $params['discount_name']);
            $first = 0;
            foreach($d_names as $name) {
                if (!$first) {
                    $where .= " and ( discount_name=" . ShopDB::quote($name);
                    $first = 1;
                } else {
                    $where .= "  or  discount_name=" . ShopDB::quote($name);
                }
            }
            $where .= " ) ";
        }

        $query = "select * $from $where $order_by $limit";

        $res = ShopDB::query($query);

        $discount = shopDB::fetch_assoc($res);
    } else {
        $res = array_pop($smarty->_SHOP_db_res);
        $discount = shopDB::fetch_assoc($res);
    }
    if ($params['all']) {
        if (!empty($discount)) {
            $c = 1;
            $discounts[] = $discount;
            while ($discount = shopDB::fetch_assoc($res)) {
                If ($params['cat_price']) {
                    if($discount['discount_type']=='fixe'){
                      $discount['discount_price'] = $params['cat_price']-$discount['discount_value'];
                    }else if($discount['discount_type']=='percent'){
                      $discount['discount_price'] = $params['cat_price']*(1.0-$discount['discount_value']/100.0);
                    }else{
                      $discount['discount_price'] =  FALSE;
                    }
                }
                            $discounts[] = $discount;
                $c++;
            }

            $smarty->assign("shop_discounts", $discounts);
            $smarty->assign("shop_discounts_count", $c);
       			$smarty->assign("shop_discounts_times", $_REQUEST['qty']);

        }

        $repeat = false;
        return $content;
    } else {
        $repeat = !empty($discount);

        if ($discount) {
      		if($params['category_id']){
      			$params['cat_price'] = $discount['category_price'];
      		}

             if ($params['cat_price']) {
                  if($discount['discount_type']=='fixe'){
                    $discount['discount_price'] = $params['cat_price']-$discount['discount_value'];
                  }else if($discount['discount_type']=='percent'){
                    $discount['discount_price'] =  $params['cat_price']*(1.0-$discount['discount_value']/100.0);
                  }else{
                    $discount['discount_price'] =  FALSE;
                  }
              }

            $smarty->assign("shop_discount", $discount);
            $smarty->_SHOP_db_res[] = $res;
        }
    }
    return $content;
}

?>