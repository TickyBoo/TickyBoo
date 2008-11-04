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

class pm_authorize_aim_View extends AdminView{
	
	
	function pm_view ( &$data ){
    global $_SHOP;
		$this->dyn_load("lang/pm_ideal_{$_SHOP->lang}.inc");
		
		$extra = $data['extra'];
	  $this->print_field('pm_authorize_aim_login',$extra);
	  $this->print_field('pm_authorize_aim_txnkey',$extra);
	  $this->print_field('pm_authorize_aim_hash',$extra);
	  $this->print_field('pm_authorize_aim_test',$extra);
	}
	
	function pm_init ( &$hand, &$data ){ 
    global $_SHOP;
				
		$form1= 
'
<div class="cc_div">
{en}To validate your order please introduce your payment information and  
click on "Pay". <br> At once that your payment is completed, you receive 
your tickets by e-mail. <br>If we cannot record cashing during 12 next hours, 
your order is cancelled automatically. {/en}
{fr}Pour valider votre commande veuillez introduire les detais du payement et 
cliquer sur "Payer".<br> Aussit&ocirc;t que votre paiement est confirm&ecute;,
vous recevez vos billets par  e-mail.<br>Si nous ne recevons pas de payement au 
cours des 12 prochaines heures, votre commande est annul&eacute;e 
automatiquement.{/fr}
{it}Per completare il  pagamento voglia cliccare su "Pagare". <br>
Non appena il Suo pagamento &egrave; completato, 
ricever&agrave; i Suoi biglietti per posta elettronica. <br>
Se non possiamo registrare l"incasso nel corso delle 12 prossime ore, 
il Suo ordine &egrave; annullato automaticamente.{/it}
{de}Um Ihre Bezahlung abzuschliessen klicken Sie bitte auf "Bezahlen".<br>
Sobald Ihre Zahlung abgeschlossen ist, erhalten Sie Ihre Tickets via E-Mail.<br>
Falls wir in den n&auml;hsten 12 Stunden keinen Zahlungseingang verzeichnen
k&ouml;nnen, wird Ihre Bestellung automatisch annulliert.{/de}
<br><br>

{include file="cc_authorize_form.tpl"}
</div>
';	


    $hand->handling_html_template .= $form1;
    
		$extra=$hand->extra;
		$extra['pm_authorize_aim_test']=TRUE;
		$hand->extra=$extra;
		
		$hand->handling_text_payment = 
'{fr}Carte de cr&eacute;dit{/fr}{de}Kreditkarte{/de}
{it}Carta di Credito{/it}{en}Credit Card{/en}';
		$hand->handling_text_payment_alt = 
'{fr}Carte de cr&eacute;dit{/fr}{de}Kreditkarte{/de}
{it}Carta di Credito{/it}{en}Credit Card{/en}';
	}

	function pm_fill ( &$hand, &$data ){ 
    $extra = $hand->extra;
		
		$extra['pm_authorize_aim_login']=$data['pm_authorize_aim_login'];
		$extra['pm_authorize_aim_txnkey']=$data['pm_authorize_aim_txnkey'];
		$extra['pm_authorize_aim_test']=$data['pm_authorize_aim_test'];
		$extra['pm_authorize_aim_hash']=$data['pm_authorize_aim_hash'];

		$hand->extra = $extra;
	}

	
	function pm_check ( &$data, &$err ){
		
		
	  $data['extra']['pm_authorize_aim_login']=$data['pm_authorize_aim_login'];
	  $data['extra']['pm_authorize_aim_txnkey']=$data['pm_authorize_aim_txnkey'];
	  $data['extra']['pm_authorize_aim_test']=$data['pm_authorize_aim_test'];
	  $data['extra']['pm_authorize_aim_hash']=$data['pm_authorize_aim_hash'];

		return TRUE;
	}
	
  function pm_form ( &$data, &$err ){
		global $_SHOP;
		
		$this->dyn_load("lang/pm_authorize_{$_SHOP->lang}.inc");
		
		//$docs=array('pm_authorize_aim_site'=>'<a class="link" href="https://www.authorize_aim.com/" target="_blank">PayPal</a>');

		$this->print_input('pm_authorize_aim_login',$data['extra'],$err);	
		$this->print_input('pm_authorize_aim_txnkey',$data['extra'],$err);	
		$this->print_input('pm_authorize_aim_hash',$data['extra'],$err);	
    $this->print_checkbox('pm_authorize_aim_test',$data['extra'],$err);
//    $this->print_field('pm_yp_docs',$docs);
	}
	
}
?>