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

$tbls = array();
$tbls['Admin'] = 'admin';

//$tbls['admin'] = 'Admin';
/* */
$tbls['admin']['fields'] = array(
  'admin_id' => " int(11) NOT NULL AUTO_INCREMENT ",
  'admin_login' => " varchar(50) NOT NULL DEFAULT ''",
  'admin_password' => " varchar(45) NOT NULL DEFAULT ''",
  'admin_status' => " enum('admin','organizer','control','pos') NOT NULL DEFAULT 'organizer'",
  'control_event_ids' => " varchar(100) NOT NULL DEFAULT ''");
$tbls['admin']['key'] = array(
  "PRIMARY KEY (`admin_id`)",
  "KEY `admin_login` (`admin_login`)");
$tbls['admin']['engine'] = 'InnoDB';
$tbls['admin']['remove'] = array ('control_organizer_id','admin_level')   ;
/* */
/* *
$tbls['Admin']['fields'] = array(
  'admin_id' => " int(11) NOT NULL AUTO_INCREMENT ",
  'admin_login' => " varchar(50) NOT NULL DEFAULT ''",
  'admin_password' => " varchar(45) NOT NULL DEFAULT ''",
  'admin_status' => " enum('admin','organizer','control','pos') NOT NULL DEFAULT 'organizer'",
  'control_event_ids' => " varchar(100) NOT NULL DEFAULT ''");
$tbls['Admin']['key'] = array(
  "PRIMARY KEY (`admin_id`)",
  "KEY `admin_login` (`admin_login`)");
$tbls['Admin']['engine'] = 'InnoDB';
$tbls['Admin']['remove'] = array ('control_organizer_id','admin_level')   ;
/* */

$tbls['Control']['fields'] = array(
  'admin_id' => " int(11) NOT NULL AUTO_INCREMENT ",
  'control_login' => " varchar(50) NOT NULL DEFAULT ''",
  'control_password' => " varchar(45) NOT NULL DEFAULT ''",
  'admin_status' => " enum('admin','organizer','control','pos') NOT NULL DEFAULT 'control'",
  'control_event_ids' => " varchar(100) DEFAULT ''");
$tbls['Control']['key'] = array(
  "PRIMARY KEY (`admin_id`)"
  );
$tbls['Control']['engine'] = 'InnoDB';
$tbls['Control']['remove'] = array ('control_organizer_id');

$tbls['SPoint']['fields'] = array(
  'user_id' => " int(11) NOT NULL DEFAULT '0'" ,
  'login' => " varchar(50) NOT NULL DEFAULT ''",
  'password' => " varchar(45) NOT NULL DEFAULT ''",
  'admin_status' => " enum('admin','organizer','control','pos') NOT NULL DEFAULT 'pos'");
$tbls['SPoint']['key'] = array(
  "UNIQUE KEY `user_id` (`user_id`)");
$tbls['SPoint']['engine'] = 'InnoDB';
$tbls['SPoint']['remove'] = array ();


$tbls['auth']['fields'] = array(
  'auth_id' => " int(11) NOT NULL AUTO_INCREMENT",
  'user_id' => " int(11) NOT NULL DEFAULT '0'",
  'username' => " varchar(50) NOT NULL DEFAULT ''",
  'password' => " varchar(45) NOT NULL DEFAULT ''",
  'active' => " varchar(38) DEFAULT 'not null'",
  'user_group' => " int(11) NOT NULL DEFAULT '0'");
$tbls['auth']['key'] = array(
  "PRIMARY KEY (`auth_id`)",
  "UNIQUE KEY `username` (`username`)",
  "KEY `password` (`password`)");
$tbls['auth']['engine'] = 'InnoDB';
$tbls['auth']['remove'] = array ('owner_id','userlevel');

$tbls['User']['fields'] = array(
  'user_id' => " int(11) NOT NULL AUTO_INCREMENT",
  'user_lastname' => " varchar(50) NOT NULL DEFAULT ''",
  'user_firstname' => " varchar(50) NOT NULL DEFAULT ''",
  'user_address' => " varchar(75) NOT NULL DEFAULT ''",
  'user_address1' => " varchar(75) NOT NULL DEFAULT ''",
  'user_zip' => " varchar(10) NOT NULL DEFAULT ''",
  'user_city' => " varchar(50) NOT NULL DEFAULT ''",
  'user_state' => " varchar(50) DEFAULT NULL",
  'user_country' => " varchar(5) NOT NULL DEFAULT ''",
  'user_phone' => " varchar(50) DEFAULT NULL",
  'user_fax' => " varchar(50) DEFAULT NULL",
  'user_email' => " varchar(50) NOT NULL DEFAULT ''",
  'user_status' => " tinyint(4) NOT NULL DEFAULT '0'",
  'user_prefs' => " varchar(50) DEFAULT NULL",
  'user_custom1' => " varchar(50) DEFAULT ''",
  'user_custom2' => " text",
  'user_custom3' => " int(11) DEFAULT '0'",
  'user_custom4' => " datetime DEFAULT '0000-00-00 00:00:00'",
  'user_owner_id' => " int(11) NOT NULL DEFAULT '0'",
  'user_lastlogin' => " datetime NOT NULL DEFAULT '0000-00-00 00:00:00'",
  'user_order_total' => " int(11) NOT NULL DEFAULT '0'",
  'user_current_tickets' => " int(6) NOT NULL DEFAULT '0'",
  'user_total_tickets' => " int(11) NOT NULL DEFAULT '0'");
$tbls['User']['key'] = array(
  "PRIMARY KEY (`user_id`)");
$tbls['User']['engine'] = 'InnoDB';
$tbls['User']['remove'] = array ('user_organizer_ids') ;
//$tbls['User']['AUTO_INCREMENT'] = 48;


$tbls['Category']['fields'] = array(
  'category_id' => " int(11) NOT NULL AUTO_INCREMENT",
  'category_event_id' => " int(11) NOT NULL DEFAULT '0'",
  'category_price' => " decimal(10,2) DEFAULT NULL",
  'category_name' => " varchar(100) DEFAULT NULL",
  'category_pm_id' => " int(11) DEFAULT NULL",
  'category_pmp_id' => " int(11) DEFAULT NULL",
  'category_ident' => " tinyint(4) DEFAULT NULL",
  'category_status' => " varchar(5) NOT NULL DEFAULT ''",
  'category_numbering' => " varchar(5) NOT NULL DEFAULT 'both'",
  'category_size' => " int(11) DEFAULT NULL",
  'category_max' => " int(11) DEFAULT NULL",
  'category_min' => " int(11) DEFAULT NULL",
  'category_template' => " varchar(30) DEFAULT NULL",
  'category_color' => " int(11) NOT NULL DEFAULT '0'",
  'category_data' => " tinytext");
$tbls['Category']['key'] = array(
  "PRIMARY KEY (`category_id`)",
  "KEY `category_event_id` (`category_event_id`)");
$tbls['Category']['engine'] = 'InnoDB';
$tbls['Category']['remove'] = array ('category_organizer_id');
//$tbls['Category']['AUTO_INCREMENT'] = 87;

$tbls['Category_stat']['fields'] = array(
  'cs_category_id' => " int(11) NOT NULL DEFAULT '0'",
  'cs_total' => " int(11) NOT NULL DEFAULT '0'",
  'cs_free' => " int(11) NOT NULL DEFAULT '0'");
$tbls['Category_stat']['key'] = array(
  "PRIMARY KEY (`cs_category_id`)");
$tbls['Category_stat']['engine'] = 'InnoDB';
$tbls['Category_stat']['remove'] = array ('cs_organizer_id');

$tbls['CC_Info']['fields'] = array(
  'cc_info_order_id' => "  int(11) NOT NULL AUTO_INCREMENT",
  'cc_info_data' => "  mediumtext NOT NULL",
  'cc_info_key' => "  mediumtext NOT NULL");
$tbls['CC_Info']['key'] = array(
  "PRIMARY KEY (`cc_info_order_id`)");
$tbls['CC_Info']['engine'] = 'InnoDB';
$tbls['CC_Info']['remove'] = array ('cc_info_organizer_id');
//$tbls['CC_Info']['AUTO_INCREMENT'] = 1;

$tbls['Color']['fields'] = array(
  'color_id' => " int(11) NOT NULL AUTO_INCREMENT",
  'color_code' => " varchar(7) NOT NULL DEFAULT ''");
$tbls['Color']['key'] = array(
  "PRIMARY KEY (`color_id`)");
$tbls['Color']['engine'] = 'InnoDB';
$tbls['Color']['remove'] = array ();
//$tbls['Color']['AUTO_INCREMENT'] = 11;

$tbls['Discount']['fields'] = array(
  'discount_id' => " int(11) NOT NULL AUTO_INCREMENT",
  'discount_event_id' => " int(11) NOT NULL DEFAULT '0'",
  'discount_name' => " varchar(50) NOT NULL DEFAULT ''",
  'discount_type' => " varchar(7) NOT NULL DEFAULT ''",
  'discount_value' => " decimal(10,2) NOT NULL DEFAULT '0.00'");
$tbls['Discount']['key'] = array(
  "PRIMARY KEY (`discount_id`)");
$tbls['Discount']['engine'] = 'InnoDB';
$tbls['Discount']['remove'] = array ();
//$tbls['Discount']['AUTO_INCREMENT'] = 4;

$tbls['Event']['fields'] = array(
  'event_id' => " int(11) NOT NULL AUTO_INCREMENT",
  'event_name' => " varchar(100) NOT NULL DEFAULT ''",
  'event_text' => " text",
  'event_short_text' => " text",
  'event_url' => " varchar(100) DEFAULT NULL",
  'event_image' => " varchar(100) DEFAULT NULL",
  'event_ort_id' => " int(11) NOT NULL DEFAULT '0'",
  'event_pm_id' => " int(11) DEFAULT NULL",
  'event_categories_nr' => " int(11) DEFAULT NULL",
  'event_date' => " date DEFAULT NULL",
  'event_time' => " time DEFAULT NULL",
  'event_open' => " time DEFAULT NULL",
  'event_end' => " time DEFAULT NULL",
  'event_status' => " varchar(5) NOT NULL DEFAULT ''",
  'event_order_limit' => " int(4) NOT NULL DEFAULT '0'",
  'event_payment' => " set('CC','POST') NOT NULL DEFAULT ''",
  'event_template' => " varchar(30) DEFAULT NULL",
  'event_group_id' => " int(11) NOT NULL DEFAULT '0'",
  'event_mp3' => " varchar(200) DEFAULT NULL",
  'event_rep' => " set('main','sub') NOT NULL DEFAULT 'main,sub'",
  'event_main_id' => " int(11) DEFAULT NULL",
  'event_type' => " varchar(25) DEFAULT NULL");
$tbls['Event']['key'] = array(
  "PRIMARY KEY (`event_id`)",
  "KEY `event_date` (`event_date`)");
$tbls['Event']['engine'] = 'InnoDB';
$tbls['Event']['remove'] = array ('event_organizer_id');
//$tbls['Event']['AUTO_INCREMENT'] = 4;

$tbls['Event_group']['fields'] = array(
  'event_group_id' => " int(11) NOT NULL AUTO_INCREMENT",
  'event_group_name' => " varchar(100) NOT NULL DEFAULT ''",
  'event_group_description' => " text",
  'event_group_image' => " varchar(100) NOT NULL DEFAULT ''",
  'event_group_status' => " varchar(5) NOT NULL DEFAULT 'unpub'",
  'event_group_start_date' => " date DEFAULT NULL",
  'event_group_end_date' => " date DEFAULT NULL",
  'event_group_type' => " varchar(25) DEFAULT NULL");
$tbls['Event_group']['key'] = array(
  "PRIMARY KEY (`event_group_id`)");
$tbls['Event_group']['engine'] = 'InnoDB';
$tbls['Event_group']['remove'] = array ('event_group_organizer_id');
//$tbls['Event_group']['AUTO_INCREMENT'] = 3;

$tbls['Event_stat']['fields'] = array(
  'es_event_id' => " int(11) NOT NULL DEFAULT '0'",
  'es_total' => " int(11) NOT NULL DEFAULT '0'",
  'es_free' => " int(11) NOT NULL DEFAULT '0'");
$tbls['Event_stat']['key'] = array(
  "PRIMARY KEY (`es_event_id`)");
$tbls['Event_stat']['remove'] = array ('es_organizer_id');
$tbls['Event_stat']['engine'] = 'InnoDB';

//$tbls['Event_stat']['engine'] = 'InnoDB';
// $tbls['Event_stat']['AUTO_INCREMENT'] = 3;
$tbls['Handling']['fields'] = array(
  'handling_id' => " int(11) NOT NULL AUTO_INCREMENT",
  'handling_payment' => " varchar(25) DEFAULT NULL",
  'handling_shipment' => " enum('email','post','entrance','sp') DEFAULT NULL",
  'handling_fee_fix' => " decimal(5,2) DEFAULT NULL",
  'handling_fee_percent' => " decimal(5,2) DEFAULT NULL",
  'handling_email_template' => " tinytext",
  'handling_pdf_template' => " tinytext",
  'handling_pdf_ticket_template' => " tinytext",
  'handling_pdf_format' => " tinytext",
  'handling_html_template' => " mediumtext",
  'handling_sale_mode' => " set('sp','www') DEFAULT NULL",
  'handling_extra' => " text",
  'handling_text_shipment' => " mediumtext",
  'handling_text_payment' => " mediumtext",
//  'handling_delunpaid' => " enum('Yes','No') NOT NULL DEFAULT 'No'",
  'handling_expires_min' => " int(11) DEFAULT NULL",
  'handling_alt' => " int(11) DEFAULT NULL",
  'handling_alt_only' => " enum('Yes','No') NOT NULL DEFAULT 'No'");
$tbls['Handling']['key'] = array(
  "PRIMARY KEY (`handling_id`)");
$tbls['Handling']['engine'] = 'InnoDB';
$tbls['Handling']['remove'] = array ('handling_organizer_id','handling_delunpaid');
//$tbls['Handling']['AUTO_INCREMENT'] = 33;

$tbls['Order']['fields'] = array(
  'order_id' => " int(11) NOT NULL AUTO_INCREMENT",
  'order_user_id' => " int(11) NOT NULL DEFAULT '0'",
  'order_session_id' => " varchar(32) NOT NULL DEFAULT ''",
  'order_tickets_nr' => " int(11) NOT NULL DEFAULT '0'",
  'order_total_price' => " decimal(10,2) NOT NULL DEFAULT '0.00'",
  'order_date' => " datetime NOT NULL DEFAULT '0000-00-00 00:00:00'",
  'order_shipment_status' => " enum('none','send') NOT NULL DEFAULT 'none'",
  'order_payment_status' => " enum('none','pending','payed') NOT NULL DEFAULT 'none'",
  'order_payment_id' => " varchar(255) DEFAULT NULL",
  'order_handling_id' => " int(11) NOT NULL DEFAULT '0'",
  'order_status' => " enum('ord','cancel','reemit','trash','res','pros') NOT NULL DEFAULT 'ord'",
  'order_reemited_id' => " int(11) DEFAULT NULL",
  'order_fee' => " decimal(10,2) DEFAULT NULL",
  'order_place' => " varchar(11) NOT NULL DEFAULT 'www'",
  'order_owner_id' => " int(11) DEFAULT NULL",
  'order_date_expire' => " datetime DEFAULT NULL",
  'order_responce' => "varchar(50) NOT NULL DEFAULT ''",
  'order_responce_date' => "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'",
  'order_note' => "text");
$tbls['Order']['key'] = array(
  "PRIMARY KEY (`order_id`)",
  "UNIQUE KEY `order_payments` (`order_handling_id`,`order_payment_id`)",
  "KEY `order_status` (`order_handling_id`,`order_shipment_status`,`order_payment_status`,`order_status`)");
$tbls['Order']['engine'] = 'InnoDB';
$tbls['Order']['remove'] = array ('order_organizer_id')   ;

//$tbls['Order']['AUTO_INCREMENT'] = 1;

$tbls['Organizer']['fields'] = array(
  'organizer_nickname' => " varchar(10) NOT NULL DEFAULT ''",
  'organizer_name' => " varchar(100) NOT NULL DEFAULT ''" ,
  'organizer_address' => " varchar(100) NOT NULL DEFAULT ''" ,
  'organizer_plz' => " varchar(100) NOT NULL DEFAULT ''",
  'organizer_ort' => " varchar(100) NOT NULL DEFAULT ''" ,
  'organizer_state' => " varchar(50) DEFAULT NULL",
  'organizer_country' => " varchar(50) DEFAULT NULL",
  'organizer_email' => " varchar(100) NOT NULL DEFAULT ''" ,
  'organizer_fax' => " varchar(100) NOT NULL DEFAULT ''" ,
  'organizer_phone' => " varchar(100) NOT NULL DEFAULT ''" ,
  'organizer_password' => " varchar(32) NOT NULL DEFAULT ''" ,
  'organizer_place' => " varchar(100) NOT NULL DEFAULT ''" ,
  'organizer_currency' => " char(3) NOT NULL DEFAULT 'GBP'" ,
  'organizer_logo' => " varchar(100) DEFAULT NULL");
$tbls['Organizer']['key'] = array();
$tbls['Organizer']['engine'] = 'InnoDB';
$tbls['Organizer']['remove'] = array ('organizer_id')   ;
//$tbls['Organizer']['AUTO_INCREMENT'] = 1;

$tbls['Ort']['fields'] = array(
  'ort_id' => " int(11) NOT NULL AUTO_INCREMENT",
  'ort_name' => " varchar(100) NOT NULL DEFAULT ''",
  'ort_phone' => " varchar(50) DEFAULT NULL",
  'ort_plan_nr' => " varchar(100) DEFAULT ''",
  'ort_url' => " varchar(100) NOT NULL DEFAULT ''",
  'ort_image' => " varchar(100) DEFAULT NULL",
  'ort_address' => " varchar(75) NOT NULL DEFAULT ''",
  'ort_address1' => " varchar(75) NOT NULL DEFAULT ''",
  'ort_zip' => " varchar(20) NOT NULL DEFAULT ''",
  'ort_city' => " varchar(50) NOT NULL DEFAULT ''",
  'ort_state' => " varchar(50) DEFAULT ''",
  'ort_country' => " varchar(50) NOT NULL DEFAULT ''",
  'ort_pm' => " text",
  'ort_fax' => " varchar(50) DEFAULT NULL");
$tbls['Ort']['key'] = array(
  "PRIMARY KEY (`ort_id`)");
$tbls['Ort']['engine'] = 'InnoDB';
$tbls['Ort']['remove'] = array ('ort_organizer_id')   ;
//$tbls['Ort']['AUTO_INCREMENT'] = 1;

$tbls['PlaceMap2']['fields'] = array(
  'pm_id' => " int(11) NOT NULL AUTO_INCREMENT",
  'pm_ort_id' => " int(11) NOT NULL DEFAULT '0'",
  'pm_event_id' => " int(11) DEFAULT NULL",
  'pm_name' => " varchar(30) NOT NULL DEFAULT ''",
  'pm_image' => " varchar(100) DEFAULT NULL");
$tbls['PlaceMap2']['key'] = array(
  "PRIMARY KEY (`pm_id`)");
$tbls['PlaceMap2']['engine'] = 'InnoDB';
$tbls['PlaceMap2']['remove'] = array ('pm_organizer_id')   ;
//$tbls['PlaceMap2']['AUTO_INCREMENT'] = 36;

$tbls['PlaceMapPart']['fields'] = array(
  'pmp_id' => " int(11) NOT NULL AUTO_INCREMENT",
  'pmp_pm_id' => " int(11) NOT NULL DEFAULT '0'",
  'pmp_ident' => " tinyint(4) NOT NULL DEFAULT '0'",
  'pmp_ort_id' => " int(11) NOT NULL DEFAULT '0'",
  'pmp_event_id' => " int(11) DEFAULT NULL",
  'pmp_name' => " varchar(30) NOT NULL DEFAULT ''",
  'pmp_width' => " int(11) NOT NULL DEFAULT '0'",
  'pmp_height' => " int(11) NOT NULL DEFAULT '0'",
  'pmp_scene' => " enum('north','east','south','west','center') NOT NULL DEFAULT 'north'",
  'pmp_shift' => " enum('0','1') NOT NULL DEFAULT '0'",
  'pmp_data' => " text NOT NULL",
  'pmp_data_orig' => " text",
  'pmp_expires' => " int(11) DEFAULT NULL");
$tbls['PlaceMapPart']['key'] = array(
  "PRIMARY KEY (`pmp_id`)");
$tbls['PlaceMapPart']['engine'] = 'InnoDB';
$tbls['PlaceMapPart']['remove'] = array ('pmp_organizer_id')   ;
//$tbls['PlaceMapPart']['AUTO_INCREMENT'] = 34;

$tbls['PlaceMapZone']['fields'] = array(
  'pmz_id' => " int(11) NOT NULL AUTO_INCREMENT",
  'pmz_pm_id' => " int(11) NOT NULL DEFAULT '0'",
  'pmz_ident' => " tinyint(4) NOT NULL DEFAULT '0'",
  'pmz_name' => " varchar(50) NOT NULL DEFAULT ''",
  'pmz_short_name' => " varchar(10) DEFAULT NULL",
  'pmz_color' => " varchar(10) DEFAULT NULL");
$tbls['PlaceMapZone']['key'] = array(
  "PRIMARY KEY (`pmz_id`)",
  "KEY `pm_id` (`pmz_pm_id`)" ,
  "KEY `pmz_ident` (`pmz_ident`)");
$tbls['PlaceMapZone']['engine'] = 'InnoDB';
$tbls['PlaceMapZone']['remove'] = array ('pmz_organizer_id')   ;
//$tbls['PlaceMapZone']['AUTO_INCREMENT'] = 51;

$tbls['Seat']['fields'] = array(
  'seat_id' => " int(11) NOT NULL AUTO_INCREMENT",
  'seat_event_id' => " int(11) NOT NULL DEFAULT '0'",
  'seat_category_id' => " int(11) NOT NULL DEFAULT '0'",
  'seat_user_id' => " int(11) DEFAULT NULL",
  'seat_order_id' => " int(11) DEFAULT NULL",
  'seat_row_nr' => " varchar(5) DEFAULT NULL",
  'seat_zone_id' => " int(11) DEFAULT NULL",
  'seat_pmp_id' => " int(11) DEFAULT NULL",
  'seat_nr' => " int(11) NOT NULL DEFAULT '0'",
  'seat_ts' => " int(11) DEFAULT NULL",
  'seat_sid' => " varchar(32) DEFAULT NULL",
  'seat_price' => " decimal(10,2) DEFAULT NULL",
  'seat_discount_id' => " int(11) DEFAULT NULL",
  'seat_code' => " varchar(16) DEFAULT NULL",
  'seat_status' => " varchar(5) NOT NULL DEFAULT 'free'",
  'seat_sales_id' => " int(11) DEFAULT NULL");
$tbls['Seat']['key'] = array(
  "PRIMARY KEY (`seat_id`)",
  "KEY `seat_event_id` (`seat_event_id`,`seat_category_id`,`seat_order_id`,`seat_ts`,`seat_status`)");
$tbls['Seat']['engine'] = 'InnoDB';
$tbls['Seat']['remove'] = array ('seat_organizer_id')   ;

$tbls['ShopConfig']['fields'] = array(
  'shopconfig_lastrun' => " int(11) NOT NULL DEFAULT '0'",
  'shopconfig_lastrun_int' => " int(11) NOT NULL DEFAULT '10'",
  'shopconfig_restime' => " int(11) NOT NULL DEFAULT '0'",
  'shopconfig_restime_remind' => " int(11) NOT NULL DEFAULT '0'",
  'shopconfig_check_pos' => " enum('No','Yes') NOT NULL DEFAULT 'No'",
  'shopconfig_delunpaid' => " enum('Yes','No') NOT NULL DEFAULT 'Yes'",
  'shopconfig_delunpaid_pos' => " enum('Yes','No') NOT NULL DEFAULT 'Yes'",
  'shopconfig_posttocollect' => " varchar(20) NOT NULL DEFAULT '2'",
  'shopconfig_user_activate' => " tinyint(4) NOT NULL DEFAULT '0'",
  'shopconfig_maxres' => " int(11) NOT NULL DEFAULT '10'",
  'status' => " char(3) NOT NULL DEFAULT 'ON'",
  'res_delay' => " int(11) NOT NULL DEFAULT '660'",
  'cart_delay' => " int(11) NOT NULL DEFAULT '600'",
  'shopconfig_run_as_demo' => " int(3) NOT NULL DEFAULT '0'" );
$tbls['ShopConfig']['engine'] = 'InnoDB';
$tbls['ShopConfig']['remove'] = array ('run_as_demo','shopconfig_organizer_id','shopconfig_id');

$tbls['Template']['fields'] = array(
  'template_id' => " int(11) NOT NULL AUTO_INCREMENT",
  'template_name' => " varchar(30) NOT NULL DEFAULT ''",
  'template_type' => " varchar(5) NOT NULL DEFAULT ''",
  'template_text' => " text NOT NULL",
  'template_ts' => " timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
  'template_status' => " varchar(5) NOT NULL DEFAULT 'new'");
$tbls['Template']['key'] = array(
  "PRIMARY KEY (`template_id`)",
  "KEY `template_name` (`template_name`)");
$tbls['Template']['engine'] = 'InnoDB';
$tbls['Template']['remove'] = array ('template_organizer_id')   ;
//$tbls['Template']['AUTO_INCREMENT'] = 21;

$tbls['Payment_log']['fields'] = array(
  'payment_log_id' => " int(11) NOT NULL AUTO_INCREMENT",
  'payment_log_order_id' => " int(11) NOT NULL DEFAULT '0'",
  'payment_log_date' => " datetime NOT NULL DEFAULT '0000-00-00 00:00:00'",
  'payment_log_ipn_server_ip' => " varchar(255) NOT NULL DEFAULT ''",
  'payment_log_ipn_server_info' => " text NOT NULL",
  'payment_log_ipn_server_result' => " text NOT NULL",
  'payment_log_action' => " varchar(255) NOT NULL DEFAULT ''",
  'payment_log_blog' => " text NOT NULL");
$tbls['Payment_log']['key'] = array(
  "PRIMARY KEY (`payment_log_id`)",
  "KEY `payment_log_order_id` (`payment_log_order_id`)");
$tbls['Payment_log']['engine'] = 'InnoDB';
$tbls['Payment_log']['remove'] = array ()   ;

$tbls['Sessions']['fields'] = array(
  'Sessions_id' => " varchar(32) NOT NULL DEFAULT ''",
  'Sessions_access' => " int(10) unsigned DEFAULT NULL",
  'Sessions_data' => " text");
$tbls['Sessions']['key'] = array(
  "PRIMARY KEY (`Sessions_id`)");
$tbls['Sessions']['remove'] = array ();
$tbls['Sessions']['engine'] = 'InnoDB';
?>