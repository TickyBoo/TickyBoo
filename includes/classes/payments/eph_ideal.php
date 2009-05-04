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
If (!defined('ACQUIRERTIMEOUT')) {
  define ('ACQUIRERTIMEOUT',10);
  define ('TRACELEVEL', 'DEBUG,ERROR');
  define ('EXPIRATIONPERIOD','PT10M');
}

require_once('classes/Payment.php');
require_once('ideal/iDEALConnector.php');

class eph_ideal extends payment{
	
  public $extras = array('pm_ideal_merchantid', 'pm_ideal_subid', 'pm_ideal_key',
                         'pm_ideal_keypass', 'pm_ideal_pcert', 'pm_ideal_certficate',
                         'pm_ideal_issuer','pm_ideal_test');
  public $mandatory = array('pm_ideal_MERCHANTID', ); // is only used in project vazant.

  function admin_form (){
    return "{gui->input name='pm_ideal_merchantid'}".
           "{gui->input name='pm_ideal_subid'}".
           "{gui->selection name=pm_ideal_issuer options='ING~ING bank'}".
           "{gui->checkbox name='pm_ideal_test'}".

           "{gui->input name='pm_ideal_key'}".
           "{gui->input name='pm_ideal_keypass'}".
           "{gui->input name='pm_ideal_pcert'}".
           "{gui->input name='pm_ideal_certficate'}";
	}

	function init (){
  	$this->handling_text_payment    = "iDEAL";
		$this->handling_text_payment_alt= "iDEAL";
//    $this->handling_html_template  .= "";
		$this->pm_paypal_test  = true;
	}

	function on_confirm(&$order) {
    global $_SHOP;
    $ideal = $this->init_ideal();
    $response = $ideal->GetIssuerList();
    if ($response->IsResponseError()) {
      return array('approved'=>false,
                   'transaction_id'=>$response->getErrorCode().' - '.$response->getErrorMessage(),
                   'response'=> $response->getConsumerMessage());
    }

    $Issuers =& $response->getIssuerFullList();
		$trans = array (" " => "&nbsp");
		foreach ($Issuers as $issuerName => $entry)	{
			$issuerList[$entry->getIssuerID()] =
        strtr(str_pad($entry->getIssuerID(), 20), $trans) . "&nbsp;"
				. $entry->getIssuerName() . "&nbsp;-&nbsp;"
				. $entry->getIssuerListType()
				;
		}
		$issuerList .= "</option>\n";
    $_SHOP->smarty->assign('ideal_issuers',$issuerList );

    return "
      <form name='ideal_ing' action='' method='post' onsubmit='this.submit.disabled=true;return true;'>
        <input type='hidden' name='action' value='submit'>
        {gui->selection name='ideal_issuer' options=\$ideal_issuers}
        <div align='right'>
        <input type='submit' value='{!pay!}' name='submit2'>
        </div>
      </form>";
	}

  function on_submit($order) {
    $ideal = $this->init_ideal();
    $response = $ideal->RequestTransaction(
        	$_POST['ideal_issuer'],
        	$order->order_id,
        	$order->$order->order_total_price *100,
        	$order->order_description(),
        	$order->EncodeSecureCode(),
        	EXPIRATIONPERIOD,
        	$_SHOP->root_secured. 'checkout_accept.php?'.$order->EncodeSecureCode() );
    if ($response->IsResponseError()) {
      return array('approved'=>false,
                   'transaction_id'=>$response->getErrorCode().' - '.$response->getErrorMessage(),
                   'response'=> $response->getConsumerMessage());
    }
		$acquirerID = $response->getAcquirerID();
		$issuerAuthenticationURL = $response->getIssuerAuthenticationURL();
		$transactionID = $response->getTransactionID();
  	$query = "update `Order`
  					set order_payment_id="._esc(transactionID)."
  					where order_id="._esc($order->order_id);
    ShopDB::query($query);
    $order->order_payment_id=$transactionID;
    header('location:'.$transactionID);
    return '';
  }
  
  function on_notify(&$order){
  }
  
  function on_return(&$order, $result){
  	$transactionID = $_POST["TransactionID"];
  	$response = $iDEALConnector->RequestTransactionStatus( $transactionID );

    if ($response->IsResponseError()){
      return array('approved'=>false,
                   'transaction_id'=>$response->getErrorCode().' - '.$response->getErrorMessage(),
                   'response'=> $response->getConsumerMessage());
  	}
		// Geldige response.
		$acquirerID = $response->getAcquirerID();

		$consumerName = $response->getConsumerName();
  	$consumerAccountNumber = $response->getConsumerAccountNumber();
    $consumerCity = $response->getConsumerCity();
    $transactionID = $response->getTransactionID();

    // De status is een integer en kan middels een aantal
    // constanten geinitialiseerd zijn:
		// IDEAL_TX_STATUS_INVALID		Status code van iDEAL server niet herkend
		// IDEAL_TX_STATUS_SUCCESS		Transactie succcess
		// IDEAL_TX_STATUS_CANCELLED	Transactie geannuleerd door bezoeker
		// IDEAL_TX_STATUS_EXPIRED		Transactie verlopen
		// IDEAL_TX_STATUS_FAILURE		Transactie fout
		// IDEAL_TX_STATUS_OPEN  		Transactie staat nog open
    $status = $response->getStatus();

  	if (in_array($status, array(IDEAL_TX_STATUS_SUCCESS))) {
	    $order->order_payment_id=$transactionID;
      $order->set_payment_status('payed');
    }

    $result = in_array($status, array(IDEAL_TX_STATUS_SUCCESS, IDEAL_TX_STATUS_OPEN));

    If ($result) {
      return array('approved'=>true,
                   'transaction_id'=>$transactionID ,
                   'response'=> 'Naam: '.$consumerName. "<br>".
                                'Plaats: '.$consumerCity."<br>".
                                'Nummer: '.$consumerAccountNumber);
    } else {
      return array('approved'=>false,
                   'transaction_id'=>$transactionID ,
                   'response'=> 'Reason: '.$response->GetStatusText());
    }
  }
  
  function on_check(&$order, $result){
  	$transactionID = $order->order_payment_id;
  	$response = $iDEALConnector->RequestTransactionStatus( $transactionID );

    if (!$response->IsResponseError()){
      // De status is een integer en kan middels een aantal
      // constanten geinitialiseerd zijn:
  		// IDEAL_TX_STATUS_INVALID		Status code van iDEAL server niet herkend
  		// IDEAL_TX_STATUS_SUCCESS		Transactie succcess
  		// IDEAL_TX_STATUS_CANCELLED	Transactie geannuleerd door bezoeker
  		// IDEAL_TX_STATUS_EXPIRED		Transactie verlopen
  		// IDEAL_TX_STATUS_FAILURE		Transactie fout
  		// IDEAL_TX_STATUS_OPEN  		Transactie staat nog open
      $status = $response->getStatus();

    	if (in_array($status, array(IDEAL_TX_STATUS_SUCCESS))) {
  	    $order->order_payment_id=$transactionID;
        $order->set_payment_status('payed');
        return true;
      } elseif (in_array($status, array(IDEAL_TX_STATUS_CANCELLED, IDEAL_TX_STATUS_EXPIRED ))) {
        $order->set_payment_status('payed');
        return true;
      }
    }
  }

  function init_ideal(){
    if (!$this->pm_ideal_test) {
      $url= 'ssl://ideal.secure-ing.com:443/ideal/iDeal';
    } else {
      $url= 'ssl://idealtest.secure-ing.com:443/ideal/iDeal';
    }
    
    $config = array(
        'PRIVATEKEY'      => $this->pm_ideal_key,
        'PRIVATEKEYPASS'  => $this->pm_ideal_keypass,
        'PRIVATECERT'     => $this->pm_ideal_pcert,
        'CERTIFICATE0'    => $this->pm_ideal_certficate,
        
        'ACQUIRERURL'     => $url,
        #'ROXY'=Vul hier een proxyserver in (gebruik dit ALLEEN als de webshop achter een proxyserver zit)
        #'ROXYACQURL'=vul hier de url van de acquirer in (gebruik dit ALLEEN als de webshop achter een proxyserver zit)
        'ACQUIRERTIMEOUT' => ACQUIRERTIMEOUT,

        'MERCHANTID'      => $this->pm_ideal_merchantid,
        'SUBID'           => $this->pm_ideal_subid,
        'EXPIRATIONPERIOD'=> EXPIRATIONPERIOD,
        'LOGFILE'         => INC.'tmp'.DS.'ideal_connect.log',
        'TraceLevel'      => TRACELEVEL,
        );
    return new iDEALConnector($config);

  }
}
?>