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

if (!defined('ft_check')) {die('System intrusion ');}
$orphancheck = array();
/**/

$orphancheck[]="
	SELECT 'Event', event_id, 'cat_id', category_id, null,
                            category_numbering,  category_size -(SELECT count(seat_id)
                                                                 FROM Seat s
                                                                 WHERE s.seat_event_id = e.event_id
                                                                 and s.seat_category_id = category_id ) , null
	FROM Event e  left join Category c on category_event_id = event_id
	WHERE e.event_id > 0
		AND lower(e.event_status) != 'unpub'
		AND lower(e.event_rep) LIKE ('%sub%')
		AND category_size != (SELECT count(seat_id)
                          FROM Seat s
                          WHERE s.seat_event_id = e.event_id
                          and s.seat_category_id = category_id )
";
/*******************************************************************/
$orphancheck[]="
select 'Category', category_id, 'event_id' l1 , category_event_id, event_id
from Category left join Event         on category_event_id = event_id
where  (category_event_id is not null and event_id is null)
";
$orphancheck[]="
select 'Category', category_id, 'pm_id'    l2 , category_pm_id, pm_id
from Category left join PlaceMap2     on category_pm_id    = pm_id
where  (pm_id is null)
";
$orphancheck[]="
select 'Category', category_id, 'pmp_id'   l3 , category_pmp_id, pmp_id
from Category left join PlaceMapPart  on category_pmp_id   = pmp_id
where  (category_pmp_id is not null and pmp_id is null)
";
$orphancheck[]="
select 'Category', category_id, 'stat_id'  i4 , category_id, cs_category_id
from Category left join Category_stat on category_id       = cs_category_id
              left join Event    on category_event_id = event_id
where  (cs_category_id is null and event_status is not null and event_status != 'unpub')
";
$orphancheck[]="
select 'Category', category_id, 'shadow'  , category_event_id, null
from Category left join Event    on category_event_id = event_id
where  (category_pm_id <> event_pm_id)
";
/**/
$orphancheck[]="
select 'Category_stat', cs_category_id, 'cat_id' l1 , cs_category_id, category_id
from Category_stat left join Category on category_id = cs_category_id
                   left join Event    on category_event_id = event_id
where  (category_id is null and event_status != 'unpub')
";
/**/
$orphancheck[]="
select 'Discount', discount_id, 'event_id' l1 , discount_event_id, event_id
from Discount left join Event on discount_event_id = event_id
where  (event_id is null)
";
/**/
$orphancheck[]="
select 'Event', e.event_id,  'ort_id'   , e.event_ort_id,    ort_id
from Event e left join Ort            on e.event_ort_id = ort_id
where  (ort_id is null and e.event_ort_id is not null)
";
$orphancheck[]="
select 'Event', e.event_id,  'pm_id'    , e.event_pm_id,     pm_id
from Event e left join PlaceMap2      on e.event_pm_id = pm_id
where  (pm_id is null  and e.event_pm_id is not null)
";
$orphancheck[]="
select 'Event', e.event_id,  'group_id' , e.event_group_id,  eg.event_group_id group_id
from Event e left join Event_group eg on e.event_group_id = eg.event_group_id
where  (e.event_group_id is not null and eg.event_group_id is null)
";
$orphancheck[]="
select 'Event', e.event_id,  'stat_id'  , e.event_id,        es.es_event_id
from Event e left join Event_stat es  on e.event_id = es.es_event_id
where  (es.es_event_id is null and e.event_status !='unpub' and e.event_rep !='main')
";
$orphancheck[]="
select 'Event', e.event_id,  'main_id'  , e.event_main_id,  me.event_id  main_id
from Event e left join Event me       on e.event_main_id = me.event_id
where  (e.event_main_id is not null and me.event_id is null)
";
/**/

$orphancheck[]="
select 'Event_stat', es_event_id, 'event_id' l1 , es_event_id, event_id
from Event_stat left join Event  on es_event_id = event_id
where  (event_id is null)
";
$orphancheck[]="
select 'Spoint', SPoint.admin_id, 'user_id' , SPoint.admin_user_id, User.user_id
from Admin SPoint left join User  on SPoint.admin_user_id = User.user_id
where  (User.user_id is null)
and    admin_status = 'pos'
";

/**/
$orphancheck[]="
select 'Order', o.order_id, 'user_id'  l1 ,o.order_user_id, u.user_id
from `Order` o left join User u on o.order_user_id = u.user_id
where  (u.user_id is null)
";
$orphancheck[]="
select 'Order', o.order_id, 'handling_id' l2 , o.order_handling_id, handling_id
from `Order` o left join Handling on o.order_handling_id = handling_id
where  (handling_id is null)
";
$orphancheck[]="
select 'Order', o.order_id, 'reemited_id' l3 , o.order_reemited_id, o2.order_id
from `Order` o left join `Order` o2 on o.order_reemited_id = o2.order_id
where  (o.order_reemited_id is not null and o2.order_id is null)
";
/*
$orphancheck[]="
select 'Order', o.order_id, 'owner_id' l4 ,    o.order_owner_id, POS.user_id
from `Order` o left join admin POS on o.order_owner_id = POS.admin_user_id
where  (o.order_owner_id is not null and POS.user_id is null)
";
/**/
$orphancheck[]="
select 'PlaceMap', pm_id, 'ort_id'  l1 ,pm_ort_id, ort_id
from `PlaceMap2` left join Ort on pm_ort_id = ort_id
where  (ort_id is null)
";
$orphancheck[]="
select 'PlaceMap', pm_id, 'event_id' l2 ,pm_event_id, event_id
from `PlaceMap2` left join Event on pm_event_id = event_id
where (pm_event_id is not null and event_id is null)
";
$orphancheck[]="
select 'PlaceMap', pm_id, 'shadow' l2 ,pm_event_id, null
from `PlaceMap2` left join Event on pm_event_id = event_id
where (pm_event_id is not null and event_pm_id != pm_id)
";
/**/
$orphancheck[]="
select 'PlaceMapPart', pmp_id,'pm_id'  l1 , pmp_pm_id, pm_id
from `PlaceMapPart` left join PlaceMap2 on pmp_pm_id = pm_id
where (pm_id is null)
";
$orphancheck[]="
select 'PlaceMapPart', 'ort_id'  l2 ,pmp_ort_id, ort_id
from `PlaceMapPart` left join Ort on pmp_ort_id = ort_id
where  (pmp_ort_id is not null  and ort_id is null)
";
$orphancheck[]="
select 'PlaceMapPart', pmp_id, 'event_id'  l3 ,pmp_event_id, event_id
from `PlaceMapPart` left join Event on pmp_event_id = event_id
where  (pmp_event_id is not null and event_id is null)
";
/**/
$orphancheck[]="
select 'PlaceMapZone', pmz_id, 'pm_id' l1  ,pmz_pm_id, pm_id
from `PlaceMapZone` left join PlaceMap2 on pmz_pm_id = pm_id
where  (pm_id is null)
";
/**/
$orphancheck[]="
select 'Seat', seat_id, 'event_id' l0  ,seat_event_id, event_id
from `Seat`      left join Event on seat_event_id = event_id
where  (event_id is null)
";
$orphancheck[]="
select 'Seat', seat_id, 'cat_id'  l1 ,seat_category_id, category_id
from `Seat`      left join Category on seat_category_id = category_id
where  (category_id is null)
";
$orphancheck[]="
select 'Seat', seat_id, 'user_id' l2 ,seat_user_id, user_id
from `Seat`      left join User on seat_user_id = user_id
where  (seat_user_id is not null and  user_id is null)
";
$orphancheck[]="
select 'Seat', seat_id, 'order_id'l3 , seat_order_id, order_id
from `Seat`      left join `Order` on seat_order_id = order_id
where  (seat_order_id is not null and order_id is null)
";
$orphancheck[]="
select 'Seat', seat_id, 'pmz_id' l4 , seat_zone_id, pmz_id
from `Seat`      left join PlaceMapZone on seat_zone_id = pmz_id
where  (seat_zone_id is not null and  pmz_id is null)
";
$orphancheck[]="
select 'Seat', seat_id, 'pmp_id'  l5  , seat_pmp_id,pmp_id
from `Seat`      left join PlaceMapPart on seat_pmp_id = pmp_id
where  (seat_pmp_id is not null and pmp_id is null)
";
$orphancheck[]="
select 'Seat', seat_id, 'disc_id' l6 , seat_discount_id, discount_id
from `Seat`      left join Discount on seat_discount_id = discount_id
where  (seat_discount_id is not null and discount_id is null)
";

/**/
class orphans {
  static $fixes = array(
       'Category~stat_id'=>'Recreate this missing stat record',
       'Category~event_id'=>'Remove this category, event is already removed',
       'Category~pm_id'=>'Remove this category, Placemap is already removed',
       'Category~pmp_id'=>'Clear the link to the removed placemapPart',
       'Category~zeros'=>'Clear all zero identifiers in the Catagory table',
       'Category_stat~cat_id'=>'Remove ALL old category_stat records',
       'Discount~event_id'=>'Remove this Discount, event is already removed',
       'Event~stat_id'=>'Recreate this missing stat record',
       'Event~group_id'=>'Clear the link to this removed eventgroup',
       'Event_stat~event_id'=>'Remove ALL old Event_stat records',
       'Event~zeros'=>'Clear all zero identifiers in the event table',
       'Event~cat_id'=>'Recreate missing seats for this category',
       'Event~pm_id'=>'Clear the link to the removed placemap',
       'Order~user_id'=>'Recreate missing user info for this order',
       'Order~owner_id'=>'Recreate missing POS login for this pos',
       'Order~zeros'=>'Clear all zero identifiers in the order table',
       'PlaceMapPart~zeros'=>'Clear all zero identifiers in the PlaceMapPart table',
       'PlaceMap~event_id'=>'Remove this placemap, event is already removed',
       'PlaceMap~zeros'=>'Clear all zero identifiers in the PlaceMap table',
       'Seat~zeros'=>'Clear all zero identifiers in the seat table',
       'Seat~order_id'=>'Release the order lock from this seats',
       'Seat~event_id'=>'Remove the seats with the already deleted event',
       'Seat~user_id'=>'Recreate missing user info for this seats',
       'Seat~cat_id'=>'Remove the seats with the deleted category',
       'Spoint~user_id'=>'Recreate missing user info for this pos'


  );

  function getlist(& $keys, $showlinks= true) {
    global $orphancheck, $_SHOP;
    $data = array();
    $keys = array();
    $trace = $_SHOP->trace_on;
    $_SHOP->trace_on=false;

    foreach( $orphancheck as $query) {
      unset($result);
      $result = ShopDB::query($query);
      while ($row = ShopDB::fetch_row($result)) {

        if (!isset($data["{$row[0]}{$row[1]}"])){
          $r = array ('_table' => $row[0], '_id' => $row[1] );
        } else {
          $r = $data["{$row[0]}{$row[1]}"];
        }

        for( $x=2;$x< count($row); $x+=3) {
          $z = var_export ((!is_null($row[$x+1]) and $row[$x+2]===$row[$x+1])?'':$row[$x+1], true);
          if ($z !='NULL' and $z !="''" )  {
            if (!in_array($row[$x],$keys)){
               $keys[] = $row[$x];
            }
            if ($z == "'0'") {
              $thisfix =  Orphans::$fixes["{$row[0]}~zeros"];
              $fixit = "{$row[0]}~zeros";
            } elseif(isset(Orphans::$fixes["{$row[0]}~{$row[$x]}"])) {
              $thisfix =  Orphans::$fixes["{$row[0]}~{$row[$x]}"];
              $fixit = "{$row[0]}~{$row[$x]}";
            } else {
              $thisfix = '';
              $fixit = '';
            }
            $z = substr($z, 1,-1);
            if (!empty($thisfix) and $showlinks) {
              $z = "<a title='{$thisfix}'
                       href='{$_SERVER['PHP_SELF']}?fix={$fixit}~{$row[1]}~{$row[$x]}~{$row[$x+1]}'>".$z."</a>\n";
            }
            $r[$row[$x]] = $z;
          }
        }
        $data[$row[0].$row[1]] = $r;
      }
    }
    $_SHOP->trace_on= $trace;

    return $data;
  }

  function dofix($key) {
    $fix = explode('~',$key);
    $fixit = $fix[0].'~'.$fix[1];
    //print_r( debug_backtrace());
    switch ($fixit) {
      //Fix category issues
      case 'Category_stat~cat_id':
        ShopDB::Query("delete from Category_stat
                       where (select category_id from Category where category_id = cs_category_id) is null") ;
        break;
      case 'Category~stat_id':
        $cat = PlaceMapCategory::load($fix[2]);
        $sql = "SELECT count(seat_id) FROM Seat s WHERE s.seat_category_id = {$cat->category_id} and seat_status = 'free'";
        $result = ShopDB::Query_one_row($sql, false);
        PlaceMapCategory::create_stat($cat->category_id,$cat->category_size, $result[0]);
        break;
      case 'Category~event_id':
        PlaceMapCategory::delete($fix[2]);
        break;
      case 'Category~pm_id':
        PlaceMapCategory::delete($fix[2]) ;
        break;
      case 'Category~pmp_id':
        ShopDB::Query("update Category set
                         category_pmp_id = null
                       where Category_id = {$fix[2]}") ;
        break;
      case 'Category~zeros':
        Orphans::clear_zeros('Category', array('category_pm_id','category_event_id','category_pmp_id'));
        break;
      // fix event issues
      case 'Discount~event_id':
        ShopDB::Query("delete from Discount
                       where discount_event_id = '{$fix[4]}'") ;
        break;
      case'Event_stat~event_id':
        ShopDB::Query("delete from Event_stat
                       where (select Event_id from Event where event_id = es_event_id) is null") ;
        break;
      case'Event~cat_id':
        $sql = "SELECT seat_id, seat_category_id FROM Seat WHERE seat_event_id = {$fix[2]}";
        $result = ShopDB::Query($sql);
        $seats  = array();
        while ($row = ShopDB::fetch_row($result)) {
          $seats[$row[1]][] = $row[0];
        }

        $sql = "SELECT event_pm_id FROM Event e WHERE e.event_id = "._esc($fix[2]);
        $result = ShopDB::Query_one_row($sql, false);
        if (!$result) {
          echo "cant find selected order placmap";
          exit;
        }
        $pm_id = $result[0];
        $all = PlaceMapPart::loadAllFull( $pm_id);

        echo "<pre>";
         PRint_r($seats);
        if ($all) {
          foreach($all as $pmp) {
           // print_r($pmp->categories);
            $changed = false;
            foreach($pmp->pmp_data as $x =>&$pmp_row) {
              foreach ($pmp_row as $y=>&$seat) {
                $zone = $pmp->zones[$seat[PM_ZONE]];
                $category = $pmp->categories[$seat[PM_CATEGORY]];
                if ($seat[PM_ZONE] > 0 && $seat[PM_CATEGORY] &&
                    $category->category_numbering != 'none'){

                  if (!in_array($seat[PM_ID], $seats[$category->category_id])){

                    if ($seat_id = Seat::publish($fix[2], $seat[PM_ROW], $seat[PM_SEAT],
                                                 $zone->pmz_id, $pmp->pmp_id, $category->category_id)) {
                      echo $x,' ',$y,' ',$pmp->pmp_data[$x][$y][PM_ID] = $seat_id,'|';
                      $changed = True;
                    }
                  }
                }
              }
            }
            if ($changed) {
              $pmp->save();
              echo "\n------------------------------------------------------------\n";
            }
          }
        }
        $cats=PlaceMapCategory::loadAll($pm_id);
        if(!$cats){
          return $this->_abort('No Categories found');
        }
        foreach($cats as $cat_ident=>$cat){
          if($cat->event_status !== 'unpub' && $cat->category_numbering =='none' &&
             count($seats[$cat->category_id]) <> $cat->category_size ){//and $cat->category_size>0
            $stats[$cat->category_ident] = count($seats[$cat->category_id]);
            print_r(count($seats[$cat->category_id]));
            print_r($cat);
            for($i=count($seats[$cat->category_id]);$i<$cat->category_size;$i++){
              if($seat_id = Seat::publish($fix[2],null,0,null,null,$cat->category_id)) {
                echo $seat_id,'|';
              }
              $stats[$cat->category_ident]++;
            }
            if ($cat->category_size <> $stats[$cat->category_ident]) {
              $cat->category_size = $stats[$cat->category_ident];
              $cat->save();
            }
          }
        }
/*
        if($stats){
          foreach($stats as $category_ident=>$cs_total){
            $cat=$cats[$category_ident];
            $cs=new Category_stat($cat->category_id,$cs_total);
            if(!$dry_run){$cs->save() or $this->_abort('publish5');}
          }
        }
*/

        echo "</pre>";
        break;


      case 'Event~group_id':
        ShopDB::Query("update Event set
                         event_group_id = null
                       where Event_id = {$fix[2]}") ;
        break;

      case 'Event~pm_id':
        ShopDB::Query("update Event set
                         event_pm_id = null
                       where Event_id = {$fix[2]}") ;
        break;
     case 'Event~stat_id':
        $sql = "SELECT count(seat_id) FROM Seat s WHERE s.seat_event_id = {$fix[2]}";
        $resulty = ShopDB::Query_one_row($sql, false);
        $sql = "SELECT count(seat_id) FROM Seat s WHERE s.seat_event_id = {$fix[2]} and seat_status = 'free'";
        $resultx = ShopDB::Query_one_row($sql, false);
        Event::create_stat($fix[2], $resulty[0],$resultx[0]);
        break;
      case 'Event~zeros':
        If ($fix[3] =='ort_id') {
          echo "<script> window.alert('Ord_id can not be cleared you need to change this from within database editor like phpmyadmin. Ask your system manager to help');</script>";
        } else {
          Orphans::clear_zeros('Event', array('event_group_id','event_main_id'));
        }
        break;
      case 'Order~zeros':
        Orphans::clear_zeros('Order', array('order_owner_id'));
        break;

      case 'PlaceMap~event_id':
        PlaceMap::delete($fix[2]);
        break;

      case 'PlaceMapPart~zeros':
        Orphans::clear_zeros('PlaceMapPart', array('pmp_pm_id','pmp_ort_id','pmp_event_id'));
        break;

      case 'Seat~event_id':
        ShopDB::Query("delete from Seat where seat_event_id = {$fix[4]}") ;
        break;

      case 'Seat~cat_id':
        ShopDB::Query("delete from Seat where seat_category_id = {$fix[4]}") ;
        break;

      case 'Seat~order_id':
        ShopDB::Query("update Seat set
                         seat_order_id = null,
                         seat_user_id = null,
                         seat_ts = null,
                         seat_sid = null,
                         seat_price = null,
                         seat_discount_id = null,
                         seat_code = null,
                         seat_sales_id = null,
                         seat_status = 'free'
                       where seat_order_id = {$fix[4]}") ;
        break;

      case 'Seat~zeros':
        Orphans::clear_zeros('Seat', array('seat_category_id','seat_zone_id' ,'seat_user_id' ,
                                           'seat_order_id'   ,'seat_pmp_id'  ,'seat_discount_id'));
        break;
      case 'Order~owner_id':
        ShopDB::Query("
                      INSERT INTO `Admin` (`admin_user_id`, `login`, `password`, `admin_status`) VALUES
                                           ({$fix[4]}, 'pos~demo`{$fix[4]}', 'c514c91e4ed341f263e458d44b3bb0a7', 'pos')") ;
        break;
      case 'Order~user_id':
      case 'Seat~user_id':
      case 'Spoint~user_id':
        ShopDB::Query("
                      INSERT IGNORE INTO `User` (`user_id`, `user_lastname`, `user_firstname`, `user_address`, `user_address1`,
                                                 `user_zip`, `user_city`, `user_state`, `user_country`, `user_phone`, `user_fax`,
                                                 `user_email`, `user_status`, `user_prefs`, `user_custom1`, `user_custom2`,
                                                 `user_custom3`, `user_custom4`, `user_owner_id`, `user_lastlogin`, `user_order_total`,
                                                 `user_current_tickets`, `user_total_tickets`) VALUES
                      ({$fix[4]}, 'Demo POS', '', '4321 Demo Street', '', '10000', 'Demo Town', 'DT', 'US', '(555) 555-1212', '(555) 555-1213',
                      'demo@fusionticket.test', 1, 'pdf', '', NULL, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, 0, 0)") ;
        break;


    }
  }
  function clear_zeros($table, $fields){
    $sql = "Update `$table` set ";
    $sets ='';
    foreach ($fields as $field) {
      $sets .= ", `$field` = NULLIF(`$field`,0)";
    }
  //  echo $sql.substr($sets,2) ;
    ShopDB::Query($sql.substr($sets,2));
  }

}
?>