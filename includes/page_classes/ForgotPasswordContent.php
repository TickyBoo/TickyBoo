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

require_once("page_classes/AUIComponent.php");
class ForgotPasswordContent extends AUIComponent{
  function draw (){
   if($_POST['submit'] and $_POST['email']){
     $query="select * from auth,User where username='{$_POST['email']}' and auth.user_id=User.user_id";
     if(!$row=ShopDB::query_one_row($query)){
       echo user_dont_exist;
       $this->printForm($_SERVER["PHP_SELF"],$_POST);
     }else{
       $pwd = substr(md5(uniqid(rand())), 0, 8);
       $pwd_md5=md5($pwd);
       
       $query="UPDATE auth SET password='$pwd_md5' WHERE username='{$_POST['email']}'";
       if(ShopDB::query($query)){
         require_once("classes/TemplateEngine.php");
         require_once("classes/htmlMimeMail.php");

         $engine= new TemplateEngine();
	       $tpl=$engine->getTemplate('forgot_passwd');
         $email=&new htmlMimeMail();

         $row['new_password']=$pwd;
	       $tpl->build($email,$row);
       
         if(!$email->send($tpl->to)){
           user_error($email->errors);
         }else{        
           echo passwd_changed;
	 }	 
       }else{
         echo passwd_not_changed;
       }
     }
   }else{
     $this->printForm($_SERVER["PHP_SELF"],$_POST);
   } 
  
  }
  
  function printForm ($action,$data){
    echo "<form action='$action' method='post'>";
    echo "<table class='forgot_pass' width='100%' align='center'>";
    echo "<tr><td class='forgot_title' colspan='2' align='center'>".forgot_pass."</td></tr>";
    echo "<tr><td class='forgot_soustitle' colspan='2'>".forgot_pass_note."</td></tr>";

    echo "<tr><td class='forgot_item'>".email."</td>
          <td class='forgot_value'><input type='text' name='email' value='{$data['email']}' size='30'></td></tr>";
    echo "<tr><td class='forgot_value' colspan='2' align='center'>
           <input type='submit' name='submit' 
	   value='".send_password."'></td></tr>";
    echo "</table>";
    echo "</form>";
  }
}
?>