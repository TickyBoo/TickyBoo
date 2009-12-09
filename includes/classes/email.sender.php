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

//The autoloader doenst seem to try to find this one.
require_once("classes/model.emaillog.php");
class EmailSender {
  
  public function send(&$template,&$data,$testMail='',$lang=''){    
    //Get $template Type
    if(!is_object($template)){
      return false;
    }
    $type = is($template->template_type,'swift');
    
    require_once('classes/email.swift.sender.php');
    $template->write($message,$data,$lang);
    
    $log = new EmailLog();
    $log->el_order_id = is($data['order_id']);
    $log->el_user_id = is($data['user_id']);
    $log->el_action = is($data['action'],'unknown'); //need to add action to the data.
    $log->el_email_uid = is($message->getId());
    $log->el_email_to = serialize(is($message->getTo()));
    $log->el_email_cc = serialize(is($message->getCc()));
    $log->el_email_message = is($message->toString());
    $log->el_bad_emails = '';
    try{
      if(EmailSwiftSender::send($message,"",$logger,$failedAddr)){
        $log->el_failed = 'no';
        $log->el_log = empt($logger->dump(),'');
        $log->el_bad_emails = serialize(empt($failedAddr,''));
        $log->save();
        return true;
      }
      $log->el_failed = 'yes';
      $log->el_log = empt($logger->dump(),'');
      $log->el_bad_emails = serialize(empt($failedAddr,''));
      $log->save();
      return false;
    }catch(Exception $e){
      $log->el_log = empt($logger->dump(),'');
      $log->el_failed = 'yes';
      $log->save();
      return false;
    }
  }
}
?>