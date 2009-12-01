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
  function table ($pm_id, $live = false) {
    $alt = 0;
    echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='4'>\n";
    echo "<tr><td class='admin_list_title' colspan='4' align='left'>" .con('categories'). "</td>\n";
    if (!$live) {
      echo "<td colspan=5 align='right'><a class='link' href='{$_SERVER['PHP_SELF']}?action=add_category&pm_id=$pm_id'>
              <img src='images/add.png' border='0' alt='".con('add')."' title='".con('add')."'></a></td>";
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
        echo "<a class='link' href='{$_SERVER['PHP_SELF']}?action=edit_category&pm_id=$pm_id&category_id={$category->category_id}'>
        <img src='images/edit.gif' border='0' alt='".con('edit')."' title='".con('edit')."'></a>\n";
        if (!$live) {
            echo "<a class='link' href='javascript:if(confirm(\"".con('delete_item')."\")){location.href=\"{$_SERVER['PHP_SELF']}?action=remove_category&pm_id=$pm_id&category_id={$category->category_id}\";}'>
                    <img src='images/trash.png' border='0' alt='".con('remove')."' title='".con('remove')."'></a>\n";
        }
        echo'</td></tr>';
        $alt = ($alt + 1) % 2;
      }
    }
    echo '</table>';
  }

  function form ($data, $err) {
    echo "<form action='{$_SERVER['PHP_SELF']}' method='post'>";
    echo "<input type=hidden name=action value=save_category>";
    if ($data['category_id']) {
       echo "<input type=hidden name=category_id value={$data['category_id']}>";
    }
    echo "<input type=hidden name=pm_id value={$_REQUEST['pm_id']}>";

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
      $this->print_select('category_numbering', $data, $err, array('none', 'rows', 'seat', 'both'),'');
      $script = " ";
      $this->addJQuery($script);
      $this->print_input('category_size', $data, $err, 6, 6);
    } else {
      $this->print_field('category_numbering', $data);
      $this->print_field('category_size', $data);
    }

    if ($data['category_id']) {
      $taken = $data['cs_total'] - $data['cs_free'];
      $this->print_field('number_taken', $taken);
    }
    $this->print_area('category_data', $data, $err, 3, 40);

    $this->form_foot();

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

    echo "<br><center><a href='{$_SERVER['PHP_SELF']}?action=edit_pm&pm_id={$data['pm_id']}' class=link>".con('place_map')."</a></center>";
  }


  function draw () {
    if ($_GET['action'] == 'add_category' and $_GET['pm_id'] > 0) {
      $pmc = PlaceMapCategory::load(0);
      $this->form((Array)$pmc, null);
    } elseif ($_GET['action'] == 'edit_category' and $_GET['category_id'] > 0) {
      $category = PlaceMapCategory::load((int)$_GET['category_id']);
      $data = (array)$category;
      $data['pm_id'] = $category->category_pm_id;
      $this->form($data, null);
    } elseif ($_POST['action'] == 'save_category' and $_POST['pm_id'] > 0) {
      $pmc = PlaceMapCategory::load((int)$_POST['category_id']);
      $_POST['category_pm_id'] =$_POST['pm_id'];
      if (!$pmc->fillPost() || !$pmc->save()) {
        $this->form($_POST, null);
      } else {
        return true;
      }

    } elseif ($_GET['action'] == 'remove_category' and $_GET['category_id'] > 0) {
      PlaceMapCategory::delete($_GET['category_id']);
      return true;
    } elseif ($_POST['action'] == 'resize_category' and $_POST['category_id'] > 0) {
      $category = PlaceMapCategory::load((int)$_POST['category_id']);

      if (!$category->change_size((int)$_POST['category_new_size'])) {
        $data = (array)$category;
        addError('category_new_size', 'error');
        $data['category_new_size'] = $_POST['category_new_size'];
        $this->form($data, null);
      } else {
        $data = (array)$category;
        $this->form($data, null);
      }
    }
  }

  // ################# petits fonctions speciales ##################
  function print_select_tpl ($name, &$data, &$err) {
    $query = "SELECT template_name
              FROM Template
              WHERE template_type='pdf2'
              ORDER BY template_name";
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

    echo "</select>".printMsg($name, $err)."</td></tr>\n";
  }

}

?>