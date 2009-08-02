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
require_once('classes/Ort.php');

class OrtView extends AdminView {

    function pm_list ($ort_id)
    {
        $query = "SELECT * FROM PlaceMap2 WHERE pm_ort_id='$ort_id'";
        if (!$res = ShopDB::query($query)) {
            return;
        }
        echo "Please notify me, that i'm showing";
        $alt = 0;
        echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='4'>\n";
        echo "<tr><td class='admin_list_title' colspan='7' align='center'>" . pm_title . "</td></tr>\n";

        while ($row = shopDB::fetch_assoc($res)) {
            echo "<tr class='admin_list_row_$alt'>";
            if ($row['pm_lock']) {
                echo "<td class='admin_list_item'><img src='images/lock.png' alt='" . locked . "' title='" . locked . "'></td>";
            } else {
                echo "<td class='admin_list_item'></td>";
            }
            echo "<td class='admin_list_item'>{$row['pm_id']}</td>\n";
            echo "<td class='admin_list_item' width='80%'>{$row['pm_name']}</td>\n";
            echo "<td class='admin_list_item'><a class='link' href='{$_SERVER['PHP_SELF']}?action=view&pm_id={$row['pm_id']}&pm_ort_id=$ort_id'><img src='images/view.png' border='0' alt='" . view . "' title='" . view . "'></a></td>\n";
            if ($row['pm_lock']) {
                echo "<td class='admin_list_item'><a class='link' href='{$_SERVER['PHP_SELF']}?action=unlock&pm_id={$row['pm_id']}&pm_ort_id=$ort_id'><img src='images/unlock.png' border='0' alt='" . unlock . "' title='" . unlock . "'></a></td>\n";
                echo "<td class='admin_list_item'></td>\n";
            } else {
                echo "<td class='admin_list_item'><a class='link' href='{$_SERVER['PHP_SELF']}?action=edit&pm_id={$row['pm_id']}&pm_ort_id=$ort_id'><img src='images/edit.gif' border='0' alt='" . edit . "' title='" . edit . "'></a></td>\n";
                echo "<td class='admin_list_item'><a class='link' href='javascript:if(confirm(\"" . delete_item . "\")){location.href=\"{$_SERVER['PHP_SELF']}?action=remove&pm_id={$row['pm_id']}&pm_ort_id=$ort_id\";}'><img src='images/trash.png' border='0' alt='" . remove . "' title='" . remove . "'></a></td>\n";
            }
            echo "<td class='admin_list_item'><a class='link' href='{$_SERVER['PHP_SELF']}?action=copy&pm_id={$row['pm_id']}&pm_ort_id=$ort_id'><img src='images/copy.png' border='0' alt='" . copy . "' title='" . copy . "'></a></td>\n";
            echo "</tr>";
            $alt = ($alt + 1) % 2;
        }
        echo "</table>\n";

        echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}?action=add&pm_ort_id=$ort_id'>" . add . "</a></center>";
    }

    function ort_form (&$data, &$err, $title)
    {
        global $_SHOP;

        echo "<form method='POST' action='{$_SERVER['PHP_SELF']}' enctype='multipart/form-data'>\n";
        echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
        echo "<tr><td class='admin_list_title' colspan='2'>" . $title . "</td></tr>";
        $this->print_field_o('ort_id', $data, $err);
        $this->print_input('ort_name', $data, $err, 25, 100);
        $this->print_input('ort_address', $data, $err, 25, 75);
        $this->print_input('ort_address1', $data, $err, 25, 75);
        $this->print_input('ort_zip', $data, $err, 10, 20);
        $this->print_input('ort_city', $data, $err, 25, 50);
        $this->print_input('ort_state', $data, $err, 25, 50);
        echo "<tr><td class='admin_name'>" . ort_country . "</td><td class='admin_value'>";
        $this->print_countrylist('ort_country', $data['ort_country'], $err);
        echo "</td></tr>";
        $this->print_input('ort_phone', $data, $err, 25, 50);
        $this->print_input('ort_fax', $data, $err, 25, 50);
        // $this->print_input('ort_plan_nr',$data, $err,6,100);
        $this->print_input('ort_url', $data, $err, 50, 100);
        $this->print_area('ort_pm', $data, $err, 4, 49);
        $this->print_file('ort_image', $data, $err);

        if ($data['ort_id']) {
            echo "<input type='hidden' name='ort_id' value='{$data['ort_id']}'/>\n";
            echo "<input type='hidden' name='action' value='update'/>\n";
        } else {
            echo "<input type='hidden' name='action' value='insert'/>\n";
        }

        echo "<tr><td align='center' class='admin_value' colspan='2'>
              <input type='submit' name='submit' value='" . save . "'>
              <input type='reset' name='reset' value='" . res . "'></td></tr>";
        echo "</table>\n";

        if  (($data['ort_id'])) {
            require_once('admin/PlaceMapView2.php');
            echo "<br>";
            PlaceMapView2::pm_list($data['ort_id']);
        }

        echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}'>" . admin_list . "</a></center>";
    }

    function ort_check (&$data, &$err)
    {
        if (empty($data['ort_name'])) {
            $err['ort_name'] = mandatory;
        }
        if (empty($data['ort_city'])) {
            $err['ort_city'] = mandatory;
        }

        return empty($err);
    }

    function ort_list ()
    {
        global $_SHOP;

        $query = "select * from Ort ";
        if (!$res = ShopDB::query($query)) {
            return;
        }

        $alt = 0;
        echo "<table class='admin_list' width='$this->width' cellpadding='4' cellspacing='1'>\n";
        echo "<tr><td class='admin_list_title' colspan='5' align='center'>" . ort_title . "</td></tr>\n";

        while ($row = shopDB::fetch_assoc($res)) {
            echo "<tr class='admin_list_row_$alt'>";
//            echo "<td class='admin_list_item'>{$row['ort_id']}</td>\n";
            echo "<td class='admin_list_item' width='50%'>{$row['ort_name']}</td>\n";
            echo "<td class='admin_list_item'>{$row['ort_city']}</td>\n";
//            echo "<td class='admin_list_item' width='10'><a class='link' href='{$_SERVER['PHP_SELF']}?action=view&ort_id={$row['ort_id']}'><img src='images/view.png' border='0' alt='" . view . "' title='" . view . "'></a></td>\n";
            echo "<td class='admin_list_item'width='35'><a class='link' href='{$_SERVER['PHP_SELF']}?action=edit&ort_id={$row['ort_id']}'><img src='images/edit.gif' border='0' alt='" . edit . "' title='" . edit . "'></a>";
            echo "<a class='link' href='javascript:if(confirm(\"" . delete_item . "\")){location.href=\"{$_SERVER['PHP_SELF']}?action=remove&ort_id={$row['ort_id']}\";}'><img src='images/trash.png' border='0' alt='" . remove . "' title='" . remove . "'></a></td>\n";
            echo "</tr>";
            $alt = ($alt + 1) % 2;
        }
        echo "</table>\n";
        echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}?action=add'>" . add . "</a></center>";
        // echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}?action=import'>".import."</a></center>";
    }

    function shared_ort_list () {
    }

    function photo_post ($data, $ort_id)
    {
        return $this->file_post($data, $ort_id, 'Ort', 'ort', '_image');
    }

    function draw ()
    {
        global $_SHOP;
      //  echo $_POST['action'];
        
        if (preg_match('/_pm$/', $_REQUEST['action']) or preg_match('/_pmz$/', $_REQUEST['action']) or
            preg_match('/_pmp$/', $_REQUEST['action']) or preg_match('/_category$/', $_REQUEST['action'])){
            require_once('admin/PlaceMapView2.php');
            $pm_view = new PlaceMapView2($this->width);
            if ($pm_view->draw()) {
                if ($ort = ort::load($_REQUEST['pm_ort_id'])) {
                    $row = (array)$ort;
                    $this->ort_form($row, $err, ort_update_title);
                }
            }
        } else
        if ($_POST['action'] == 'insert') {
            if (!$this->ort_check($_POST, $err)) {
                $this->ort_form($_POST, $err, ort_add_title);
            } else {
                $ort = new ort;
                $ort->_fill($_POST);
                if (!$ort_id = $ort->save()) {
                   echo "can't save";
                    return 0;
                }
                if (!$this->photo_post($_POST, $ort_id)) {
                    echo "<div class=error>" . img_loading_problem . "</div>";
                }

                $this->ort_list();
            }
        } else
        if ($_POST['action'] == 'update') {
            if (!$this->ort_check($_POST, $err)) {
                $this->ort_form($_POST, $err, ort_update_title);
            } else {
                $ort = new ort;
                $ort->_fill($_POST);
                if (!$ort_id = $ort->save()) {
                    echo "error: ".$_SHOP->db_error;
                    return 0;
                }
                if (!$this->photo_post($_POST, $_POST['ort_id'])) {
                    echo "<div class=error>" . img_loading_problem . "</div>";
                }

                $this->ort_list();
            }
        } else
        if ($_GET['action'] == 'add') {
            $this->ort_form($row, $err, ort_add_title);
        } else
        if ($_GET['action'] == 'remove' and $_GET['ort_id'] > 0) {
            // check if not in use
            $query = "SELECT event_name FROM Event Where event_ort_id="._ESC($_GET['ort_id']);
            if (!$res = ShopDB::query($query)) {
                return 0;
            } while ($name = shopDB::fetch_array($res)) {
                if (!$used) {
                    echo "<div class=error>" . in_use . "</div>";
                }
                echo "<div class=error>" . $name['event_name'] . "</div>";
                $used = true;
            }
            if ($used) {
                return;
            }
            $ort = Ort::load($_GET['ort_id']);
            $ort->delete();
            $this->ort_list();
        } else
        if ($_GET['action'] == 'import') {
            $this->shared_ort_list();
        } else
        if ($_GET['ort_id']){
            if ($ort = ort::load($_REQUEST['ort_id'])) {
               $row = (array)$ort;
               $this->ort_form($row, $err, ort_update_title);
            }
        } else {
            $this->ort_list();
        }
    }
}

?>