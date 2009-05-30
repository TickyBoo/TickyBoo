<?php
    /**
    * o------------------------------------------------------------------------------o
    * | This package is licensed under the Phpguru license. A quick summary is       |
    * | that for commercial use, there is a small one-time licensing fee to pay. For |
    * | registered charities and educational institutes there is a reduced license   |
    * | fee available. You can read more  at:                                        |
    * |                                                                              |
    * |                  http://www.phpguru.org/static/license.html                  |
    * o------------------------------------------------------------------------------o
    *
    * © Copyright 2008,2009 Richard Heyes
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
      return parent::send($recipients,$type);
    }
}
?>