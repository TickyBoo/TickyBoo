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

$orphancheck = array();
/**/
$orphancheck[]="
select 'Category', category_id, 'event_id' l1 , category_event_id, event_id,
                                'pm_id'    l2 , category_pm_id, pm_id,
                                'pmp_id'   l3 , category_pmp_id, pmp_id,
                                'stat_id'  i4 , category_id, cs_category_id
from Category left join Event on category_event_id = event_id
              left join PlaceMap2 on category_pm_id = pm_id
              left join PlaceMapPart on category_pmp_id = pmp_id
              left join Category_stat on category_id = cs_category_id
where  (category_event_id <> 0 and event_id is null)
or     (pm_id is null)
or     (cs_category_id is null and category_status != 'unpub')
or     (category_pmp_id <> 0 and pmp_id is null)
";
/**/
$orphancheck[]="
select 'Category_stat', cs_category_id, 'cat_id' l1 , cs_category_id, category_id
from Category_stat left join Category on category_id = cs_category_id
where  (category_id is null )
";
/**/
$orphancheck[]="
select 'Discount', discount_id, 'event_id' l1 , discount_event_id, event_id
from Discount left join Event on discount_event_id = event_id
where  (event_id is null)
";
/**/
$orphancheck[]="
select 'Event', e.event_id,  'ort_id' l1 , e.event_ort_id, ort_id,
                             'pm_id' l2 , e.event_pm_id, pm_id,
                             'group_id' l3 , e.event_group_id,  eg.event_group_id group_id,
                             'stat_id' l5 , e.event_id,  es.es_event_id,
                             'main_id' l4 , e.event_main_id , me.event_id  main_id
from Event e left join Ort on event_ort_id = ort_id
             left join PlaceMap2 on event_pm_id = pm_id
             left join Event_group eg on e.event_group_id = eg.event_group_id
             left join Event me on e.event_main_id = me.event_id
             left join Event_stat es on e.event_id = es.es_event_id
where  (ort_id is null)
or     (pm_id is null)
or     (es.es_event_id is null and e.event_status !='unpub' and e.event_rep !='main')
or     (e.event_group_id<>0 and eg.event_group_id is null)
or     (e.event_main_id is not null and me.event_id is null)
";

$orphancheck[]="
	SELECT 'Seats Missing', event_id, 'cat_id', category_id
	FROM event e  left join Category c on category_event_id = event_id
	WHERE e.event_id > 0
		AND lower(e.event_status) <> 'unpub'
		AND lower(e.event_rep) LIKE ('%sub%')
		AND category_size <> (SELECT count(seat_id) FROM seat s WHERE s.seat_event_id = e.event_id and s.seat_category_id = category_id )
";

/**/
$orphancheck[]="
select 'Event_stat', es_event_id, 'event_id' l1 , es_event_id, event_id
from Event_stat left join Event  on es_event_id = event_id
where  (event_id is null)
";
/**/
$orphancheck[]="
select 'Order', o.order_id, 'user_id'  l1 ,o.order_user_id, u.user_id ,
                            'handling_id' l2 , o.order_handling_id, handling_id,
                            'reemited_id' l3 , o.order_reemited_id, o2.order_id,
                            'owner_id' l4 ,    o.order_owner_id, POS.user_id
from `Order` o left join User u on o.order_user_id = u.user_id
             left join Handling on o.order_handling_id = handling_id
             left join `Order` o2 on o.order_reemited_id = o2.order_id
             left join SPoint POS on o.order_owner_id = POS.user_id
where  (u.user_id is null)
or     (o.order_owner_id is not null and POS.user_id is null)
or     (handling_id is null)
or     (o.order_reemited_id is not null and o2.order_id is null)
";
/**/
$orphancheck[]="
select 'PlaceMap2', pm_id, 'ort_id'  l1 ,pm_ort_id, ort_id,
                           'event_id' l2 ,pm_event_id, event_id
from `PlaceMap2` left join Ort on pm_ort_id = ort_id
                 left join Event on pm_event_id = event_id
where  (ort_id is null)
or     (pm_event_id is not null and event_id is null)
";
/**/
$orphancheck[]="
select 'PlaceMapPart', pmp_id,'pm_id'  l1 , pmp_pm_id, pm_id,
                              'ort_id'  l2 ,pmp_ort_id, ort_id,
                              'event_id'  l3 ,pmp_event_id, event_id
from `PlaceMapPart` left join Ort on pmp_ort_id = ort_id
                 left join Event on pmp_event_id = event_id
                 left join PlaceMap2 on pmp_pm_id = pm_id
where  (pmp_ort_id is not null and pmp_ort_id <> 0 and ort_id is null)
or     (pmp_event_id is not null and pmp_event_id <> 0 and event_id is null)
or     (pm_id is null)
";
/**/
$orphancheck[]="
select 'PlaceMapZone', pmz_id, 'pm_id' l1  ,pmz_pm_id, pm_id
from `PlaceMapZone` left join PlaceMap2 on pmz_pm_id = pm_id
where  (pm_id is null)
";
/**/
$orphancheck[]="
select 'Seat', seat_id, 'event_id' l0  ,seat_event_id, event_id,
                        'cat_id'  l1 ,seat_category_id, category_id,
                        'user_id' l2 ,seat_user_id, user_id,
                        'order_id'l3 , seat_order_id, order_id,
                        'pmz_id' l4 , seat_zone_id, pmz_id,
                        'pmp_id'  l5  , seat_pmp_id,pmp_id,
                        'disc_id' l6 , seat_discount_id, discount_id
from `Seat`      left join PlaceMapZone on seat_zone_id = pmz_id
                 left join PlaceMapPart on seat_pmp_id = pmp_id

                 left join `Order` on seat_order_id = order_id
                 left join User on seat_user_id = user_id
                 left join Event on seat_event_id = event_id

                 left join Discount on seat_discount_id = discount_id
                 left join Category on seat_category_id = category_id
where  (category_id is null)
or     (event_id is null)
or     (seat_order_id is not null and seat_order_id <> 0 and order_id is null)

or     (seat_user_id is not null and seat_user_id <> 0 and user_id is null)
or     (seat_zone_id is not null and seat_zone_id <> 0 and pmz_id is null)

or     (seat_pmp_id is not null and seat_pmp_id <> 0 and pmp_id is null)
or     (seat_discount_id is not null and seat_discount_id <> 0 and discount_id is null)
";

/**/
//require_once("includes/config/init_common.php");
//include "ShopDB.php";
$data = array();
$keys = array();
foreach( $orphancheck as $query) {
  unset($result);
  $result = ShopDB::query($query);
  while ($row = ShopDB::fetch_row($result)) {
      $r = array ('_table' => $row[0], '_id' => $row[1]);

    for( $x=2;$x< count($row); $x+=3) {
      $z = ((!empty($row[$x+1]) and $row[$x+2]!==$row[$x+1])?$row[$x+1]:'');//.' - '.var_export ($row[$x+2],true);
      if ($z) {
        if (!in_array($row[$x],$keys)){
           $keys[] = $row[$x];
        }
        $r[$row[$x]] = $z;
      }
    }
    $data[] = $r;
  }
}

?>
