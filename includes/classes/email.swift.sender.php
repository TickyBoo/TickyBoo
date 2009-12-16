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

if (!defined('ft_check')) {die('System intrusion ');}

require_once (LIBS.'swift'.DS.'swift_required.php');

class EmailSwiftSender {
  
  public function send(&$swiftMessage,$testEmail='',&$logger , &$failed){
    global $_SHOP;
    
    //Add SMTP Mailer if defined.
    if(empt($_SHOP->mail_smtp_host,false)){
      $smtp = Swift_SmtpTransport::newInstance($_SHOP->mail_smtp_host,
        empt($_SHOP->mail_smtp_port,'25'),
        empt($_SHOP->mail_smtp_security,null));
        
      if(empt($_SHOP->mail_smtp_username,false)){
        $smtp->setUsername($_SHOP->mail_smtp_username);
        $smtp->setPassword(empt($_SHOP->mail_smtp_password,''));
      }
      $tranports[] = $smtp;
    }
    //Add sendmail
    if(empt($_SHOP->mail_sendmail,false)){
      $sendmail = Swift_SendmailTransport::newInstance(empt($_SHOP->mail_sendmail,'/usr/sbin/sendmail -bs'));
      $tranports[] = $sendmail;
    }
    
    //Add mail as good measure to try and fall back on
    $mail = Swift_MailTransport::newInstance();
    $tranports[] = $mail;
    
    //Add to fail over transport
    $transport = Swift_FailoverTransport::newInstance($tranports);
    
    //Create Mailer
    $mailer = Swift_Mailer::newInstance($transport);
    
    //Or to use the Echo Logger
    //$logger = new Swift_Plugins_Loggers_EchoLogger();
    //$mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));
    
    //Or to use the Normal Logger
    $logger = new Swift_Plugins_Loggers_ArrayLogger();
    $mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));
    
    $ret = $mailer->send($swiftMessage,$failed);
    
    if(!$ret || $ret < 1){
      Shopdb::dblogging("email '{$type}' errors:\n".print_r($email->errors,true));
      return false;
    }else{
      return $ret;
    }
    
  }
  
}

?>