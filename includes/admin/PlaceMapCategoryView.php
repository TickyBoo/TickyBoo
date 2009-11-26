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

class PlaceMapCategoryView extends AdminView {
    function __construct($width = 50) {
        $this->width = $width;
    }

    function category_form (&$data, &$err) {
      echo "
  <script  type='text/javascript'>
      function getElement(id){
        if(document.all) {return document.all(id);}
        if(document.getElementById) {return document.getElementById(id);}
  		}
      function ShowSize(a){
        if(tr1=getElement('category_size')){
          if (a) {
            tr1.style.display='';
          } else {
            tr1.style.display='none';
          }
        }
      }
  </script>";

        echo "<form action='{$_SERVER['PHP_SELF']}' method='post'>";

        $this->form_head(categories);

        $this->print_field_o('category_id', $data);
        $this->print_input('category_name', $data, $err, 30, 100);
        if (!$data['event_status'] or ($data['event_status'] == 'unpub')) {
            $this->print_input('category_price', $data, $err, 6, 6);
        } else {
            $this->print_field('category_price', $data);
        }
        $this->print_select_tpl('category_template', $data, $err);
        $this->print_color('category_color', $data, $err);
        if (!$data['event_status'] or ($data['event_status'] == 'unpub')) {
          $this->print_select_num('category_numbering', $data, $err, array('none', 'rows', 'seat', 'both'),'');
          if ($data['category_numbering'] == 'none') {
            $this->print_input('category_size', $data, $err, 6, 6);
          } else {
            $this->print_field('category_size', $data);
          }

        } else {
          $this->print_field('category_numbering', $data);
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

        echo "<br><center><a href='{$_SERVER['PHP_SELF']}?action=view_pm&pm_id={$data['pm_id']}' class=link>".con('place_map')."</a></center>";
    }

    function category_check ($data) {
        return true;
    }

    function category_list ($pm_id, $live = false) {
        global $_SHOP;

        $alt = 0;
//        echo $live;
        echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='4'>\n";
        echo "<tr><td class='admin_list_title' colspan='4' align='left'>" .con('categories'). "</td>\n";
        if (!$live) {
          echo "<td colspan=5 align='right'><a class='link' href='{$_SERVER['PHP_SELF']}?action=add_category&pm_id=$pm_id'>".con('add')."</a></td>";
        }
        echo "</tr>";
        if ($cats = PlaceMapCategory::LoadAll($pm_id)){
          foreach($cats as  $category) {
            echo "<tr class='admin_list_row_$alt'>";
            echo "<td class='admin_list_item' width=10 bgcolor='{$category->category_color}'>&nbsp;</td>\n";
            echo "<td class='admin_list_item' width='50%'>{$category->category_name}</td>\n";
            echo "<td class='admin_list_item'>{$category->category_size} ".con('cat_at')." {$category->category_price} </td>\n";
            echo "<td class='admin_list_item'>" . con($category->category_numbering) . " </td>\n";

            echo "<td class='admin_list_item' width=60 align=right>";
            if (!$mine) {
                echo "<a class='link' href='{$_SERVER['PHP_SELF']}?action=edit_category&pm_id=$pm_id&category_id={$category->category_id}'>
                <img src='images/edit.gif' border='0' alt='".con('edit')."' title='".con('edit')."'></a>\n";
                if (!$live) {
                    echo "<a class='link' href='javascript:if(confirm(\"".con('delete_item')."\")){location.href=\"{$_SERVER['PHP_SELF']}?action=remove_category&pm_id=$pm_id&category_id={$category->category_id}\";}'><img src='images/trash.png' border='0' alt='".con('remove')."' title='".con('remove')."'></a>\n";
                }
            } else {
               echo "<a class='link' href='{$_SERVER['PHP_SELF']}?action=view_category&pm_id=$pm_id&category_id={$category->category_id}'><img src='images/view.png' border='0' alt='".con('view')."' title='".con('view')."'></a>\n";
            }
            echo'</td></tr>';
            $alt = ($alt + 1) % 2;
          }
        }

        echo '</table>';
    }


    function draw () {
        if ($_GET['action'] == 'add_category' and $_GET['pm_id'] > 0) {
            $this->category_form($_GET, $err);
        } elseif ($_POST['action'] == 'insert_category' and $_POST['pm_id'] > 0) {
            if (!$this->category_check($_POST, $err)) {
                $this->category_form($_POST, $err);
            } else {
              $category = new PlaceMapCategory;
              $category->fillPost();
              $category->category_event_id = $pm->pm_event_id;
              $category->category_pm_id    = $_POST['pm_id'];

              if (!$category->save()) {
                print_r($category->errors());
              }

              return true;
            }
        } elseif ($_GET['action'] == 'edit_category' and $_GET['category_id'] > 0) {
            $category = PlaceMapCategory::load($_GET['category_id']);
            $data = (array)$category;
            $data['pm_id'] = $category->category_pm_id;

            $this->category_form($data, $err);
        } elseif ($_POST['action'] == 'update_category' and $_POST['category_id'] > 0) {
            if (!$this->category_check($_POST, $err)) {
                $this->category_form($_POST, $err);
            } else {
                $category = PlaceMapCategory::load($_POST['category_id']);
                $category->fillPost();
                $category->save();
                return true;
            }
        } elseif ($_GET['action'] == 'remove_category' and $_GET['category_id'] > 0) {
            $category = PlaceMapCategory::load($_GET['category_id']);
            PlaceMapCategory::delete($category->category_id);
            return true;
        } elseif ($_POST['action'] == 'resize_category' and $_POST['category_id'] > 0) {
            $category = PlaceMapCategory::load($_POST['category_id']);

            if (!$category->change_size((int)$_POST['category_new_size'])) {
                $data = (array)$category;
                $err['category_new_size'] = con('error');
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

    function print_select_num ($name, &$data, &$err, $opt)
    {
        // $val=array('both','rows','none');
        if ($data[$name]) {
            $sel[$data[$name]] = " selected ";
        } else {
            $sel['none'] = " selected ";
        }

        echo "<tr><td class='admin_name'  width='40%'>" . con($name) . "</td>
  <td class='admin_value'>
   <select name='$name' >\n";

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
}

?>