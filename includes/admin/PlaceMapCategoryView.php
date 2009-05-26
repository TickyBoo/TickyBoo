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
require_once("classes/PlaceMapCategory.php");

class PlaceMapCategoryView extends AdminView {
    function __construct($width = 50)
    {
        $this->width = $width;
    }

    function category_view ($cat) {
        $this->form_head(category_view_title);

        $data=(array)$cat;

        $this->print_field('category_id',$data );
        $this->print_field('category_name',$data);
        $this->print_field_o('event_name',$data);
        $this->print_field('category_ident',$data);
        $this->print_field('category_price',$data);
        $this->print_field('category_template',$data);
        $this->print_color('category_color',$data);
        $this->print_field('category_numbering',$data);
        $this->print_field('category_size',$data);
        $this->print_field('category_max',$data);
        $this->print_field('category_actual',$data);
        $this->print_field('category_data',$data);

        echo "</table><br><center>";
        echo "<a class='link' href='{$_SERVER['PHP_SELF']}?action=view_pm&pm_id={$cat->category_pm_id}'>".place_map."</a>";
        echo "</center>";
    }
    
    function category_form (&$data, &$err)
    {
        echo "<form action='{$_SERVER['PHP_SELF']}' method='post'>";

        $this->form_head(category_update_title);

        $this->print_field_o('category_id', $data);
        $this->print_field_o('event_name', $data);

        $this->print_input('category_name', $data, $err, 30, 100);
        if (!$data['category_status'] or ($data['category_status'] == 'unpub')) {
            $this->print_input('category_price', $data, $err, 6, 6);
        } else {
            $this->print_field('category_price', $data);
        }
        $this->print_select_tpl('category_template', $data, $err);
        $this->print_select_color('category_color', $data, $err);
        if (!$data['category_status'] or ($data['category_status'] == 'unpub')) {
            $this->print_select_num('category_numbering', $data, $err, array('both', 'rows', 'seat', 'none'));
        } else {
            $this->print_field('category_numbering', $data);
        }

        if (!$data['category_status'] or ($data['category_status'] == 'unpub')) {
            $this->print_input('category_size', $data, $err, 6, 6);
        } else {
            $this->print_field('category_size', $data);
        }
        if ($data['category_id']) {
            $sold = $this->Cat_Stat($data['category_id']);
            $this->print_field('number_taken', $sold);
        }
        $this->print_area('category_data', $data, $err, 3, 40);

        $this->form_foot();

        if ($data['category_id']) {
            echo "<input type=hidden name=category_id value={$data['category_id']}>";
            echo "<input type=hidden name=action value=update_category>";
        } else {
            echo "<input type=hidden name=action value=insert_category>";
        }
        echo "<input type=hidden name=pm_id value={$data['pm_id']}>";
        echo "</form>";

        if ($data['category_status'] == 'nosal' and $data['category_numbering'] == 'none') {
            echo "<br>";
            echo "<form action='{$_SERVER['PHP_SELF']}' method=post>";
            $this->form_head(category_new_size_title);
            $this->print_input('category_new_size', $data, $err, 6, 6);
            $this->form_foot();
            echo "<input type=hidden name=pm_id value={$data['pm_id']}>";
            echo "<input type=hidden name=category_id value={$data['category_id']}>";
            echo "<input type=hidden name=action value=resize_category>";
            echo "</form>";
        }

        echo "<br><center><a href='{$_SERVER['PHP_SELF']}?action=view_pm&pm_id={$data['pm_id']}' class=link>" . place_map . "</a></center>";
    }

    function category_check ($data)
    {
        return true;
    }

    function category_list ($pm_id, $live = false)
    {
        global $_SHOP;

        require_once('classes/PlaceMap.php');
        if ($pm = PlaceMap::load($pm_id)) {
            $mine = true;
        }

        $alt = 0;
//        echo $live;
        echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='4'>\n";
        echo "<tr><td class='admin_list_title' colspan='5' align='center'>" . categories . "</td></tr>\n";

        $query = "select * from Category LEFT JOIN Color ON category_color=color_id where category_pm_id="._esc($pm_id);
        if (!$res = ShopDB::query($query)) {
            return;
        } while ($category = shopDB::fetch_object($res)) {
            echo "<tr class='admin_list_row_$alt'>";
            echo "<td class='admin_list_item' width=10 bgcolor='{$category->color_code}'>&nbsp;</td>\n";
            echo "<td class='admin_list_item' width='50%'>{$category->category_name} ({$category->category_status})</td>\n";
            echo "<td class='admin_list_item'>{$category->category_size} &agrave; {$category->category_price} </td>\n";
            echo "<td class='admin_list_item' >" . con($category->category_numbering) . " </td>\n";

            echo "<td class='admin_list_item' width=40 align=right>";
            if ($mine) {
                echo "<a class='link' href='{$_SERVER['PHP_SELF']}?action=edit_category&pm_id=$pm_id&category_id={$category->category_id}'><img src='images/edit.gif' border='0' alt='" . edit . "' title='" . edit . "'></a>\n";
                if (!$live) {
                    echo "<a class='link' href='javascript:if(confirm(\"" . delete_item . "\")){location.href=\"{$_SERVER['PHP_SELF']}?action=remove_category&pm_id=$pm_id&category_id={$category->category_id}\";}'><img src='images/trash.png' border='0' alt='" . remove . "' title='" . remove . "'></a>\n";
                }
            } else {
               echo "<a class='link' href='{$_SERVER['PHP_SELF']}?action=view_category&pm_id=$pm_id&category_id={$category->category_id}'><img src='images/view.png' border='0' alt='" . view . "' title='" . view . "'></a>\n";
            }
            echo'</td></tr>';
            $alt = ($alt + 1) % 2;
        }

        if ($mine and !$live) {
            echo "<tr><td colspan=5 align=center><a class='link' href='{$_SERVER['PHP_SELF']}?action=add_category&pm_id=$pm_id'>" . add . "</a></td></tr>";
        }

        echo '</table>';
    }


    function draw ()
    {
        global $_SHOP;
        if ($_GET['action'] == 'add_category' and $_GET['pm_id'] > 0) {
            require_once('classes/PlaceMap.php');
            if (!$pm = PlaceMap::load($_GET['pm_id'])) {
                return;
            }

            $this->category_form($_GET, $err);
        } else
        if ($_POST['action'] == 'insert_category' and $_POST['pm_id'] > 0) {
            require_once('classes/PlaceMap.php');
            if (!$pm = PlaceMap::load($_POST['pm_id'])) {
                return;
            }
            if (!$this->category_check($_POST, $err)) {
                $this->category_form($_POST, $err);
            } else {
                $category = new PlaceMapCategory( $_POST['pm_id'],
                                                  $_POST['category_name'],
                                                  $_POST['category_price'],
                                                  $_POST['category_template'],

                                                  $_POST['category_color'],
                                                  $_POST['category_numbering'],
                                                  $_POST['category_size'],
                                                  $pm->pm_event_id);
                $category->category_data = $_POST['category_data'];

                $category->save();

                return true;
            }
        }elseif ($_GET['action'] == 'view_category' and $_GET['category_id'] > 0) {
            $category = PlaceMapCategory::load_full($_GET['category_id']);
            $this->category_view($category);
        } elseif ($_GET['action'] == 'edit_category' and $_GET['category_id'] > 0) {
            $category = PlaceMapCategory::load($_GET['category_id']);
            $data = (array)$category;
            $data['pm_id'] = $category->category_pm_id;

            $this->category_form($data, $err);
        } else if ($_POST['action'] == 'update_category' and $_POST['category_id'] > 0) {
            if (!$this->category_check($_POST, $err)) {
                $this->category_form($_POST, $err);
            } else {
                $category = PlaceMapCategory::load($_POST['category_id']);
                foreach($_POST as $k => $v) {
                    $category->$k = $v;
                }
                $category->category_pm_id = $_POST['pm_id'];
                $category->save();

                return true;
            }
        }elseif ($_GET['action'] == 'remove_category' and $_GET['category_id'] > 0) {
            $category = PlaceMapCategory::load($_GET['category_id']);

            PlaceMapCategory::delete($category->category_id);

            return true;
        } else if ($_POST['action'] == 'resize_category' and $_POST['category_id'] > 0) {
            $category = PlaceMapCategory::load($_POST['category_id']);

            if (!$category->change_size((int)$_POST['category_new_size'])) {
                $data = (array)$category;
                $err['category_new_size'] = error;
                $data['category_new_size'] = $_POST['category_new_size'];
                $this->category_form($data, $err);
            } else {
                $data = (array)$category;
                $this->category_form($data, $err);
            }
        }
    }
    // ################# petits fonctions speciales ##################
    function print_select_tpl ($name, &$data, &$err)
    {
        global $_SHOP;

        $query = "SELECT template_name FROM Template WHERE template_type='pdf2' ORDER BY template_name";
        if (!$res = ShopDB::query($query)) {
            user_error(shopDB::error());
            return false;
        }

        $sel[$data[$name]] = " selected ";

        echo "<tr><td class='admin_name'  width='40%'>" . con($name) . "</td>
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
    function print_color ($name, &$data)
    {
        if ($data[$name] > 0) {
            $query = "SELECT color_code FROM Color WHERE color_id="._esc($data[$name]);
            if (!$res = ShopDB::query_one_row($query)) {
                return false;
            }

            $value = $res[0];
            $st = "style=background-color:$value;";
        } else {
            $field = no_color;
        }
        echo "<tr><td class='admin_name' width='40%'>" . con($name) . "</td>
    <td class='admin_value'>
    <table width='40' $st><tr><td width='40'>$field&nbsp;</td></tr></table>
    </td></tr>\n";
    }

    function print_select_color ($name, &$data, &$err)
    {
        $query = "SELECT color_id,color_code FROM Color";
        if (!$res = ShopDB::query($query)) {
            return false;
        }
        $sel[0] = "";
        if (isset($data[$name])) {
            $sel[$data[$name]] = " checked";
        } else {
            $sel[0] = " checked";
        }
        echo "<tr><td class='admin_name'  width='40%'>" . con($name) . "</td>
  <td class='admin_value'><table border=1><tr>
  <td align='center'><input type=radio name='$name' value='0' " . $sel[0] . " >X</td>\n";

        while ($v = shopDB::fetch_assoc($res)) {
            $value = $v['color_id'];
            If (!isset($sel[$v['color_id']])) {$sel[$v['color_id']] = "";}
            echo "<td width='10' height='12' style='background-color:{$v['color_code']};'>
          <input type=radio radio name='$name' value='$value' " . $sel[$v['color_id']] . ">
          <img src='images/dot.gif' width='10' height='12' ></td>";
        }

        echo "</tr></table><span class='err'>{$err[$name]}</span>
  </td></tr>\n";
    }
    function print_select_num ($name, &$data, &$err, $opt)
    {
        // $val=array('both','rows','none');
        if ($data[$name]) {
            $sel[$data[$name]] = " selected ";
        } else {
            $sel['both'] = " selected ";
        }

        echo "<tr><td class='admin_name'  width='40%'>" . con($name) . "</td>
  <td class='admin_value'>
   <select name='$name'>\n";

        foreach($opt as $v) {
            echo "<option value='$v'{$sel[$v]}>" . con($name . "_" . $v) . "</option>\n";
        }

        echo "</select><span class='err'>{$err[$name]}</span>
  </td></tr>\n";
    }

    function Cat_Stat($cat_id)
    {
        if (isset($cat_id)) {
            $query = "SELECT * FROM Category_stat WHERE cs_category_id="._esc($cat_id);
            if ($stat = ShopDB::query_one_row($query)) {
                $sold['number_taken'] = $stat['cs_total'] - $stat['cs_free'];
                return $sold;
            }
        }
    }

    function _fill ($data)
    {
        foreach($data as $k => $v) {
            $this->$k = $v;
        }
    }
}

?>