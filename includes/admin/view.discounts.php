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
require_once("admin/class.adminview.php");

class DiscountView extends AdminView {
  function table ($discount_event_id, $live = false) {
    global $_SHOP;
    if (!is_null($discount_event_id)) {
      $query = "SELECT event_name, ort_name, event_status
                FROM Event left join Ort on ort_id=event_ort_id
                WHERE event_id="._esc((int)$discount_event_id);
      if (!$names = ShopDB::query_one_row($query)) {
          $err = shopDB::error();
          if ($err) user_error($err);
          return;
      }

      $query = "SELECT *
                FROM Discount
                WHERE discount_event_id="._esc((int)$discount_event_id);
      if (!$res = ShopDB::query($query)) {
          user_error(shopDB::error());
          return;
      }
    } else {
      $query = "SELECT *
                FROM Discount
                WHERE discount_event_id is null";
      if (!$res = ShopDB::query($query)) {
          user_error(shopDB::error());
          return;
      }
    }
    $alt = 0;
    echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='2'>\n";
    echo "<tr><td class='admin_list_title' colspan='4' align='left'>". con('discount_title') . "</td>";
    if (!$live) {
      if (!is_null($discount_event_id)) {
        echo "<td align='right'>".$this->show_button("{$_SERVER['PHP_SELF']}?action=add_disc&discount_event_id={$discount_event_id}","add",3)."</td>";
      } else {
        echo "<td align='right'>".$this->show_button("{$_SERVER['PHP_SELF']}?action=add_disc","add",3)."</td>";
      }
    }
    echo "</tr>";

    while ($row = shopDB::fetch_assoc($res)) {
        echo "<tr class='admin_list_row_$alt'>";
     //  echo "<td class='admin_list_item'>{$row['discount_id']}</td>\n";
        echo "<td class='admin_list_item' width=10'>&nbsp;</td>\n";
        echo "<td class='admin_list_item'>{$row['discount_name']}</td>\n";
        if ($row['discount_type'] == 'percent') {
            $type = "{$row['discount_value']}%";
        } else if ($row['discount_type'] == 'fixe') {
            $type = valuta($row['discount_value']);
        }
        echo "<td class='admin_list_item' align='right'>$type</td>\n";
        echo "<td class='admin_list_item' width='30'>{$row['discount_used']}&nbsp;</td>\n";
        echo "<td class='admin_list_item' width='65' align='right'>";
        echo $this->show_button("{$_SERVER['PHP_SELF']}?action=edit_disc&discount_id={$row['discount_id']}&discount_event_id={$discount_event_id}","edit",2);
        echo $this->show_button("javascript:if(confirm(\"".con('delete_item')."\")){location.href=\"{$_SERVER['PHP_SELF']}?action=remove_disc&discount_id={$row['discount_id']}&discount_event_id={$discount_event_id}\";}","remove",2,
                                array('tooltiptext'=>"Delete {$row['discount_name']}?",
                                      'disable'=>$live ));
        echo "</td></tr>";
        $alt = ($alt + 1) % 2;
    }
    echo "</table>\n";
  }

  function form ($data, $err, $title) {
    echo "<form method='POST' action='{$_SERVER['PHP_SELF']}'>\n";
    if (!is_null($data['discount_event_id'])) {
      echo "<input type='hidden' name='discount_event_id' value='{$data['discount_event_id']}' />\n";
    }
    echo "<input type='hidden' name='action' value='save_disc' />\n";
    if ($data['discount_id']) {
      echo "<input type='hidden' name='discount_id' value='{$data['discount_id']}' />\n";
    }

    echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
    echo "<tr><td class='admin_list_title' colspan='2' align='left'>" . $title . "</td></tr>";

    $this->print_field_o('discount_id', $data);

    $this->print_input('discount_name', $data, $err, 30, 50);
    if (is_null($data['discount_event_id'])) {
      $this->print_input('discount_promo', $data, $err, 15, 15);
    }


    $this->print_select ("discount_type", $data, $err, array("fixe", "percent"));

    $this->print_input('discount_value', $data, $err, 6, 5);

    $this->print_field_o('discount_used', $data);

    if ($data['event_pm_id']) {
		  $this->form_foot(2,"{$_SERVER['PHP_SELF']}?action=edit_pm&pm_id={$data['event_pm_id']}");
    } else {
      $this->form_foot(2);
    }
  }

  function draw ($showlist=false) {
    if ($_GET['action'] == 'add_disc') {
        $disc = new Discount(true, is($_GET['discount_event_id'],false));
        $row = (array)$disc;
        $this->form($row, null, con('discount_add_title'));
    } elseif ($_GET['action'] == 'edit_disc') {
        $row = Discount::load($_GET['discount_id']);
        $this->form((array)$row, null, con('discount_update_title'));
    } elseif ($_POST['action'] == 'save_disc') {
      if (!$disc = Discount::load((int)$_POST['discount_id'])) {
         $disc = new Discount(true);
      }
      if ( !$disc->fillPost() || !$disc->saveEx() ) {
        $this->form( $_POST, null , con((isset($_POST['ort_id']))?'discount_update_title':'discount_add_title') );
      } elseif ($showlist) {
         $this->table(null, false);
      } else {
        return true;
      }

    } elseif ($_GET['action'] == 'remove_disc' and $_GET['discount_id'] > 0) {
      $row = Discount::load($_GET['discount_id']);
      $row->delete();
    } elseif ($showlist) {
       $this->table(null, false);
    }
  }
}

?>