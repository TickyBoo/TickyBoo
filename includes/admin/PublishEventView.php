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
require_once('classes/Event.php');

class PublishEventView extends AdminView {
    function cat_view (&$data, &$event, $stats, $pmps)
    {
        echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
        echo "<tr><td class='admin_list_title' colspan='2'>{$data['category_name']}</td></tr>";

        if (!$data['category_template'] and !$event['event_template']) {
            $data['category_template'] = '<div class=warning>' . undefined . '</div>'; 
            // $err=TRUE;
        } else if (!$data['category_template'] and $event['event_template']) {
            $data['category_template'] = $event['event_template'];
        }

        if ($data['category_numbering'] != 'none') {
            $data['category_size'] = $stats[$data['category_ident']];
            if (empty($pmps[$data['category_ident']])) {
                $err = true;
                $data['category_pmp_id'] = '<div class=error>' . undefined . '</div>';
            } else if (sizeof($pmps[$data['category_ident']]) > 1) {
                $data['category_pmp_id'] = '<div class=error>' . implode(',', $pmps[$data['category_ident']]) . '</div>';
                $err = true;
            } else {
                $data['category_pmp_id'] = $pmps[$data['category_ident']][0];
            }
        }

        if (!$data['category_size']) {
            $data['category_size'] = '<div class=error>0</div>';
            $err = true;
        }

        if ($data['category_price'] == 0) {
            $data['category_price'] = '<div class=error>0.00</div>';
            $err = true;
        }

        $this->print_field('category_id', $data);
        $this->print_field('category_name', $data);
        $this->print_field('category_price', $data);
        $this->print_field('category_numbering', $data);
        $this->print_field('category_template', $data);
        $this->print_field('category_size', $data);

        if ($data['category_numbering'] != 'none') {
            $this->print_field('category_pm_id', $data);
            $this->print_field('category_pmp_id', $data);
        }

        echo "</table>\n";

        return $err;
    }

    function event_view (&$data)
    {
        $data["event_date"] = formatAdminDate($data["event_date"]);
        $data["event_time"] = formatTime($data["event_time"]);
        $data["event_open"] = formatTime($data["event_open"]);
        $event_id = $data["event_id"];

        if (!$data['event_pm_id']) {
            $agenda = ' - ' . agenda_only;
        }

        echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
        echo "<tr><td colspan='2' class='admin_list_title'>" . $data["event_name"] . "$agenda
        </td></tr>";

        $this->print_field('event_id', $data);
        $this->print_field('event_name', $data);
        $this->print_field('ort_name', $data);
        $this->print_field('event_short_text', $data);
        $this->print_field('event_text', $data);
        $this->print_url('event_url', $data);
        $this->print_field('event_date', $data);
        $this->print_field('event_time', $data);
        $this->print_field('event_open', $data);
        $this->print_field('event_group_name', $data);
        $this->print_field('event_order_limit', $data);
        $this->print_field('event_template', $data);
        $this->view_file('event_image' , $data, $err);
        $this->view_file('event_mp3' , $data, $err, 'mp3');

        echo "</table>\n";
    }

    function confirm_button (&$event, $show_button = true)
    {
        echo "<div class='info'><br>" . pub_confirm_msg;
        if ($show_button) {
            echo "<form action='{$_SERVER['PHP_SELF']}' method='POST'>
                     <input type='hidden' name='action' value='publish'><br>
         <input type='hidden' name='event_id' value='{$_GET['event_id']}'><br>
         <input type='submit' name='confirm' value='".confirm_yes."'>
         <input type='button' name='goback' onclick='location.href=\"view_event.php\"' value='".confirm_no."'>
         </form>";
        }

        echo "</div>";
    }

    function draw ()
    {
        global $_SHOP;
        if ($_POST['confirm'] == confirm_yes and $_POST['event_id'] > 0) {
            if ($event = Event::load($_POST['event_id'], false)) {
                if ($event->publish($stats, $pmps)) {
                    echo "<div class='success'> <b>{$event->event_name}</b> " . pub_success . "</div>\n";
                } else {
                    echo "<div class='err'> <b>{$event->event_name}</b> " . pub_failure . "</div>\n";
                }
            }
            $this->delayedLocation('view_event.php');

        } else

        if ($_GET['event_id'] > 0) {
            if (!$event = Event::load($_GET['event_id'], false) ) {
                return true;
            }
            // dry-run
            $event->publish($stats, $pmps, true);

            $event_d = (array)$event;
            $this->confirm_button($event_d, false);
            $this->event_view($event_d);

            if ($event->event_pm_id and ($event->event_rep == 'sub' or $event->event_rep == 'main,sub')) {
                require_once('classes/PlaceMapCategory.php');
                if ($cats = PlaceMapCategory::loadAll_event($_GET['event_id'])) {
                    foreach($cats as $category) {
                        $category_d = (array)$category;
                        $err = $this->cat_view($category_d, $event_d, $stats, $pmps);
                        $errs = ($err or $errs);
                    }
                } else {
                    $errs = true;
                    echo '<div class=error align=center>' . category . ' ' . undefined . '<br></div>';
                }
            }
            if ($errs) {
                echo "<br><a class='link' href='{$_SERVER['PHP_SELF']}?action=view_pm&pm_id={$event->event_pm_id}'>
                            <div class=error align=center>" . correct_errors_first . "<br></div></a>";
            } else {
                $this->confirm_button($event_d);
            }

        }  else
               return true;
    }
}

?>