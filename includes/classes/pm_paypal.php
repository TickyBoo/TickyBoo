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

class pm_paypal{

  function on_notify(&$pay,&$smarty){
    require_once('classes/Order.php');
    require_once('functions/connect_func.php');
    global $_SHOP;
    $url=$pay['extra']['pm_paypal_url'];
    $receiver_email=$pay['extra']['pm_paypal_business'];
    
    
    $debug.="url: $url\n";
		
    $order_a=$smarty->get_template_vars('shop_order');
    $order_id=$order_a['order_id'];
    $order_total=$order_a['order_total_price'];
    
    $debug.="order_id : $order_id\n";
    
    $_POST["cmd"]="_notify-validate";
    $result=url_post($url,$_POST);
    
    $debug.="res : $result\n";

    $debug.=print_r($_POST,true);
    $debug.="OK";
    
    $handle=fopen($_SHOP->install_dir."/includes/tmp/paypal.log","a");
    fwrite($handle,$debug);
    fclose($handle);
	
	if(!eregi("VERIFIED",$result)) return;
    if($_POST["payment_status"]!="Completed") return;
    if($_POST["receiver_email"]!=$receiver_email) return;
    if($_POST["mc_gross"]<$order_total) return;
    if(!$order=Order::load_ext($order_id)) return;
    
	$order->order_payment_id=$_POST['txn_id'];
    $order->set_payment_status('payed');
  }
	
}
?>