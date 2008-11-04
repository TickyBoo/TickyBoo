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
require_once("admin/AdminView.php");

class pm_paypal_View extends AdminView{
	
	
	function pm_view ( &$data ){
    global $_SHOP;
		$this->dyn_load("lang/pm_paypal_{$_SHOP->lang}.inc");
		
		$extra = $data['extra'];
	  $this->print_field('pm_paypal_business',$extra);
	  $this->print_field('pm_paypal_url',$extra);
	}
	
	function pm_init ( &$hand, &$data ){ 
    global $_SHOP;
		
		$ha='$shop_handling';
		
$form1= '
Put explanations here... (in Handlins)
<br>
{* This is a comment, The top statment is for payment in the personal_page, Bottom for when they checkout. *}
{if $shop_order}
{include file="paypal_button.tpl" id=$shop_order.order_id price=$shop_order.order_total_price url=$shop_handling.extra.pm_paypal_url}
{else}
{include file="paypal_button.tpl" id=$order_id price=$order_total_price url=$shop_handling.extra.pm_paypal_url}
{/if}';	


		$hand->handling_text_payment="PayPal";
		$hand->handling_text_payment_alt="PayPal";

    $hand->handling_html_template .= $form1;
    $extra=$hand->extra;
		$extra['pm_paypal_url']='https://www.paypal.com/cgi-bin/webscr';
		$hand->extra=$extra;
	}

	function pm_fill ( &$hand, &$data ){ 
    $extra = $hand->extra;
		
		$extra['pm_paypal_business']=$data['pm_paypal_business'];
		$extra['pm_paypal_url']=$data['pm_paypal_url'];

		$hand->extra = $extra;
	}

	
	function pm_check ( &$data, &$err ){
	  $data['extra']['pm_paypal_business']=$data['pm_paypal_business'];
	  $data['extra']['pm_paypal_url']=$data['pm_paypal_url'];

		return TRUE;
	}
	
  function pm_form ( &$data, &$err ){
		global $_SHOP;
		
		$this->dyn_load("lang/pm_paypal_{$_SHOP->lang}.inc");
		
		$docs=array('pm_paypal_site'=>'<a class="link" href="https://www.paypal.com/" target="_blank">PayPal</a>');

		$this->print_input('pm_paypal_business',$data['extra'],$err);	
		$this->print_input('pm_paypal_url',$data['extra'],$err);	
//    $this->print_field('pm_yp_site',$docs);
//    $this->print_field('pm_yp_docs',$docs);
	}
	
}
?>