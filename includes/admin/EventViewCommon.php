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

if (!defined('ft_check')) {die('System intrusion ');}
require_once("admin/AdminView.php");

class EventViewCommon extends AdminView {
  function print_select_pm ($name, &$data, &$err, $main = 'main') {
    global $_SHOP;
    $query = "SELECT pm_id,ort_id,pm_ort_id,pm_name,ort_name
              FROM Ort LEFT JOIN PlaceMap2 ON pm_ort_id=ort_id
              where pm_event_id IS NULL
              order by ort_name";
    if (!$res = ShopDB::query($query)) {
        return;
    }

    echo "<tr><td class='admin_name'>" . con($name) . "</td>
          <td class='admin_value'><select name='$name'>\n";

    if ($data[$name]) {
        $sel[$data[$name]] = 'selected';
    } else {
        $sel['no_pm'] = 'selected';
    }

    if ($main == 'main') {
        echo "<option value='no_pm' {$sel['no_pm']}></option>";
    } elseif ($main == 'has_def') {
        echo "<option value='copy_main_pm' {$sel['no_pm']}>(" . copy_main_pm . ")</option>";
    }
    while ($row = shopDB::fetch_assoc($res)) {
        if ($row['ort_id'] != $ort_id) {
            $ort_id = $row['ort_id'];
            echo "<option value='0,{$row['ort_id']}' {$sel[$row['pm_id']]}>{$row['ort_name']} - " . agenda_only . "</option>\n";
        }
        if ($row['pm_id']) {
            echo "<option value='{$row['pm_id']},{$row['pm_ort_id']}' {$sel[$row['pm_id']]}>{$row['ort_name']} - {$row['pm_name']}</option>\n";
        }
    }

    echo "</select><span class='err'>{$err[$name]}</td></tr>\n";
  }

  function print_select_tpl ($name, &$data, &$err, $suffix = '') {
    global $_SHOP;

    $query = "SELECT template_name FROM Template
              WHERE template_type='pdf2'
              ORDER BY template_name";

    if (!$res = ShopDB::query($query)) {
        return false;
    }

    $sel[$data[$name]] = " selected ";

    echo "<tr><td class='admin_name'  width='40%'>$suffix" . con($name) . "</td>
            <td class='admin_value'>
              <select name='$name'>
               <option value=''></option>\n";

    while ($v = shopDB::fetch_row($res)) {
        $value = htmlentities($v[0], ENT_QUOTES);
        echo "<option value='$value' " . $sel[$v[0]] . ">{$v[0]}</option>\n";
    }

    echo "</select><span class='err'>{$err[$name]}</span>
          </td></tr>\n";
  }

  function print_select_group ($name, &$data, &$err){
      global $_SHOP;

      $query = "SELECT event_group_id,event_group_name
                FROM Event_group
          	  ORDER BY event_group_name";
      if (!$res = ShopDB::query($query)) {
          return false;
      }

      $sel[$data[$name]] = " selected ";

      echo "<tr><td class='admin_name'  width='40%'>" . con($name) . "</td>
            <td class='admin_value'>
             <select name='$name'>
             <option value=''></option>\n";

      while ($v = shopDB::fetch_row($res)) {
          echo "<option value='{$v[0]}' " . $sel[$v[0]] . ">{$v[1]}</option>\n";
      }

      echo "</select><span class='err'>{$err[$name]}</span>
            </td></tr>\n";
  }


  function print_select_ort ($name, &$data, &$err)
  {
      $query = "SELECT * FROM Ort";
      if (!$res = ShopDB::query($query)) {
          return;
      }

      echo "<tr><td class='admin_name'>" . con($name) . "</td>
  <td class='admin_value'><select name='$name'>\n";

      $sel[$data[$name]] = 'selected';

      while ($row = shopDB::fetch_assoc($res)) {
          echo "<option value='{$row['ort_id']}' {$sel[$row['ort_id']]}>{$row['ort_name']}</option>\n";
      }

      echo "</select></td></tr>\n";
  }

  function photo_post($data, $event_id)
  {
      return $this->file_post($data, $event_id, 'Event', 'event');
  }

  function mp3_post ($data, $event_id)
  {
      return $this->file_post($data, $event_id, 'Event', 'event', '_mp3');
  }

  function photo_post_ort ($data, $event_id)
  {
      return $this->file_post($data, $event_id, 'Event', 'event', '_ort_image');
  }

  function get_event_types () {
     global $_SHOP;
     return $_SHOP->event_type_enum;
  }

  function select_types ($name, &$data, &$err) {
      global $_SHOP;
      $sel[$data["$name"]] = " selected ";
      echo "<tr><td class='admin_name'  width='40%'>" . con($name) . "</td>
            <td class='admin_value'> <select name='$name'>";
      $types = $_SHOP->event_type_enum;
      // print_r($types);
      foreach($types as $k => $v) {
          echo "<option value='" . $v . "' " . $sel[$v] . ">" . con($v) . "</option>\n";
      }
      echo "</select><span class='err'>{$err[$name]}</span></td></tr>\n";
  }

  function print_type ($name, &$data)
  {
      echo "<tr><td class='admin_name' width='40%'>" . con($name) . "</td>
            <td class='admin_value'>" . con($data[$name]) . "
            </td></tr>\n";
  }
  //mychanges

  function print_subtitle($name)
  {
  	echo "<tr>
  			<td colspan=2>$name</td>
  		  </tr>";
  }
  function print_select_recurtype($name,$data)
  {
  	$type_list = array("nothing","daily");

  	echo "<tr><td class='admin_name' width='40%'>".con($name)."</td>
  			<td  class='admin_value' ><select id='event_recur_type' name={$name} onchange='changeRecurType(this.value)'>\n";
  	foreach ($type_list as $item) {
  		echo "<option ".(($data["$name"] == $item) ? "selected" : '')." value={$item}>".con("recure_$item")."</option>\n";
  	}
  	echo "</select></td></tr>\n";
  	echo "<tr>
            <td colspan='2' style='padding:0px;margin:0px;'>
               <table id='recur_table' border=0 width='100%'>\n";
  }

  function print_select_recurdays($dsp_name,$name)
  {
  	echo "<tr><td class='admin_name' width='40%'>$dsp_name</td>
  			<td  class='admin_value'><div  style='float:left;' id='$name'> <select name='$name'>\n";
  	for($i=1;$i<8;$i++) {
  		echo "<option>".$i."</option>\n";
  	}
  	echo "</select></div><div style='float:left;padding-left:2px;' id='$name-suffix'> days</div></td></tr>\n";
  }

  function print_recur_enddate($dsp_name,$name) {
  	echo "<tr><td  class='admin_value' colspan='2' style='padding:0px;margin:0px;'>\n";
  	echo "<table id='recur_table' border=1 width='100%'>
            <tr>
              <td class='admin_name' width='40%'>$dsp_name</td>
	          <td  class='admin_value' ><input type='text' name='$name'></td>
            </tr>\n";

  }

  function print_days_selection(&$data,&$err) {
    GLOBAL $_SHOP;
    $days           = array( 1,2,3,4,5,6, 0);
 	  $exclusion_days = is($data['recurse_days_selection'],array());

    If (!isset($_SHOP->days_arr) or empty($_SHOP->days_arr)) {
  		$_SHOP->days_arr  = explode('|',con('dts_day_arr'));
    }

  	echo "
        <tr>
  			  <td class='admin_name' width='40%'>".con('recure_days_selection')."</td>
  		    <td class='admin_value'>
  		    	<table id='day_options'>
  		    		<tr>";
    $cnt = 0;
    foreach( $days as $myday) {
      $cnt++;
      echo "		<td class='admin_name'>
			    				<input type='checkbox' name='recurse_days_selection[]' value='{$myday}' ".
			    				((in_array($myday, $exclusion_days))?'checked':'').
                  " >&nbsp;".$_SHOP->days_arr[$myday]."&nbsp;
 		    			</td>\n";
 		if ($cnt == 4) {
        echo "  </tr>
	      		  <tr>\n";
        $cnt = 0;
      }
    }
    echo "	</tr>
	      	</table>
   		    	<span class='err'>{$err['opt_days']}</span>
   		  	</td>
	   </tr>\n";
  }

  function getEventRecurDates($data, $invert= true) {
  	$event_dates	= array();
  	$rep_days     = is($data['recurse_days_selection'], array());
  	$start_date 	= $data['event_date'];
		$end_date     = $data['event_recur_end'];

    if ($invert) {
		  $rep_days     = array_diff(array(0,1,2,3,4,5,6), $rep_days);
    }

		$dt_split     = explode("-",$start_date);
		$weekday      = date("w", mktime(0,0,0,$dt_split[1],$dt_split[2],$dt_split[0]));
		$no_days      = ceil(stringDatediff($start_date, $end_date) / 86400 );

    for($i = 0; $i <= $no_days; $i++) {
      $x = ($weekday + $i) % 7;
      if (in_array($x, $rep_days)) {
				$event_dates[] = addDaysToDate($start_date, $i);
      }
    }
		return $event_dates;
  }

  function Print_Recure_end(){
	   echo "
         </table>
	   </td>
   </tr>\n";
  }

  function printRecurChangeScript() {
  	echo "<script type='text/javascript'>
  			changeRecurType();
  			function changeRecurType() {
  				type = document.getElementById('event_recur_type').value;
  				if(type == 'daily') {
 				    document.getElementById('recur_table').style.display='';
  				} else {
  					document.getElementById('recur_table').style.display='none';
  				}
  			}
  		  </script>
  		";
  }



}

?>