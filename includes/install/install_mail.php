<?php
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2009 Christopher Jenkins, Niels, Lou. All rights reserved.
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

class install_mail {
  function precheck($Install) {
    return true;
  }
  
  function postcheck($Install) {
    Install_Request(Array('mail_mode','mail_sendmail','mail_smtp_host', 'mail_smtp_port', 'mail_smtp_auth',
                          'mail_smtp_user', 'mail_smtp_pass', 'mail_smtp_helo'),'SHOP');
 
    switch($_POST['mail_mode']) {
      case 'sendmail':
        if (empty($_POST['mail_sendmail'])) {
          array_push($Install->Errors,'You need to fill the sendmail path to use sendmail.');
        } else {
          unset($_SESSION['SHOP']['mail_smtp_host']);// = null;
          unset($_SESSION['SHOP']['mail_smtp_port']);// = null;
          unset($_SESSION['SHOP']['mail_smtp_auth']);// = null;
          unset($_SESSION['SHOP']['mail_smtp_user']);// = null;
          unset($_SESSION['SHOP']['mail_smtp_pass']);// = null;
          unset($_SESSION['SHOP']['mail_smtp_helo']);// = null;
        }        
        break;
      case 'SMTP':
        if (empty($_POST['mail_smtp_host']) or empty($_POST['mail_smtp_port'])) {
          array_push($Install->Errors,'You need to fill the Hostname and port to use SMTP.');
        }else{
          unset($_SESSION['SHOP']['mail_sendmail']);//  = null;         
        }
        
        if (isset($_POST['mail_smtp_auth']) and (empty($_POST['mail_smtp_user']) or empty($_POST['mail_smtp_pass']) )) {
          array_push($Install->Errors,'You need to fill the username and passwor to use SMTP with authrisation.');
        }
        break;
    default:
      unset($_SESSION['SHOP']['mail_mode']);//      = 'sendmail';

      unset($_SESSION['SHOP']['mail_smtp_host']);// = null;
      unset($_SESSION['SHOP']['mail_smtp_port']);// = null;
      unset($_SESSION['SHOP']['mail_smtp_auth']);// = null;
      unset($_SESSION['SHOP']['mail_smtp_user']);// = null;
      unset($_SESSION['SHOP']['mail_smtp_pass']);// = null;
      unset($_SESSION['SHOP']['mail_smtp_helo']);//= null;
      
      unset($_SESSION['SHOP']['mail_sendmail']);//  = null;         
      
    }
    return true;
    
  }

  function display($Install) {
    Install_Form_Open ($Install->return_pg,'Validate_Inst_Database()');
    $chk[$_SESSION['SHOP']['mail_mode']] = 'selected="selected"';
    $auth = ($_SESSION['SHOP']['mail_smtp_auth'])?"checked='checked'":'';
    
    if (empty($_SESSION['SHOP']['mail_smtp_host'])) $_SESSION['SHOP']['mail_smtp_host'] = 'localhost';
    if (empty($_SESSION['SHOP']['mail_smtp_port'])) $_SESSION['SHOP']['mail_smtp_port'] = '25';
    
    echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
            <tr>
              <td colspan=\"2\">
                <h2>Mail settings.</h2>
              </td>
            </tr>
            <tr>
              <td colspan=\"2\">
                Config the mail server. You have the choice between the default linux-mail, sendmail and SMTP.<br><br>
              </td>
            </tr>
            <tr>
              <td valign='top'>Mailserver:</td>
              <td>
                <select name='mail_mode'>
                  <option value='' >Default linux-mail server</option>  
                  <option value='sendmail' {$chk['sendmail']}>SendMail server</option>  
                  <option value='SMTP' {$chk['SMTP']}>SMTP server</option>
                </select><br><br>
              </td>
            </tr>
            <tr>
              <td width='30%'>SMTP Hostname</td>
              <td><input type=\"text\" name=\"mail_smtp_host\" value=\"".$_SESSION['SHOP']['mail_smtp_host']."\" /></td>
            </tr>        
            <tr>
              <td width='30%'>SMTP Port</td>
              <td><input type=\"text\" name=\"mail_smtp_port\" value=\"".$_SESSION['SHOP']['mail_smtp_port']."\" /></td>
            </tr>        
            <tr>
               <td colspan=\"2\" width='30%' height='6px'></td>
            </tr>        
            <tr>
              <td width='30%'>Need Authorisation</td>
              <td><input type='checkbox' {$auth} name='mail_smtp_auth' value='1'></td>
            </tr>        
            <tr>
              <td width='30%'>SMTP Username</td>
              <td><input type=\"text\" name=\"mail_smtp_user\" value=\"".$_SESSION['SHOP']['mail_smtp_user']."\" /></td>
            </tr>        
            <tr>
              <td width='30%'>SMTP Password</td>
              <td><input type=\"password\" name=\"mail_smtp_pass\" value=\"".$_SESSION['SHOP']['mail_smtp_pass']."\" /></td>
            </tr>        
            <tr>
              <td width='30%' valign='top'>SMTP Helo code</td>
              <td><input type=\"text\" name=\"mail_smtp_helo\" value=\"".$_SESSION['SHOP']['mail_smtp_helo']."\" /><br><br></td>
            </tr>        
            
            <tr>
              <td valign='top'>Sendmail path:</td>
              <td><input type=\"text\" name=\"mail_sendmail\" value=\"".$_SESSION['SHOP']['mail_sendmail']."\" /></td>
              </td>
            </tr>
          </table>";
    Install_Form_Buttons ();
    Install_Form_Close ();
  }  
}
?>