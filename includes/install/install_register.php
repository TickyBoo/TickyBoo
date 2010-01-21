<?php
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2010 Christopher Jenkins, Niels, Lou. All rights reserved.
 *
 * Original Design:
 *  phpMyTicket - ticket reservation system
 *   Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
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

class install_register {
  function precheck($Install) {
    return true;
  }

  function postcheck($Install) {
    if ($_REQUEST['do_send']) {
      require_once(dirname(__FILE__) . '/../libs/rmail/RMail.php');
      $_REQUEST['forumname'] = clean($_REQUEST['forumname']);
      $_REQUEST['comments']  = clean($_REQUEST['comments']);
      $email= new Rmail();
      $type = is($_SESSION['SHOP']['mail_mode'],'mail');
      $email->setSMTPParams($_SESSION['SHOP']['mail_smtp_host'],
                            $_SESSION['SHOP']['mail_smtp_port'],
                            $_SESSION['SHOP']['mail_smtp_helo'],
                            $_SESSION['SHOP']['mail_smtp_auth'],
                            $_SESSION['SHOP']['mail_smtp_user'],
                            $_SESSION['SHOP']['mail_smtp_pass']);
      if (!is_null($_SESSION['SHOP']['mail_sendmail'])) {
        $email->setSendmailPath($_SESSION['SHOP']['mail_sendmail']);
      }
      $email->setTextCharset("UTF-8");
      $email->setHtmlCharset("UTF-8");
      $email->setHeadCharset("UTF-8");
      $email->setSubject('Registerstation FusionTicket');
      $email->setFrom('noreplay@fusionticket.com');
      $email->setText("Version: ".INSTALL_VERSION."\n".
                      "Website: ".BASE_URL."\n".
                      "ForumUser: ". $_REQUEST['forumname']."\n".
                      "Comment:\n".$_REQUEST['comments']);

      $result = $email->send(array('lumensoh@xs4all.nl'),$type);
    //  print_r($email);
      if ($result) {
        array_push($Install->Warnings,'Thanks, The mail is send to us.');
      }else{
        array_push($Install->Warnings,'Sorry the mail is not send, check your mail settings.<br>'.var_dump($email->errors ) );
      }
    }
    return true;
  }

  function display($Install) {
    Install_Form_Open ($Install->return_pg,'','Register this copy');
    echo "<table cellpadding=\"1\" cellspacing=\"2\" width=\"100%\">
            <tr>
              <td colspan=\"2\">
                We like to have an idea how and where FusionTicket is used. For this reason we like you to register this copy on our server.
                The information we will register is the url, php/mysql version and the comments you write below.
              </td>
            </tr>
            <tr> <td height='6px'></td> </tr>
            <tr>
              <td width='30%'>Forum loginname:</td>
              <td><input type=\"text\" name=\"forumname\" value=\"\" /> Please fill here the username that you use on our website.</td>
            </tr>
            <tr>
              <td valign='top'>Comments:</td>
              <td >
                <textarea rows=\"3\" name=\"comments\" cols=\"50\" >
                </textarea>
              </td>
            </tr>
            <tr>
              <td colspan='2'>
                <br>Register information:  <input type='checkbox' checked='checked' name='do_send' value='1'>
              </td>
            </tr>

          </table>";
    Install_Form_Buttons ();
    Install_Form_Close ();
  }
}
?>