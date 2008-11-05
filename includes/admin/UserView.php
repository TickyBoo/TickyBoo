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

require_once("classes/ShopDB.php");
require_once("classes/User.php");
require_once("admin/AdminView.php");

class UserView extends AdminView{
  function UserView ($id)
  {
    $this->user_id = $id;
  }

  function print_user ($user)
  {
    $user["user_country_name"] = $this->getCountry($user["user_country"]);
    $status = $this->print_status($user["user_status"]);
    $user["user_status"] = $status;
    echo "<table class='admin_form' width='500' cellspacing='1' cellpadding='4'>\n";
    echo "<tr><td class='admin_list_title' colspan='2'>{$user["user_lastname"]}
    {$user["user_firstname"]}</td></tr>";

    $this->print_field('user_lastname', $user);
    $this->print_field('user_firstname', $user);
    $this->print_field('user_address', $user);
    $this->print_field('user_address1', $user);
    $this->print_field('user_zip', $user);
    $this->print_field('user_city', $user);
    $this->print_field('user_state', $user);
    $this->print_field('user_country', $user); 
    // $this->print_field('user_country_name',$user );
    $this->print_field('user_phone', $user);
    $this->print_field('user_fax', $user);
    $this->print_field('user_email', $user);
    $this->print_field('user_status', $user);

    echo "</table>\n";
  }
  function draw ()
  {
    global $_SHOP;
    $currency = $_SHOP->currency;
    $user = User::load_user($this->user_id);
    $this->print_user($user);
    $query = "select * from `Order` where order_user_id ='{$this->user_id}' and order_organizer_id={$_SHOP->organizer_id}";
    if (!$res = ShopDB::query($query)){
      user_error(shopDB::error());
      return;
    }
    echo "<br><table class='admin_list' cellspacing='0' cellpadding='5' width='500'>
   <tr><td class='admin_list_title' colspan='7'>" . orders . "</td></tr>";
    while ($order = shopDB::fetch_assoc($res)){
      echo "<tr><td class='order_item'>" . $order["order_id"] . "</td>
               <td class='order_item' colspan='6'>" . tickets_nr . " " . $order["order_tickets_nr"] .
      " - $currency " . $order["order_total_price"] . " - " . date . "  " . $order["order_date"] .
      " - " . $order["order_shipment_mode"] . " - " .
      $this->print_order_status($order["order_status"]) . "
	       <a href='view_order.php?action=details&order_id=" . $order["order_id"] . "'>
	       <img src='images/view.png' border='0'></a></td><tr>";
      $query = "select * from Seat LEFT JOIN Discount ON seat_discount_id=discount_id,Event,Category where seat_order_id='" . $order["order_id"] . "'
               AND seat_event_id=event_id AND seat_category_id= category_id and event_organizer_id='{$_SHOP->organizer_id}'";
      if (!$res1 = ShopDB::query($query)){
        user_error(shopDB::error());
        return;
      } while ($ticket = shopDB::fetch_assoc($res1)){
        if ((!$ticket["category_numbering"]) or $ticket["category_numbering"] == 'both'){
          $place = $ticket["seat_row_nr"] . "-" . $ticket["seat_nr"];
        }else if ($ticket["category_numbering"] == 'rows'){
          $place = place_row . " " . $ticket["seat_row_nr"];
        }else if ($ticket["category_numbering"] == 'seat'){
          $place = place_seat . " " . $ticket["seat_nr"];
        }else{
          $place = '---';
        }

        echo "<tr><td class='ticket_item_1'>&nbsp;</td>
	       <td class='ticket_item'>" . $ticket["seat_id"] . "</td>
	       <td class='ticket_item'>" . $ticket["event_name"] . "</td>
	       <td class='ticket_item'>" . $ticket["category_name"] . "</td>
	       <td class='ticket_item'>$place</td>
	       <td class='ticket_item'>" . $ticket["discount_name"] . "</td>

	       <td class='ticket_item' align='right'>" . $ticket["seat_price"] . "$currency </td><tr>";
      }
    }
    echo "</table>";
  }
  function print_status ($user_status)
  {
    if ($user_status == '1'){
      return sale_point;
    }else if ($user_status == '2'){
      return member;
    }else if ($user_status == '3'){
      return guest;
    }
  }

  function print_order_status ($order_status)
  {
    if ($order_status == 'ord'){
      return "<font color='blue'>" . ordered . "</font>";
    }else if ($order_status == 'send'){
      return "<font color='red'>" . sended . "</font>";
    }else if ($order_status == 'payed'){
      return "<font color='green'>" . payed . "</font>";
    }else if ($order_status == 'cancel'){
      return "<font color='#787878'>" . canceled . "</font>";
    }
  }
}

?>