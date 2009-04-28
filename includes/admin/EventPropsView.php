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

require_once("admin/EventViewCommon.php");
require_once("classes/ShopDB.php");
require_once("functions/datetime_func.php");
require_once('classes/Event.php');
require_once('admin/EventSubPropsView.php');

function showstr($Text,$len=20) {
  if(strlen($Text)> $len) {
     $Text = substr($Text,0, $len).'&hellip;';
  }
  return $Text;
}

class EventPropsView extends EventViewCommon {

    function event_form (&$data, &$err)
    {
        global $_SHOP;

        echo "<form method='POST' action='{$_SERVER['PHP_SELF']}' enctype='multipart/form-data'>\n";

        $this->form_head(event_add_title);

        $this->print_field_o('event_id', $data);

        $this->print_input('event_name', $data, $err, 30, 100);
        If(!$data['event_id']){
          $this->print_select_group('event_group_id', $data, $err);
          $this->print_select_pm('event_pm_ort_id', $data, $err);
          $this->print_select('event_rep', $data, $err, array('unique', 'main'));
          echo "<input type='hidden' name='action' value='insert'/>\n";
        }else{
          $this->print_field_o('event_group_name', $data, $err);
          $this->print_field('event_ort_name', $data);
          $this->print_field('event_rep', $data);

          echo "<input type='hidden' name='event_id' value='{$data['event_id']}'/>\n";
          echo "<input type='hidden' name='action' value='update'/>\n";
        }
        $this->print_area('event_short_text', $data, $err, 3);
        $this->print_area('event_text', $data, $err);
        $this->print_input('event_url', $data, $err, 30, 100);

        $this->print_date('event_date', $data, $err);
        $this->print_time('event_time', $data, $err);
        $this->print_time('event_open', $data, $err);
  $this->print_time('event_end',$data,$err);

        $this->print_input('event_order_limit', $data, $err, 3, 4);
        $this->print_select_tpl('event_template', $data, $err);

        $this->select_types('event_type', $data, $err);

        $this->print_file('event_image', $data, $err, 'img');
        // $this->print_file('event_ort_image',$data,$err,'img');
        $this->print_file('event_mp3', $data, $err, 'mp3');


        $this->form_foot();

        echo "</form>\n";
        echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}'>" . admin_list . "</a></center>";
    }

    function fill_images()
    {
        $img_pub['pub'] = array(
            "src"   => 'images/grun.png',
            "title" => "Click to Unpublish.",
            "alt"   => "Published",
            "link"  => "view_event.php?action=unpublish&event_id=");

        $img_pub['unpub'] = array(
            "src"   => 'images/rot.png',
            'title' => "Click to publish.",
            'alt'   => "New event",
            'link'  => "view_event.php?action=publish&event_id=");
     
        $img_pub['nosal'] = array(
            "src"   => 'images/grey.png',
            "title" => "Click to republish.",
            "alt"   => "Unpublished",
            "link"  => "view_event.php?action=republish&event_id=");
        
        return $img_pub;
    }
    
    function event_sub_list ($event_main_id, &$alt)
    {
        global $_SHOP;

        if (!$main = Event::load($event_main_id, false)) {
            return;
        }

        $query = "select *
                  from Event left join Ort on event_ort_id=ort_id
                  where event_main_id="._esc(event_main_id)."
                  and event_rep='sub'
                  and event_status!='trash'
                  order by event_date ";

        if (!$res = ShopDB::query($query)) {
            return;
        }

        $img_pub = $this->fill_images();

        while ($row = shopDB::fetch_assoc($res)) {
            $edate = formatAdminDate($row["event_date"], false);
            $etime = formatTime($row["event_time"]);

            echo "<tr class='admin_list_row_$alt'>
                <td class='admin_list_item' bgcolor=white >&nbsp;</td>
                <td class='admin_list_item'><a class='link' alt='{$img_pub[$row['event_status']]['alt']}'
                                                            title='{$img_pub[$row['event_status']]['title']}'
                                                            href='{$img_pub[$row['event_status']]['link']}{$row['event_id']}'>
                   <img border='0' alt='{$img_pub[$row['event_status']]['alt']}'
                                   title='{$img_pub[$row['event_status']]['title']}'
                                   src='{$img_pub[$row['event_status']]['src']}'></a></td>
                <td class='admin_list_item'>$edate $etime</td>
                <td class='admin_list_item'>".showstr($row['ort_name'])."</td>\n";

            echo "<td class='admin_list_item'>
               <a class='link' href='view_event.php?action=edit_sub&event_id={$row['event_id']}'>
                  <img src='images/edit.gif' border='0' alt='" . edit . "' title='" . edit . "'></a>\n";

            if ($row['event_pm_id']) {
                echo "<a class='link' href='{$_SERVER['PHP_SELF']}?action=view_pm&pm_id={$row['event_pm_id']}'>
                         <img src='images/pm.png' border='0' alt='" . place_map . "' title='" . place_map . "'></a>\n";
            }

            if (($row['event_pm_id'] and $row['event_status'] == 'unpub') or
                (!$row['event_pm_id'] and $row['event_status'] != 'pub') or
                ($row["event_status"] == 'nosal')) {
                /*                    $title = "Archive";
                    $alt = "Archive";
                    $src = "images/archive.jpg";
                    $link = "archive_event.php?event_id=$event_id";*/

                echo "<a class='link' target='_blank' href='archive_event.php?event_id={$row['event_id']}'>
                         <img src='images/archive.png' border='0' alt='" . Archive . "' title='" . Archive . "'></a>\n";

                echo "<a class='link' href='javascript:if(confirm(\"" . delete_item . "\")){location.href=\"view_event.php?action=remove&event_id={$row['event_id']}\";}'>
                        <img src='images/trash.png' border='0' alt='" . remove . "' title='" . remove . "'></a>\n";
            }
            echo "</td></tr>\n\n";
            $alt = ($alt + 1) % 2;
        }
    }

    function event_list () {
        global $_SHOP;

        $query = "select * from Event LEFT JOIN Ort ON event_ort_id=ort_id
                  WHERE event_rep!='sub'
                  and event_status!='trash'
                  order by event_date ";

        if (!$res = ShopDB::query($query)) {
            return;
        }

        $alt = 0;
        echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='4'>\n";
        echo "<tr><td class='admin_list_title' colspan='8' align='center'>" . event_title . "</td></tr>\n";

        $img_pub = $this->fill_images();

        $alt = 0;
        while ($row = shopDB::fetch_assoc($res)) {
            $edate = formatAdminDate($row["event_date"], false);
            $etime = formatTime($row["event_time"]);

            echo "<tr class='admin_list_row_$alt'>
            <td width=18 class='admin_list_item'><a class='link' alt='{$img_pub[$row['event_status']]['alt']}'
                                                        title='{$img_pub[$row['event_status']]['title']}'
                                                        href='{$img_pub[$row['event_status']]['link']}{$row['event_id']}'>
               <img border='0' alt='{$img_pub[$row['event_status']]['alt']}'
                               title='{$img_pub[$row['event_status']]['title']}'
                               src='{$img_pub[$row['event_status']]['src']}'></a></td>
            <td class='admin_list_item'>".showstr($row['event_name'],30)."</td>\n";

            if ($row['event_rep'] == 'main') {
                echo "<td colspan=2 class='admin_list_item'><a class='link' title='".add."' alt='".add."' href='view_event.php?action=add_sub&event_main_id={$row['event_id']}'>" . multi . "</a></td>\n";
            } else {
                echo "<td width=120 class='admin_list_item'>$edate $etime</td>\n";
                echo "<td class='admin_list_item'>".showstr($row["ort_name"])."</td> ";
            }

            echo "<td class='admin_list_item' nowrap='nowrap'>
                  <a class='link' href='view_event.php?action=edit&event_id={$row['event_id']}'><img src='images/edit.gif' border='0' alt='" . edit . "' title='" . edit . "'></a>\n";

            if ($row['event_pm_id']) {
                echo "<a class='link' href='{$_SERVER['PHP_SELF']}?action=view_pm&pm_id={$row['event_pm_id']}'><img src='images/pm.png' border='0' alt='" . place_map . "' title='" . place_map . "'></a>\n";
            }

            if (($row['event_pm_id'] and $row['event_status'] == 'unpub') or
                (!$row['event_pm_id'] and $row['event_status'] != 'pub') or
                ($row["event_status"] == 'nosal')) {
                echo "<a class='link' target='_blank' href='archive_event.php?event_id={$row['event_id']}'>
                         <img src='images/archive.png' border='0' alt='" . Archive . "' title='" . Archive . "'></a>\n";
                echo "<a class='link' title='" . delete_item . "' href='javascript:if(confirm(\"" . delete_item . "\")){location.href=\"view_event.php?action=remove&event_id={$row['event_id']}\";}'><img src='images/trash.png' border='0' alt='" . remove . "' title='" . remove . "'></a>\n";
            }
            echo "</td></tr>\n\n";

            $alt = ($alt + 1) % 2;
            if ($row['event_rep'] == 'main') {
                $this->event_sub_list($row['event_id'], $alt);
            }
        }

        echo "</table>\n";
        echo "<br><center><a class='link' href='view_event.php?action=add'>" . add . "</a></center>";
    } 
    // #######################################################
    // #######################################################
    // #######################################################
    function draw () {
        global $_SHOP;

        if (preg_match('/_disc$/', $_REQUEST['action']) or
            preg_match('/_pmp$/', $_REQUEST['action']) or
            preg_match('/_pmz$/', $_REQUEST['action']) or
            preg_match('/_category$/', $_REQUEST['action'])  or
            preg_match('/_pm$/', $_REQUEST['action'])) {
            require_once("admin/PlaceMapView2.php");
            $pmp_view = new PlaceMapView2($this->width);
            if ($pmp_view->draw()) {
                $this->event_list();
            }
        } else
        if (preg_match('/_sub$/', $_REQUEST['action'])) {
            require_once("admin/EventSubPropsView.php");
            $pmp_view = new EventSubPropsView($this->width);
            if ($pmp_view->draw()) {
               $this->event_list();
            }
        } else
        if ($_GET['action'] == 'add') {
            $this->event_form($row, $err, event_add_title);
        } else
        if ($_POST['action'] == 'insert') {
            if (!$this->event_check($_POST, $err)) {
                $this->event_form($_POST, $err, event_add_title);
            } else {
                $this->save_event($_POST, true);
                $this->event_list();
            }
        } else

        if ($_GET['action'] == 'edit' and $_GET['event_id']) {
            $event = Event::load($_GET['event_id'], false);
            $row=(array)$event;
            if (!$row) {
                return $this->event_list();
            }
            $this->event_form($row, $err);
        } else
        if ($_POST['action'] == 'update') {
            if (!$this->event_check($_POST, $err)) {
                $this->event_form($_POST, $err, event_add_title);
            } else {
                $this->save_event($_POST, false);
                $this->event_list();
            }
        } else

        if ($_GET['action'] == 'remove' and $_GET['event_id']) {
            $event = Event::load($_GET['event_id'], false);
            $event->delete();
            $this->event_list();
        } else
        if ($_REQUEST['action'] == 'publish') {
            require_once("admin/PublishEventView.php");
            $obj = new PublishEventView;
            $obj->setwidth($this->width);
            if ($obj->draw()) {$this->event_list();}

        } else
        if ($_REQUEST['action'] == 'unpublish') {
            require_once("admin/UnpublishEventView.php");
            $obj = new UnpublishEventView;
            $obj->setwidth($this->width);
            if ($obj->draw()){ $this->event_list();}
        } else
        if ($_REQUEST['action'] == 'republish') {
            require_once("admin/RepublishEventView.php");
            $obj = new RepublishEventView;
            $obj->setwidth($this->width);
            if ($obj->draw()) {$this->event_list();}
        } else                {
           $this->event_list();
        }
    }
    // #######################################################
    // #######################################################
    // #######################################################
    function event_check (&$data, &$err)
    {
        global $_SHOP;

        if (empty($data['event_name'])) {
            $err['event_name'] = mandatory;
        } 
        // if(empty($data['event_pm_id']))  {$err['event_pm_id']=mandatory;}
        if ((isset($data['event_time-h']) and strlen($data['event_time-h']) > 0) or
                (isset($data['event_time-m']) and strlen($data['event_time-m']) > 0)) {
            $h = $data['event_time-h'];
            $m = $data['event_time-m'];
            if (!is_numeric($h) or $h < 0 or $h > 23) {
                $err['event_time'] = invalid;
            } else if (!is_numeric($m) or $h < 0 or $m > 59) {
                $err['event_time'] = invalid;
            } else {
                $data['event_time'] = "$h:$m";
            }
        }

        if ((isset($data['event_open-h']) and strlen($data['event_open-h']) > 0) or
                (isset($data['event_open-m']) and strlen($data['event_open-m']) > 0)) {
            $h = $data['event_open-h'];
            $m = $data['event_open-m'];
            if (!is_numeric($h) or $h < 0 or $h > 23) {
                $err['event_open'] = invalid;
            } else if (!is_numeric($m) or $h < 0 or $m > 59) {
                $err['event_open'] = invalid;
            } else {
                $data['event_open'] = "$h:$m";
            }

  if((isset($data['event_end-h']) and strlen($data['event_end-h'])>0) or (isset($data['event_end-m']) and strlen($data['event_end-m'])>0)){
    $h=$data['event_end-h'];
    $m=$data['event_end-m'];
    if(!is_numeric($h) or $h<0 or $h>23){$err['event_end']=invalid;}
    else if(!is_numeric($m) or $h<0 or $m>59){$err['event_end']=invalid;}
    else{$data['event_end']="$h:$m";}
        }

        if ((isset($data['event_date-y']) and strlen($data['event_date-y']) > 0) or
                (isset($data['event_date-m']) and strlen($data['event_date-m']) > 0) or
                (isset($data['event_date-d']) and strlen($data['event_date-d']) > 0)) {
            $y = $data['event_date-y'];
            $m = $data['event_date-m'];
            $d = $data['event_date-d'];

            if (!checkdate($m, $d, $y)) {
                $err['event_date'] = invalid;
            } else {
                $data['event_date'] = "$y-$m-$d";
            }
        }

        if ($data['event_rep'] == 'unique') {
            if (!isset($data['event_date'])and !isset($err['event_date'])) {
                $err['event_date'] = mandatory;
            }
            if (!isset($data['event_time'])and !isset($err['event_time'])) {
                $err['event_time'] = mandatory;
            } 
            // if(!isset($data['event_open'])){$err['event_open']=mandatory;}
        }

        If (!$data['event_id']) {
            if ($data['event_rep'] == 'unique' and $data['event_pm_ort_id'] == 'no_pm') {
                $err['event_pm_ort_id'] = mandatory;
            }
            if ($data ['event_pm_ort_id'] != 'no_pm') {
                list($event_pm_id, $event_ort_id) = explode(',', $data ['event_pm_ort_id']);
                $data['event_pm_id'] = $event_pm_id;
                $data['event_ort_id'] = $event_ort_id;
            }

            if ($data['event_rep'] == 'unique') {
                $data['event_rep'] = 'main,sub';
            }
        }

        return empty($err);
    }

    // #######################################################
    function save_event (&$data, $isnew )
    {
        global $_SHOP;

        If ($isnew) {
              $event = new Event;
        } else {
              if ($event = Event::load($data['event_id'], false)) {
               echo "<div class=error>". invalid_event ."<div>";
            }
        }

        $event->_fill($data);

        if (!$event_id = $event->save()) {
             echo "<div class=error>". event_not_updated. shopDB::error() ."<div>";
            return;
        }

        if (!$this->photo_post($_POST, $event_id)) {
            echo "<div class=error>" . img_loading_problem . "<div>";
        }

        if (!$this->photo_post_ort($_POST, $event_id)) {
            echo "<div class=error>" . img_loading_problem . "<div>";
        }

        if (!$this->mp3_post($_POST, $event_id)) {
            echo "<div class=error>" . mp3_loading_problem . "<div>";
        }
    }
}

?>