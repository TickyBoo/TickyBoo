<?php
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 * Copyright (C) 2007-2008 Christopher Jenkins. All rights reserved.
 *
 * Original Design:
 *	phpMyTicket - ticket reservation system
 * 	Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of fusionTicket.
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
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@noctem.co.uk if any conditions of this licencing isn't
 * clear to you.
 */

session_cache_limiter("must-revalidate");

require_once("../includes/config/init_admin.php");

require_once ("admin/MenuAdmin.php");
require_once ("admin/AdminPage.php");
require_once ("classes/AUIBico.php");
require_once ("admin/OrderView.php");





//print cart update
$body=new OrderView();
// width=200 for menu ...Change it to your preferd width;
// 700 total table
$page=new AdminPage(800);
$page->setTitle("Administration");

$bico=new AUIBico(200,800);

$page->set("body",$bico);


//edit here your menu
$menu[]=new MenuAdmin();

//if($_GET["action"]=='list_all'){
  $legende="<center>
  <table width='190' class='menu_admin' cellspacing='2'>
  <tr><td align='center' class='menu_admin_title'>".legende."</td></tr>
  <tr><td class='admin_order_res' style='padding-left: 25px; padding-top: 2px; padding-bottom: 2px;'>".reserved."</td></tr>
  <tr><td class='admin_order_ord' style='padding-left: 25px; padding-top: 2px; padding-bottom: 2px;'>".ordered."</td></tr>
  <tr><td class='admin_order_send' style='padding-left: 25px; padding-top: 2px; padding-bottom: 2px;'>".sended."</td></tr>
  <tr><td class='admin_order_payed' style='padding-left: 25px; padding-top: 2px; padding-bottom: 2px;'>".payed."</td></tr>
  <tr><td class='admin_order_cancel' style='padding-left: 25px; padding-top: 2px; padding-bottom: 2px;'>".canceled."</td></tr>
  <tr><td class='admin_order_reemit' style='padding-left: 25px; padding-top: 2px; padding-bottom: 2px;'>".reemited."</td></tr>
  <tr><td class='admin_order_payedsend' style='padding-left: 25px; padding-top: 2px; padding-bottom: 2px;'>".payed_and_send."</td></tr>
  
  </table></center><br>";
  $menu[]=$legende;
//}

if($_GET["action"]=='list_all' or $_GET["action"]=='list_type' or $_GET["action"]=='details'){
  $sty="style='padding-left: 25px; padding-top: 2px; padding-bottom: 2px;'";
  $action_symbol="<center>
  <table width='190' class='menu_admin' cellspacing='2'>
  <tr><td align='center' class='menu_admin_title'>".possible_actions."</td></tr>
  <tr><td class='menu_admin_item' $sty><img src='images/view.png' border='0'> ".view_order_details."</td></tr>
  <tr><td class='menu_admin_item' $sty><img src='images/printer.gif' border='0'> ".print_order."</td></tr>
  <tr><td class='menu_admin_item' $sty><img src='images/ord.png' border='0'> ".change_order_to_ord."</td></tr>

  <tr><td class='menu_admin_item' $sty><img src='images/mail.png' border='0'> ".send_order_post."</td></tr>
  <tr><td class='menu_admin_item' $sty><img src='images/no_mail.png' border='0'> ".no_send_order_post."</td></tr>

  <!--tr><td class='menu_admin_item' $sty><img src='images/email.png' border='0'> ".send_order_email."</td></tr-->
  <tr><td class='menu_admin_item' $sty><img src='images/pig.png' border='0'> ".change_order_to_payed."</td></tr>
  <tr><td class='menu_admin_item' $sty><img src='images/no_pig.png' border='0'> ".change_order_to_no_payed."</td></tr>

  <tr><td class='menu_admin_item' $sty><img src='images/remis.png' border='0'> ".reemit_order_menu."</td></tr>
  <tr><td class='menu_admin_item' $sty><img src='images/trash.png' border='0'> ".cancel_order."</td></tr>
  
  </table></center>";
  $menu[]=$action_symbol;
}

if($_GET['action']=='print' and $_GET['order_id']>0){
  require_once("classes/Order.php");
  Order::print_order($_GET['order_id'],'','stream');
  exit;
}

// width=200 for menu ...Change it to your preferd width;
// 700 total table 
$bico->setmenu($menu);
$bico->setbody($body);

$page->draw();

?>