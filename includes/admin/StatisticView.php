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

require_once("admin/AdminView.php");
require_once("classes/ShopDB.php");
require_once("functions/datetime_func.php");

class StatisticView extends AdminView{
    var $img_pub;
    
    function fill_images()
    {
        $this->img_pub['pub']   = 'images/grun.png';
        $this->img_pub['unpub'] = 'images/rot.png';
        $this->img_pub['nosal'] = 'images/grey.png';
    }

  function plotEventStats ($start_date, $end_date, $month, $year)
  {
    global $_SHOP;
    $query = "select MAX(es_total) from Event_stat";
    if (!$res = ShopDB::query_one_row($query)){
      user_error(shopDB::error());
      return;
    }
    $max_places = $res[0];
    if (!($max_places > 0)){
      return;
    }
    $query = "select Event_stat.*, event_id, event_name, event_date, event_time, event_status from Event_stat, Event
              where  es_event_id=event_id
               and event_status!='unpub'
  	           and event_date >="._esc($start_date)."
        	     and event_date<="._esc($end_date)."
        	     and event_rep LIKE '%sub%'
        	    order by event_date,event_time";

    $weight = 200 / $max_places;
    if (!$res = ShopDB::query($query)){
      user_error(shopDB::error());
      return;
    }
    echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='10'>\n";
    echo "<tr><td class='admin_list_title' colspan='2' align='center'>
          <a class='link' href='{$_SERVER["PHP_SELF"]}?action=grafik&month=" . ($month > 1?$month - 1:12) . "&year=" . ($month > 1?$year:$year - 1) . "'><<<<< </a>
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" .
    event_stats_title . " " .
    strftime ("%B %Y", mktime (0, 0, 0, $month, 1, $year)) .
    "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  <a class='link' href='{$_SERVER["PHP_SELF"]}?action=grafik&month=" . ($month < 12?$month + 1:1) . "&year=" . ($month < 12?$year:$year + 1) . "'>>>>>></a>
	  </td></tr>\n";
    $alt = 0;
    $i = 0;
    while ($event = shopDB::fetch_assoc($res)){
      $events[$i] = $event;
      $i++;
      $tot = $event["es_total"];
      $free = $event["es_free"];
      $evsaled = ($tot - $free);
      If ($event["event_status"] == 'pub' or $evsaled) {
        echo "<tr class='admin_list_row_$alt'><td class='admin_list_item' width='200'>" .
        $event["event_name"] . "<br> " . formatAdminDate($event["event_date"]) . " " .
        formatTime($event["event_time"]) . "</td><td class='admin_list_item'>";
        $this->plotBar($tot, $free, $weight);
        echo "</td></tr>";
        $alt = ($alt + 1) % 2;
      }
    }
    echo "</table>";
    echo "<br>";
    for($i = 0;$i < sizeof($events);$i++){
      $evtot = $events[$i]["es_total"];
      $evfree = $events[$i]["es_free"];
      $evsaled = ($evtot - $evfree);
      If ($events[$i]["event_status"] == 'pub' or $evsaled) {
        $query = "select MAX(cs_total) from Category_stat ";
        if (!$res = ShopDB::query_one_row($query)){
          user_error(shopDB::error());
          return;
        }
        $max_places = $res[0];
        $weight = 200 / $max_places;

        $query = "select * from Category_stat,Category where
                category_event_id='" . $events[$i]["event_id"] . "'
  	      and cs_category_id=category_id";
        if (!$res = ShopDB::query($query)){
          user_error(shopDB::error());
          return;
        }
        echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='10'>\n";
        echo "<tr><td class='admin_list_title' colspan='2' align='center' >" .
        $events[$i]["event_name"] . " " . formatAdminDate($events[$i]["event_date"]) . " " . formatTime($events[$i]["event_time"]) . "</td></tr>\n";
        $alt = 0;
        while ($cat = shopDB::fetch_assoc($res)){
          echo "<tr class='admin_list_row_$alt'><td class='admin_list_item'  width='200'><img src='{$this->img_pub[$cat['category_status']]}'>&nbsp;" .
          $cat["category_name"] . "</td><td class='admin_list_item' align='left'>";
          $tot = $cat["cs_total"];
          $free = $cat["cs_free"];
          $this->plotBar($tot, $free, $weight);
          echo "</td></tr>";
          $alt = ($alt + 1) % 2;
        }
        echo "</table>";
        echo "<br>";
      }
    }
  }

  function plotBar ($tot, $free, $weight)
  {
    $saled = ($tot - $free);
    $width = ceil($tot * $weight) + 150;
    $wsaled = ceil($saled * $weight);
    $wfree = ceil($free * $weight);
    $percent = 100 * $saled / $tot;
    $percent = round($percent, 2);
    echo "<table border='0' cellspacing='0' width='100%'><tr>";//$width
    if ($wsaled > 0){
      echo "<td bgcolor='#ff0000'><img src='images/dot.gif' width='$wsaled' height='16'></td>";
    }
    if ($wfree > 0){
      echo "<td bgcolor='#00ff00'><img src='images/dot.gif' width='$wfree' height='16'></td>";
    }
    echo "<td nowrap='nowrap' align='right'>$percent% ($saled/$tot)</td></tr></table>";
  }

  function eventStats ($start_date, $end_date, $month, $year)
  {
    global $_SHOP;
    $curr = $_SHOP->currency;
    $query = "select seat_category_id,SUM(seat_price) as total_sum from Seat group by
            seat_category_id";
    if (!$res = ShopDB::query($query)){
      user_error(shopDB::error());
      return;
    } while ($sums = shopDB::fetch_assoc($res)){
      $sum[$sums["seat_category_id"]] = $sums["total_sum"];
    }

    $query = "select Event_stat.*,event_id,event_name,event_date,event_time, event_status from Event_stat,Event
             where  es_event_id=event_id and event_status!='unpub'
	     and event_date >='$start_date'
	     and event_date<='$end_date'
	     and event_rep LIKE '%sub%'
	    order by event_date,event_time";
    if (!$res = ShopDB::query($query)){
      user_error(shopDB::error());
      return;
    }
    $i = 0;
    while ($event = shopDB::fetch_assoc($res)){
      $events[$i] = $event;
      $i++;
    }

    echo "<table class='admin_list' width='$this->width' cellspacing='0' cellpadding='5'>\n";
    echo "<tr><td class='admin_list_title' colspan='5' align='center'>
          <a class='link' href='{$_SERVER["PHP_SELF"]}?month=" . ($month == 1?12:$month - 1) . "&year=" . ($month == 1?$year - 1:$year) . "'><<<<< </a>
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" .
    event_stats_title . " " .
    strftime ("%B %Y", mktime (0, 0, 0, $month, 1, $year)) .
    "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  <a class='link' href='{$_SERVER["PHP_SELF"]}?month=" . (($month < 12)?($month + 1):1) . "&year=" . ($month < 12?$year:$year + 1) . "'>>>>>></a></td></tr>\n";
    for($i = 0;$i < sizeof($events);$i++){
      $evtot = $events[$i]["es_total"];
      $evfree = $events[$i]["es_free"];
      $evsaled = ($evtot - $evfree);
      If ($events[$i]["event_status"] == 'pub' or $evsaled) {
        $evpercent = 100 * $evsaled / $evtot;
        $evpercent = round($evpercent, 2);

        echo "<tr class='stats_event_item'><td colspan='5'>" . $events[$i]["event_name"] . " " .
        formatAdminDate($events[$i]["event_date"]) . " " . formatTime($events[$i]["event_time"]) . "</td></tr>";

        $query = "select * from Category_stat,Category where
                category_event_id='" . $events[$i]["event_id"] . "'
  	      and cs_category_id=category_id";
        if (!$res = ShopDB::query($query)){
          user_error(shopDB::error());
          return;
        }
        while ($cat = shopDB::fetch_assoc($res)){
          $tot = $cat["cs_total"];
          $free = $cat["cs_free"];
          $saled = ($tot - $free);
          $percent = 100 * $saled / $tot;
          $percent = round($percent, 2);
          // $gain=$cat["category_price"]*$saled;
          if ($sum[$cat["category_id"]]){
            $gain = $sum[$cat["category_id"]];
          }else{
            $gain = 0;
          }
          $sum_gain += $gain;
          echo "
                <tr><td class='stats_event_item' align='right'>&nbsp;</td>
                    <td class='stats_cat_item' witdh='20'><img src='{$this->img_pub[$cat['category_status']]}'>&nbsp;" .$cat["category_name"] . "</td>
                    <td align='right' class='stats_cat_item' align='50%'>$percent%</td>
  	                <td align='right' class='stats_cat_item'>$saled/$tot</td>
                    <td class='stats_cat_item' align='right'> " . sprintf("%1.2f", $gain) . " $curr</td>
                </tr>";
        }
        echo "<tr class='stats_event_item'><td colspan='2'>&nbsp;&nbsp;</td>";
        echo "<td align='right' >$evpercent%</td><td align='right' >$evsaled/$evtot</td>
        <td align='right'>  " . sprintf("%1.2f", $sum_gain) . " $curr</td>";
        echo "</tr><tr><td colapsn='5'>&nbsp;&nbsp;</td>";

        echo "</tr>";
        $sum_gain = 0;
      }
    }
    echo "</table>";
  }

  function draw ()
  {
    global $_SHOP;
    $this->fill_images();
    if (!($_GET['month'] or $_GET['year'])){
      $date = date('Y-m-1');

      $query = "select event_date from Event where event_date>='$date' order by event_date,event_time limit 1";
      require_once('classes/ShopDB.php');
      if ($row = ShopDB::query_one_row($query) and !empty($row[0])){
        list($year, $month) = split('-', $row[0]);
        $start_date = "$year-$month-1";
        $end_date = "$year-$month-31";
      }else{
        $start_date = date("Y-m-01");
        $end_date = date("Y-m-31");
        $month = date("m");
        $year = date("Y");
      }
    }elseif (!($_GET["month"] and $_GET["year"])){
      $start_date = date("Y-m-01");
      $end_date = date("Y-m-31");
      $month = date("m");
      $year = date("Y");
    }else{
      $start_date = $_GET["year"] . "-" . $_GET["month"] . "-01";
      $end_date = $_GET["year"] . "-" . $_GET["month"] . "-31";
      $month = $_GET["month"];
      $year = $_GET["year"];
      $mydate = "&month={$_GET["month"]}&year={$_GET["year"]}";

    }
    if ($_GET["action"] == 'grafik'){
      $this->plotEventStats($start_date, $end_date, $month, $year);
      echo "\n<center><button type='button' onclick='location.href=\"?action=text{$mydate}\"'>".show_text_stats."</button>";
    }else{
      $this->eventStats($start_date, $end_date, $month, $year);
      echo "\n<br><center><button type='button' onclick='location.href=\"?action=grafik{$mydate}\"'>".show_grafik_stats."</button>";
    }
  }
}

?>
