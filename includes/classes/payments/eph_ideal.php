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
  public $mandatory = array('pm_ideal_merchantid' ); // is only used in project vazant.

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
		foreach ($Issuers as $issuerName => $entry)	{
			$issuerList[] = array($entry->getIssuerID(), $entry->getIssuerName()) ;
		}
    $_SHOP->smarty->assign('ideal_issuers',$issuerList );

    //print_r($order);
    
    return "
      {gui->StartForm name='ideal_ing' action='checkout.php' method='post' onsubmit='this.submit.disabled=true;return true;'}
        {gui->hidden name='action' value='submit'}
        {gui->hidden name='sor' value='".Order::EncodeSecureCode($order,'')."'}
        {gui->selection name='ideal_issuer' options=\$ideal_issuers}
      {gui->EndForm title=!pay! align='right' noreset=true}";
	}

  function on_submit($order) {
    global $_SHOP;
    $ideal = $this->init_ideal();
    $url = $_SHOP->root_secured. 'checkout_accept.php?'.$order->EncodeSecureCode(null);
    $response = $ideal->RequestTransaction(
        	$_POST['ideal_issuer'],
        	$order->order_id,
        	$order->order_total_price *100,
        	$order->order_description(),
        	$order->order_id,
        	EXPIRATIONPERIOD,
          $url)	;
    if ($response->IsResponseError()) {
      return array('approved'=>false,
                   'transaction_id'=>$response->getErrorCode().' - '.$response->getErrorMessage(),
                   'response'=> $response->getConsumerMessage());
    }
		$acquirerID = $response->getAcquirerID();
		$issuerAuthenticationURL = $response->getIssuerAuthenticationURL();
		$transactionID = $response->getTransactionID();
    $order->order_payment_id=$transactionID;
    $order->set_payment_status('pending');
    header('location:'.$issuerAuthenticationURL);
    return '';
  }
  
  function on_return(&$order, $result){
    global $_SHOP;
    $ideal = $this->init_ideal();
  	$response = $ideal->RequestTransactionStatus( $order->order_payment_id );

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

   if ($status = IDEAL_TX_STATUS_SUCCESS) {
	    $order->order_payment_id=$transactionID;
      $order->set_payment_status('payed');
      return array('approved'=>true,
                   'transaction_id'=>$transactionID ,
                   'response'=> 'Naam: '.$consumerName. "<br>".
                                'Plaats: '.$consumerCity."<br>".
                                'Nummer: '.$consumerAccountNumber);

   } elseif ($status = IDEAL_TX_STATUS_OPEN) {
      return array('approved'=>true,
                   'transaction_id'=>$transactionID ,
                   'response'=> con('eph_ideal_waitingaception'));
    } else {
      return array('approved'=>false,
                   'transaction_id'=>$transactionID ,
                   'response'=> 'Reason: '.$response->GetStatusText());
    }
  }
  
  function on_check(&$order){
    global $_SHOP;
    $ideal = $this->init_ideal();
  	$response = $ideal->RequestTransactionStatus( $order->order_payment_id );

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
        $order->order_delete($order->order_id, $response->GetStatusText());
        return true;
      }
    }
  }

  function init_ideal(){
    global $_SHOP;
    if (!$this->pm_ideal_test) {
      $url= 'ssl://ideal.secure-ing.com:443/ideal/iDeal';
    } else {
      $url= 'ssl://idealtest.secure-ing.com:443/ideal/iDeal';
    }
    
    $config = array(
        'PRIVATEKEY'      => "$this->pm_ideal_key",
        'PRIVATEKEYPASS'  => "$this->pm_ideal_keypass",
        'PRIVATECERT'     => "$this->pm_ideal_pcert",
        'CERTIFICATE0'    => "$this->pm_ideal_certficate",
        
        'ACQUIRERURL'     => "$url",
        #'ROXY'=Vul hier een proxyserver in (gebruik dit ALLEEN als de webshop achter een proxyserver zit)
        #'ROXYACQURL'=vul hier de url van de acquirer in (gebruik dit ALLEEN als de webshop achter een proxyserver zit)
        'ACQUIRERTIMEOUT' => ACQUIRERTIMEOUT,

        'MERCHANTRETURNURL' => $_SHOP->root,
        'MERCHANTID'      => "$this->pm_ideal_merchantid",
        'SUBID'           => ($this->pm_ideal_subid)?$this->pm_ideal_subid:'0',
        'EXPIRATIONPERIOD'=> EXPIRATIONPERIOD,
        'LOGFILE'         => INC.'tmp'.DS.'ideal_connect.log',
        'TRACELEVEL'      => TRACE_DEBUG.TRACE_ERROR,
        );
    return new iDEALConnector($config);
    

  }
}
?>