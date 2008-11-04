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
require_once("classes/Event.php");
require_once("functions/datetime_func.php");

class UnpublishEventView extends AdminView {
    function cat_view (&$data)
    {
        echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
        echo "<tr><td class='admin_list_title' colspan='2'>{$data['category_name']}</td></tr>";

        $this->print_field('category_id', $data);
        $this->print_field('category_name', $data);
        $this->print_field('category_price', $data);
        $this->print_field('category_numbering', $data);
        if ($data['category_numbering'] == 'none') {
            $this->print_field('category_size', $data);
        } else {
            $this->print_field('category_pm_id', $data);
        }

        echo "</table>\n";
    }

    function event_view (&$data)
    {
        $data["event_date"] = formatAdminDate($data["event_date"]);
        $data["event_time"] = formatTime($data["event_time"]);
        $data["event_open"] = formatTime($data["event_open"]);
        $event_id = $data["event_id"];

        echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
        echo "<tr><td colspan='2' class='admin_list_title'>" . $data["event_name"] . "
        </td></tr>";

        $this->print_field('event_id', $data);
        $this->print_field('event_name', $data);
        $this->print_field('ort_name', $data);
        $this->print_field('event_short_text', $data);
        $this->print_field('event_text', $data);
        $this->print_field('event_url', $data);
        $this->print_field('event_date', $data);
        $this->print_field('event_time', $data);
        $this->print_field('event_open', $data);

        echo "</table>\n";
    }

    function confirm_button (&$event, $show_button = true)
    {
        echo "<div class='info'><br>" . nosale_confirm_msg;
        if ($show_button) {
            echo "<form action='{$_SERVER['PHP_SELF']}' method='POST'>";
            if ($event['event_rep'] == 'main') {
                echo "<input type=checkbox name=also_sub id=also_sub style='border:0px;' value=1><label for='also_sub'> " . also_sub . "</label></br>";
            }
            echo "
                     <input type='hidden' name='action' value='unpublish'><br>
                     <input type='hidden' name='event_id' value='{$event['event_id']}'><br>
                     <input type='submit' name='confirm' value='YES'>
                     <input type='button' name='goback' onclick='location.href=\"view_event.php\"' value='".confirm_no."'>

                   </form>";
        }
        echo "<br></div>";
    }

    function draw ()
    {
        global $_SHOP;
        if (isset($_POST['confirm']) and $_POST['confirm'] == 'YES' and $_POST['event_id'] > 0) {
            if ($event = Event::load($_POST['event_id']) and $event->event_organizer_id == $_SHOP->organizer_id) {
                if ($event->stop_sales()) {
                    echo "<div class='success'> <b>'{$event->event_name}'</b> " . stop_success . "</div>\n";
                } else {
                    echo "<div class='err'> <b>'{$event->event_name}'</b> " . stop_failure . "</div>\n";
                }

                if ($event->event_rep == 'main' and $_POST['also_sub'] and $subs = Event::load_all_sub($event->event_id)) {
                    foreach($subs as $sub) {
                        $date = formatAdminDate($sub->event_date);

                        if ($sub->event_status == 'pub') {
                            if ($sub->stop_sales()) {
                                echo "<div class='success'> <b>'$date'</b> " . stop_success . "</div>\n";
                            } else {
                                echo "<div class='err'> <b>'$date'</b> " . stop_failure . "</div>\n";
                            }
                        }
                    }
                }

                $this->delayedLocation('view_event.php');

            }
        } else
        if ($_GET['event_id'] > 0) {
            if (!$event = Event::load($_GET['event_id'], false) or
                    $event->event_organizer_id != $_SHOP->organizer_id) {
               return true;
            }

            $event_d = (array)$event;

            $this->confirm_button($event_d, false);
            $this->event_view($event_d);

            require_once('classes/PlaceMapCategory.php');
            if ($cats = PlaceMapCategory::loadAll_event($_GET['event_id'])) {
                foreach($cats as $category) {
                    $category_d = (array)$category;
                    $err = $this->cat_view($category_d);
                }
            }

            $this->confirm_button($event_d);
        } else
           return true;
    }
}

?>