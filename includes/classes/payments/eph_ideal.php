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

//require_once("admin/AdminView.php");

class EPH_ideal extends payment{
	
	
	function admin_view ( &$data ){
		$extra = $data['extra'];
	  $this->print_field('pm_authorize_aim_login',$extra);
	  $this->print_field('pm_authorize_aim_txnkey',$extra);
	  $this->print_field('pm_authorize_aim_hash',$extra);
	  $this->print_field('pm_authorize_aim_test',$extra);
	}
	
  function admin_form ( &$data, &$err ){
		$this->print_input('pm_authorize_aim_login',$data['extra'],$err);
		$this->print_input('pm_authorize_aim_txnkey',$data['extra'],$err);
		$this->print_input('pm_authorize_aim_hash',$data['extra'],$err);
    $this->print_checkbox('pm_authorize_aim_test',$data['extra'],$err);
//    $this->print_field('pm_yp_docs',$docs);
	}

  function init ( ){
    global $_SHOP;
				
		$form1= 
'<div class="cc_div">
To validate your order please introduce your payment information and
click on "Pay". <br> At once that your payment is completed, you receive 
your tickets by e-mail. <br>If we cannot record cashing during 12 next hours, 
your order is cancelled automatically.<br><br>
</div>';

    $hand->handling_html_template   .= $form1;
		$hand->handling_text_payment     = 'Credit Card';
		$hand->handling_text_payment_alt = 'Credit Card';

		$hand->extra['pm_authorize_aim_test']=TRUE;
	}

}
?>