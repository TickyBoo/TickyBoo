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

require_once('classes/PlaceMapZone.php');
require_once('classes/PlaceMapCategory.php');


define('PM_ZONE', 0);
define('PM_LABEL', 0);
define('PM_ROW', 1);
define('PM_LABEL_TYPE', 1);
define('PM_SEAT', 2);
define('PM_LABEL_SIZE', 2);
define('PM_CATEGORY', 3);
define('PM_LABEL_TEXT', 3);
define('PM_ID', 4);
define('PM_STATUS', 5);

define('PM_STATUS_FREE', 0);
define('PM_STATUS_OCC', 1);
define('PM_STATUS_RESP', 2);
define('PM_STATUS_HOLD', 3);

class PlaceMapPart { // ZRS
    var $pmp_id;
    var $pmp_name;
    var $pmp_pm_id;
    var $pmp_width;
    var $pmp_height;
    var $pmp_data;

    function PlaceMapPart ($pmp_pm_id = 0, $pmp_name = 0, $pmp_width = 0, $pmp_height = 0)
    {
        if ($pmp_pm_id) {
            $this->pmp_pm_id = $pmp_pm_id;
            $this->pmp_name = $pmp_name;
            $this->pmp_width = $pmp_width;
            $this->pmp_height = $pmp_height;

            $this->pmp_data = array_fill(0, $pmp_height, array_fill(0, $pmp_width, array(0, 0, 0)));
        }
    }

    function _ser_data ()
    {
        foreach($this->pmp_data as $row) {
            foreach($row as $seat) {
                $res .= implode(',', $seat) . '|';
            }
        }

        return substr($res, 0, - 1);
    }

    function _unser_data ($pmp_data_s, $w, $h)
    {
        $pmp_data_0 = explode('|', $pmp_data_s);
        $c = 0;
        for($j = 0;$j < $h;$j++) {
            for($k = 0;$k < $w;$k++) {
                $pmp_data_a[$j][$k] = explode(',', $pmp_data_0[$c++]);
            }
        }

        return $pmp_data_a;
    }

    function save ()
    {
        global $_SHOP;

        $data = $this->_ser_data();

        if ($this->pmp_id) {
            $query = "update PlaceMapPart set
            pmp_pm_id=" .   _esc($this->pmp_pm_id) . ",
            pmp_event_id=". _esc($this->pmp_event_id) . ",
      	    pmp_name=" .    _esc($this->pmp_name) . ",
            pmp_width=" .   _esc($this->pmp_width) . ",
      	    pmp_height=" .  _esc($this->pmp_height) . ",
      	    pmp_data=" .    _esc($data) . ",
      	    pmp_expires=" . _esc($this->pmp_expires) . ",
      	    pmp_scene=" .   _esc($this->pmp_scene) . ",
      	    pmp_shift=" .   _esc($this->pmp_shift) . "
      	    where pmp_id=". _esc($this->pmp_id) ;
        } else {
            $query = "insert into PlaceMapPart (
                 pmp_pm_id,
		             pmp_event_id,
    	           pmp_name,
	               pmp_width,
	               pmp_height,
   	             pmp_data,
		             pmp_scene,
		             pmp_shift
               ) VALUES (
                 ". _esc($this->pmp_pm_id) . ",
                 ". _esc($this->pmp_event_id) . ",
                 ". _esc($this->pmp_name) . ",
                 ". _esc($this->pmp_width) . ",
                 ". _esc($this->pmp_height) . ",
                 ". _esc($data) . ",
                 ". _esc($this->pmp_scene) . ",
                 ". _esc($this->pmp_shift).")";
        }

        if (ShopDB::query($query)) {
            if (!$this->pmp_id) {
                $this->pmp_id = shopDB::insert_id();
            }

            return $this->pmp_id;
        } else {
            echo shopDB::error();
            return false;
        }
    }

    function load ($pmp_id)
    {
        global $_SHOP;

        $query = "select * from PlaceMapPart where pmp_id=$pmp_id ";

        if ($res = ShopDB::query_one_row($query)) {
            $new_pmp = new PlaceMapPart;
            $new_pmp->_fill($res);
            $new_pmp->pmp_data = PlaceMapPart::_unser_data($res['pmp_data'], $res['pmp_width'], $res['pmp_height']);

            return $new_pmp;
        }
    }

    function loadAll_short ($pm_id)
    {
        global $_SHOP;

        $query = "select pmp_id,pmp_name from PlaceMapPart where pmp_pm_id=$pm_id order by pmp_id";
        if ($res = ShopDB::query($query)) {
            while ($data = shopDB::fetch_array($res)) {
                $new_pmp = new PlaceMapPart;
                $new_pmp->_fill($data);

                $all[] = $new_pmp;
            }
        }

        return $all;
    }

    function loadAll ($pm_id)
    {
        global $_SHOP;

        $query = "select * from PlaceMapPart where pmp_pm_id=$pm_id  order by pmp_id";

        if ($res = ShopDB::query($query)) {
            while ($data = shopDB::fetch_assoc($res)) {
                $new_pmp = new PlaceMapPart;
                $new_pmp->_fill($data);
                $new_pmp->pmp_data = PlaceMapPart::_unser_data($data['pmp_data'], $data['pmp_width'], $data['pmp_height']);

                $all[] = $new_pmp;
            }
        }

        return $all;
    }

    function load_full ($pmp_id)
    {
        global $_SHOP;

        $query = "select * from Ort,PlaceMapPart,PlaceMap2 LEFT JOIN Event ON pm_event_id=event_id where pmp_pm_id=pm_id and ort_id=pm_ort_id and pmp_id=$pmp_id ";

        if ($res = ShopDB::query_one_row($query)) {
            $new_pmp = new PlaceMapPart;
            $new_pmp->_fill($res);
            $new_pmp->pmp_data = PlaceMapPart::_unser_data($res['pmp_data'], $res['pmp_width'], $res['pmp_height']);

            $new_pmp->zones = PlaceMapZone::loadAll($new_pmp->pm_id);
            $new_pmp->categories = PlaceMapCategory::loadAll($new_pmp->pm_id);

            return $new_pmp;
        }
    }

    function loadAll_full ($pm_id)
    {
        global $_SHOP;

        $query = "select * from Ort, PlaceMapPart, PlaceMap2 LEFT JOIN Event ON pm_event_id=event_id
                  where pmp_pm_id=pm_id and ort_id=pm_ort_id and  pm_id=$pm_id ";

        if ($res = ShopDB::query($query)) {
            while ($data = shopDB::fetch_array($res)) {
                $new_pmp = new PlaceMapPart;
                $new_pmp->_fill($data);
                $new_pmp->pmp_data = PlaceMapPart::_unser_data($data['pmp_data'], $data['pmp_width'], $data['pmp_height']);

                $new_pmp->zones = PlaceMapZone::loadAll($new_pmp->pm_id);
                $new_pmp->categories = PlaceMapCategory::loadAll($new_pmp->pm_id);

                $pmps[] = $new_pmp;
            }
        }
        return $pmps;
    }

    function delete ()
    {
        global $_SHOP;

        $query = "delete from PlaceMapPart where pmp_id={$this->pmp_id} limit 1";
        ShopDB::query($query);
    }

    function set_zone ($zone_id, $zone_map)
    {
        for($j = 0;$j < $this->pmp_height;$j++) {
            for($k = 0;$k < $this->pmp_width;$k++) {
                if ($zone_map[$j][$k]) {
                    if ($this->pmp_data[$j][$k][PM_ZONE] != $zone_id) {
                        $this->pmp_data[$j][$k] = array($zone_id, 0, 0);
                    }
                } else if ($this->pmp_data[$j][$k][PM_ZONE] == $zone_id) {
                    $this->pmp_data[$j][$k] = array(0, 0, 0);
                }
            }
        }
    }

    function set_numbers ($zone_id, $numbers, $sep = '/')
    {
        foreach($numbers as $j => $row) {
            foreach($row as $k => $seat_s) {
                list($row, $seat) = explode($sep, $seat_s);
                $row = strtr($row, ',|', '..');
                $seat = strtr($seat, ',|', '..');

                if ($this->pmp_data[$j][$k][PM_ZONE] == $zone_id) {
                    $this->pmp_data[$j][$k][PM_ROW] = $row;
                    $this->pmp_data[$j][$k][PM_SEAT] = $seat;
                }
            }
        }
    }

    function set_category ($category_id, $map)
    { 
        // echo "<div align=left><pre>";
        // print_r($map);
        // echo '</pre></div>';
        for($j = 0;$j < $this->pmp_height;$j++) {
            for($k = 0;$k < $this->pmp_width;$k++) {
                if ($this->pmp_data[$j][$k][PM_ZONE] > 0) {
                    if ($map[$j][$k]) {
                        $this->pmp_data[$j][$k][PM_CATEGORY] = $category_id;
                    } else if ($this->pmp_data[$j][$k][PM_CATEGORY] == $category_id) {
                        $this->pmp_data[$j][$k][PM_CATEGORY] = 0;
                    }
                }
            }
        }
    }

    function clear ($map)
    {
        foreach($map as $j => $row) {
            foreach($row as $k => $sel) {
                if ($sel) {
                    $this->pmp_data[$j][$k] = array(0);
                }
            }
        }
    }

    function set_label ($label_type, $map, $label_text = '')
    {
        $label_text = strtr($label_text, ',|', '..');
        if ($label_type == 'T') {
            if (count($map) != 1) {
                echo a;
                return;
            }
            foreach($map as $j => $row) {
                foreach($row as $k => $sel) {
                    if (!isset($k_prev)) {
                        $k_prev = $k;
                    } else if (($k - 1) != $k_prev or !$sel) {
                        echo "$k $k_prev";
                        return;
                    } else {
                        $k_prev = $k;
                    }
                }
            }
        }
        foreach($map as $j => $row) {
            foreach($row as $k => $sel) {
                if ($sel) {
                    $this->pmp_data[$j][$k][PM_LABEL] = 'L';
                    $this->pmp_data[$j][$k][PM_LABEL_TYPE] = $label_type;
                    if ($label_type == 'T') {
                        if (!$cont) {
                            $cont->j = $j;
                            $cont->k = $k;
                            $cont->size = 1;
                            $this->pmp_data[$j][$k][PM_LABEL_TEXT] = $label_text;
                        } else {
                            $cont->size++;
                            $this->pmp_data[$j][$k][PM_LABEL_SIZE] = 0;
                        }
                    }
                }
            }
        }
        if ($cont) {
            $this->pmp_data[$cont->j][$cont->k][PM_LABEL_SIZE] = $cont->size;
        }
    }

    function get_stats ($stats = null)
    {
        if ($stats) {
            $zone = $stats->zones;
            $cat = $stats->categories;
        }

        for($j = 0;$j < $this->pmp_height;$j++) {
            for($k = 0;$k < $this->pmp_width;$k++) {
                $seat = $this->pmp_data[$j][$k];
                if ($seat[PM_ZONE] > 0) {
                    $zone[$seat[PM_ZONE]]++;
                    $cat[$seat[PM_CATEGORY]]++;
                }
            }
        }
        $stats->zones = $zone;
        $stats->categories = $cat;
        return $stats;
    }

    function auto_numbers ($zone_id, $first_row = 1, $step_row = 1, $inv_row = false,
        $first_seat = 1, $step_seat = 1, $inv_seat = false,
        $flip = false, $keep = false)
    {
        $pmp_height = $this->pmp_height;
        $pmp_width = $this->pmp_width;

        $row = $first_row;

        for($j = 0;$j < $pmp_height;$j++) {
            $seat = $first_seat;
            for($k = 0;$k < $pmp_width;$k++) {
                $j_0 = ($inv_row?$pmp_height - $j - 1:$j);
                $k_0 = ($inv_seat?$pmp_width - $k - 1:$k);

                if ($this->pmp_data[$j_0][$k_0][PM_ZONE] == $zone_id) {
                    if (!$flip) {
                        if ($keep and $this->pmp_data[$j_0][$k_0][PM_SEAT]) {
                            $seat = $this->pmp_data[$j_0][$k_0][PM_SEAT];
                        } else {
                            $this->pmp_data[$j_0][$k_0][PM_SEAT] = strtr($seat, ',|', '..');
                        }
                        if ($keep and $this->pmp_data[$j_0][$k_0][PM_ROW]) {
                            $row = $this->pmp_data[$j_0][$k_0][PM_ROW];
                        } else {
                            $this->pmp_data[$j_0][$k_0][PM_ROW] = strtr($row, ',|', '..');
                        }
                    } else {
                        if ($keep and $this->pmp_data[$j_0][$k_0][PM_SEAT]) {
                            $row = $this->pmp_data[$j_0][$k_0][PM_SEAT];
                        } else {
                            $this->pmp_data[$j_0][$k_0][PM_SEAT] = strtr($row, ',|', '..');
                        }
                        if ($keep and $this->pmp_data[$j_0][$k_0][PM_ROW]) {
                            $seat = $this->pmp_data[$j_0][$k_0][PM_ROW];
                        } else {
                            $this->pmp_data[$j_0][$k_0][PM_ROW] = strtr($seat, ',|', '..');
                        }
                        // $this->pmp_data[$j_0][$k_0][PM_SEAT]=$row;
                        // $this->pmp_data[$j_0][$k_0][PM_ROW]=$seat;
                    }
                    if (is_numeric($seat)) {
                        $seat += $step_seat;
                    } else {
                        $seat = chr(ord($seat) + $step_seat);
                    }
                }
            }
            if ($seat != $first_seat) {
                if (is_numeric($row)) {
                    $row += $step_row;
                } else {
                    $row = chr(ord($row) + $step_row);
                }
            }
        }
    }

    function add_rows ($count, $before_row = 0)
    {
        $new_pmp = array();

        for($i = 0;$i < $before_row;$i++) {
            $new_pm[] = $this->pmp_data[$i];
        }

        for($i = 0;$i < $count;$i++) {
            $new_pm[] = array_fill(0, $this->pmp_width, array(0, 0, 0));
        }

        for($i = $before_row;$i < $this->pmp_width;$i++) {
            $new_pm[] = $this->pmp_data[$i];
        }

        $this->pmp_data = $new_pmp;
        $thia->pm_heigth = $this->pmp_height + $count;
    }

    function zone_bounds ($zone_id)
    {
        $l = $this->pmp_width;
        $r = 0;
        $t = $this->pmp_height;
        $b = 0;

        for($j = 0;$j < $this->pmp_height;$j++) {
            for($k = 0;$k < $this->pmp_width;$k++) {
                if ($this->pmp_data[$j][$k][PM_ZONE] == $zone_id) {
                    $l = min($l, $k);
                    $r = max($r, $k);
                    $t = min($t, $j);
                    $b = max($b, $j);
                }
            }
        }

        if ($l <= $r and $b >= $t) {
            return array('left' => $l, 'right' => $r, 'top' => $t, 'bottom' => $b,
                'width' => $r - $l, 'height' => $b - $t);
        } else {
            return false;
        }
    }

    function category_bounds ($cat_id)
    {
        $l = $this->pmp_width;
        $r = 0;
        $t = $this->pmp_height;
        $b = 0;

        for($j = 0;$j < $this->pmp_height;$j++) {
            for($k = 0;$k < $this->pmp_width;$k++) {
                $seat = $this->pmp_data[$j][$k];
                if ($seat[PM_ZONE] > 0 and $seat[PM_CATEGORY] == $cat_id) {
                    $l = min($l, $k);
                    $r = max($r, $k);
                    $t = min($t, $j);
                    $b = max($b, $j);
                }
            }
        }

        if ($l <= $r and $b >= $t) {
            return array('left' => $l, 'right' => $r, 'top' => $t, 'bottom' => $b,
                'width' => $r - $l, 'height' => $b - $t);
        } else {
            return false;
        }
    }

    function zone_map ($zone_id)
    {
        if ($box = $this->zone_bounds($zone_id)) {
            $zone = array();
            for($j = $box['top'];$j < $box['height'];$j++) {
                $zone[$j] = array();
                for($k = $box['left'];$k < $box['width'];$k++) {
                    $zone[$j][$k] = $this->pmp_data[$j][$k];
                }
            }
            return $zone;
        } else {
            return false;
        }
    }

    function delete_zone ($zone_ident)
    {
        for($j = 0;$j < $this->pmp_height;$j++) {
            for($k = 0;$k < $this->pmp_width;$k++) {
                if ($this->pmp_data[$j][$k][PM_ZONE] == $zone_ident) {
                    $this->pmp_data[$j][$k] = array(0, 0, 0);
                    $count++;
                }
            }
        }
        return $count;
    }

    function delete_category ($cat_ident)
    {
        for($j = 0;$j < $this->pmp_height;$j++) {
            for($k = 0;$k < $this->pmp_width;$k++) {
                if ($this->pmp_data[$j][$k][PM_ZONE] > 0 and $this->pmp_data[$j][$k][PM_CATEGORY] == $cat_ident) {
                    $tmp = $this->pmp_data[$j][$k];
                    $tmp[PM_CATEGORY] = 0;
                    $this->pmp_data[$j][$k] = $tmp;
                    $count++;
                }
            }
        }
        return $count;
    }

    function split (&$index, &$cats, &$old_cats, $zones = true)
    {
        $index_0 = $index + 0;
        for($j = 0;$j < $this->pmp_height;$j++) {
            for($k = 0;$k < $this->pmp_width;$k++) {
                $seat = $this->pmp_data[$j][$k];
                if ($seat[PM_ZONE] > 0 and $seat[PM_CATEGORY]) {
                    if ($zones) {
                        $key = $this->pmp_id . "-" . $seat[PM_ZONE] . "-" . $seat[PM_CATEGORY];
                    } else {
                        $key = $this->pmp_id . "-" . $seat[PM_CATEGORY];
                    }

                    if ($cat = $cats[$key]) {
                        $this->pmp_data[$j][$k][PM_CATEGORY] = $cat->category_ident;
                    } else {
                        $category_ident = $index++;

                        $zone = $this->zones[$seat[PM_ZONE]];
                        $old_cat = $this->categories[$seat[PM_CATEGORY]];

                        if ($zones) {
                            $category_name = "{$this->pmp_name} {$zone->pmz_name} {$old_cat->category_name}";
                        } else {
                            $category_name = "{$this->pmp_name} {$old_cat->category_name}";
                        }

                        $cat = new PlaceMapCategory($this->pmp_pm_id, $category_name,
                            $old_cat->category_price,
                            $old_cat->category_template, $old_cat->category_color,
                            $old_cat->category_numbering, 0,
                            $old_cat->category_event_id);

                        $cat->category_ident = $category_ident;
                        $this->pmp_data[$j][$k][PM_CATEGORY] = $cat->category_ident;

                        $cats[$key] = $cat; 
                        // $old_cat->category_name.= " - OLD";
                        $old_cats[$old_cat->category_id] = $old_cat;
                    }
                }
            }
        }

        return $index_0 != $index;
    }

    function _fill ($data)
    {
        foreach($data as $k => $v) {
            $this->$k = $v;
        }
    }

    function publish ($event_id, $dummy, &$stats, &$pmps, $dry_run = false)
    {
        require_once('classes/Seat.php');

        for($j = 0;$j < $this->pmp_height;$j++) {
            for($k = 0;$k < $this->pmp_width;$k++) {
                $seat = $this->pmp_data[$j][$k];
                if ($seat[PM_ZONE] > 0 and $seat[PM_CATEGORY]) {
                    $zone = $this->zones[$seat[PM_ZONE]];
                    $category = $this->categories[$seat[PM_CATEGORY]];

                    if ($category->category_numbering == 'none') {
                        continue;
                    }

                    if ($dry_run or $seat_id = Seat::publish($event_id, $seat[PM_ROW], $seat[PM_SEAT],
                            $zone->pmz_id, $this->pmp_id, $category->category_id)) {
                        if (!$dry_run) {
                            $this->pmp_data[$j][$k][PM_ID] = $seat_id;
                        }

                        $stats[$category->category_ident]++;
                        $pmps_n[$category->category_ident] = $this->pmp_id;
                    } else {
                        return false;
                    }
                }
            }
        }

        if ($pmps_n) {
            foreach($pmps_n as $cat_ident => $pmp_id) {
                $pmps[$cat_ident][] = $pmp_id;
            }
        }
        return true;
    }

    function save_original ()
    {
        global $_SHOP;

        $query = "update PlaceMapPart set
	    pmp_data_orig=pmp_data
	    where pmp_id='{$this->pmp_id}' ";

        if (ShopDB::query($query)) {
            return $this->pmp_id;
        } else {
            return false;
        }
    }

    function check_cache ()
    {
        $now = time();
        if ($this->pmp_expires and $this->pmp_expires <= $now) {
            $this->rebuild_cache();
            $this->save();
        }
    }

    function rebuild_cache ()
    { 
        // echo rebuild_cache;
        require_once('classes/Seat.php');
        Seat::expire_pmp($this->pmp_id);

        $seats_db = Seat::load_pmp_all($this->pmp_id);
        $expires = time() + 3600;

        /*
    echo "<pre align=left>seats_db:
    ";
    print_r($seats_db);
    echo "</pre>";
    */

        if ($seats_db) {
            for($j = 0;$j < $this->pmp_height;$j++) {
                for($k = 0;$k < $this->pmp_width;$k++) {
                    $seat_c = $this->pmp_data[$j][$k];

                    if ($seat_c[PM_ZONE] > 0 and $seat_c[PM_CATEGORY]) {
                        if ($seat_db = $seats_db[$seat_c[PM_ID]]) {
                            if ($seat_db['seat_ts']) {
                                $expires = min($seat_db['seat_ts'], $expires);
                            }

                            if ($seat_db['seat_status'] == 'free') {
                                $this->pmp_data[$j][$k][PM_STATUS] = PM_STATUS_FREE;
                            } elseif ($seat_db['seat_status'] == 'resp') {
                                $this->pmp_data[$j][$k][PM_STATUS] = PM_STATUS_RESP;
                            } else {
                                $this->pmp_data[$j][$k][PM_STATUS] = PM_STATUS_OCC;
                            }
                        } else {
                            if (!$error) {
                                user_error('seats cache error found, rebuilding cache... PLEASE RELOAD'); 
                                // user_error("seats cache error! seat id: {$seat_c[PM_ID]} ($k,$j) {$seat_c[PM_ZONE]} {$seat_c[PM_CATEGORY]}");
                                // print_r($seat_c);
                                $this->pmp_data = PlaceMapPart::_unser_data($this->pmp_data_orig, $this->pmp_width, $this->pmp_height); 
                                // echo("<pre>");print_r($pmp_data_orig);echo("</pre>");
                                $error = true;
                            }
                        }
                    }
                }
            }
        }
        $this->pmp_expires = $expires;
    }

    function clear_cache ($pmp_id)
    {
        global $_SHOP;

        $query = "update PlaceMapPart set pmp_expires=1 where pmp_id=$pmp_id";
        ShopDB::query($query);
    }

    function find_doubles ($pmz_ident = 0)
    {
        if ($this->zones) {
            for($j = 0;$j < $this->pmp_height;$j++) {
                for($k = 0;$k < $this->pmp_width;$k++) {
                    $seat = $this->pmp_data[$j][$k];
                    if ($seat[PM_ZONE] > 0 and (!$pmz_ident or $seat[PM_ZONE] == $pmz_ident)) {
                        if ($prev = $tmp[$seat[PM_ZONE]][$seat[PM_ROW]][$seat[PM_SEAT]]) {
                            $doubles[$prev[0]][$prev[1]] = true;
                            $doubles[$j][$k] = true;
                        } else {
                            $tmp[$seat[PM_ZONE]][$seat[PM_ROW]][$seat[PM_SEAT]] = array($j, $k);
                        }
                    }
                }
            }
        }
        return $doubles;
    }
}

?>