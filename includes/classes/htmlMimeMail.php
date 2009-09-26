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


require_once(dirname(__FILE__) . '/RMail.php');

class HtmlMimemail extends RMail{

    public function send($recipients, $type = null) {
      global $_SHOP;
      
      if(is_null($type)) $type = is($_SHOP->mail_mode,'mail');
      parent::setSMTPParams($_SHOP->mail_smtp_host,
                            $_SHOP->mail_smtp_port,
                            $_SHOP->mail_smtp_helo,
                            $_SHOP->mail_smtp_auth,
                            $_SHOP->mail_smtp_user,
                            $_SHOP->mail_smtp_pass);
      if (!is_null($_SHOP->mail_sendmail)) {
        parent::setSendmailPath($_SHOP->mail_sendmail);
      }
      if (!$result = parent::send($recipients,$type)){
        shopdb::dblogging("email '{$type}' errors:\n".print_r($email->errors,true));
      }
      return $result;
    }
}
?>