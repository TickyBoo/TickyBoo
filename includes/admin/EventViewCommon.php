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

class EventViewCommon extends AdminView {
    function print_select_pm ($name, &$data, &$err, $main = 'main')
    {
        global $_SHOP;
        $query = "SELECT pm_id,ort_id,pm_ort_id,pm_name,ort_name
                  FROM Ort LEFT JOIN PlaceMap2 ON pm_ort_id=ort_id
                  where pm_event_id IS NULL
                  and   ort_organizer_id={$_SHOP->organizer_id}
                  order by ort_name";
        if (!$res = ShopDB::query($query)) {
            return;
        }

        echo "<tr><td class='admin_name'>" . $this->con($name) . "</td>
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
        } while ($row = shopDB::fetch_assoc($res)) {
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

    function print_select_tpl ($name, &$data, &$err, $suffix = '')
    {
        global $_SHOP;

        $query = "SELECT template_name FROM Template
                  WHERE template_type='pdf'
                  and template_organizer_id='{$_SHOP->organizer_id}'
                  ORDER BY template_name";

        if (!$res = ShopDB::query($query)) {
            return false;
        }

        $sel[$data[$name]] = " selected ";

        echo "<tr><td class='admin_name'  width='40%'>$suffix" . $this->con($name) . "</td>
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

    function print_select_group ($name, &$data, &$err)
    {
        global $_SHOP;

        $query = "SELECT event_group_id,event_group_name
                  FROM Event_group
            	  WHERE event_group_organizer_id='{$_SHOP->organizer_id}'
            	  ORDER BY event_group_name";
        if (!$res = ShopDB::query($query)) {
            return false;
        }

        $sel[$data[$name]] = " selected ";

        echo "<tr><td class='admin_name'  width='40%'>" . $this->con($name) . "</td>
              <td class='admin_value'>
               <select name='$name'>
               <option value=''></option>\n";

        while ($v = shopDB::fetch_row($res)) {
            echo "<option value='{$v[0]}' " . $sel[$v[0]] . ">{$v[1]}</option>\n";
        }

        echo "</select><span class='err'>{$err[$name]}</span>
              </td></tr>\n";
    }

    function print_pay ($name, &$data, &$err)
    {
        if (!(strpos($data[$name], 'CC') === false)) {
            $chk_cc = "checked";
        }
        if (!(strpos($data[$name], 'POST') === false)) {
            $chk_post = "checked";
        }

        echo "<tr><td class='admin_name' rowspan='2'>" . $this->con($name) . "<div class='err'>{$err[$name]}</div></td>
              <td class='admin_value'><label><input type='checkbox' name='{$name}[1]' value='CC' $chk_cc>" . $this->con(payment_cc) . "</label></td>
              </tr><tr><td class='admin_value'><label><input type='checkbox' name='{$name}[2]' value='POST' $chk_post>" . $this->con(payment_post) . "</label></td></tr>\n";
    }

    function print_select_ort ($name, &$data, &$err)
    {
        $query = "SELECT * FROM Ort";
        if (!$res = ShopDB::query($query)) {
            return;
        }

        echo "<tr><td class='admin_name'>" . $this->con($name) . "</td>
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

    function get_event_types ()
    {
        $query = "SHOW  COLUMNS  FROM Event LIKE  'event_type'";
        if (!$res = ShopDB::query_one_row($query)) {
            return;
        }
        $types = explode("','", preg_replace("/(enum|set)\('(.+?)'\)/", "\\2", $res[1]));
        return $types;
    }

    function select_types ($name, &$data, &$err)
    {
        $sel[$data["$name"]] = " selected ";
        echo "<tr><td class='admin_name'  width='40%'>" . $this->con($name) . "</td>
              <td class='admin_value'> <select name='$name'>";
        $types = $this->get_event_types();
        // print_r($types);
        foreach($types as $k => $v) {
            echo "<option value='" . $v . "' " . $sel[$v] . ">" . $this->con($v) . "</option>\n";
        }
        echo "</select><span class='err'>{$err[$name]}</span></td></tr>\n";
    }
    function print_type ($name, &$data)
    {
        echo "<tr><td class='admin_name' width='40%'>" . $this->con($name) . "</td>
              <td class='admin_value'>" . $this->con($data[$name]) . "
              </td></tr>\n";
    }
}

?>