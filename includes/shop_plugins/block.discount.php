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
require_once "classes/Discount.php";

function smarty_block_discount ($params, $content, &$smarty, &$repeat)
{
    if ($repeat) {
        $from = 'from Discount';
        $where = "where 1";

        if ($params['order']) {
            $order_by = "order by {$params['order']}";
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

        $discount = shopDB::fetch_array($res);
    } else {
        $res = array_pop($smarty->_SHOP_db_res);
        $discount = shopDB::fetch_array($res);
    }
    if ($params['all']) {
        if (!empty($discount)) {
            $c = 1;
            $discounts[] = $discount;
            while ($discount = shopDB::fetch_array($res)) {
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
        }

        $repeat = false;
        return $content;
    } else {
        $repeat = !empty($discount);

        if ($discount) {
             If ($params['cat_price']) {
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