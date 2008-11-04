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

global $_VALUTA_LIST;
$_VALUTA_LIST = array(
  'EUR' => '&euro;',
  'AUD' => '&#36;',
  'CAD' => '&#36;',
  'USD' => '&#36;',
  'SGD' => '&#36;',
  'NZD' => '&#36;',
  'GBP' => '&pound;',
  'JPY' => '&yen;');

/* to found more currencies look at: http://en.wikipedia.org/wiki/List_of_circulating_currencies"
/*
CHF Swiss Franc
CZK Czech Koruna
DKK Danish Krone
EUR Euro
GBP Pound Sterling
HKD Hong Kong Dollar
HUF Hungarian Forint
JPY Japanese Yen
NOK Norwegian Krone
NZD New Zealand Dollar
PLN Polish Zloty
SEK Swedish Krona
SGD Singapore Dollar
USD U.S. Dollar
*/
function smarty_function_valuta ($params,&$smarty) {
  global $_VALUTA_LIST, $_SHOP;
  
  if (isset($params['code'])) {
    $valuta = $params['code'];
  }
  else {
    $valuta = $_SHOP->organizer_data->organizer_currency;
  }

  $valuta = (isset($_VALUTA_LIST[$valuta]))?$_VALUTA_LIST[$valuta]:$valuta;
  $valuta = (!empty($params['value']))?$valuta.' '.$params['value']:$params['value'].' '.$valuta;

  if(!empty($params['assign'])){
    $smarty->assign($params['assign'],$valuta);
  }else{
    return $valuta;
  }   
}

?>