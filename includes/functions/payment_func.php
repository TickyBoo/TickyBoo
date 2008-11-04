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

function payment_form ($data=null,&$_SHOP_PAY_ERR,$allow_cc=FALSE){
   global $_SHOP;

   echo "<form action='kasse.php' method='post' name='payment'>
   <table class='payment_form' width='500' border='0' cellspacing='0' cellpadding='5'> 
   <input type='hidden' name='action' value='pay'>
   <tr><td colspan='3' class='payment_form_title' align='center'>".pay_methods."</td></tr>";
   $checked='checked';
   if($allow_cc){
      $checked='';
      echo "<tr><td class='payment_form_soustitle'>
            <input type='radio' id='cc_check' name='payment' value='cc'  ".($data['payment']=='cc'?'checked':'')."></td>
            <td class='payment_form_soustitle'><label for='cc_check'>".kreditkarte."</label>
            </td><td class='payment_form_soustitle'>+ ".$_SHOP->cc_charge." CHF </td></tr>
            <tr><td colspan='3'>
            <table width='100%' border='0' cellspacing='1' cellpadding='0'> 
            <tr><td class='payment_form_item'>".type."</td> <td ><select name='cc_type'>
	    <option value='0' ".(isset($data['cc_type'])?'':'selected')."></option>
            <option value='1' ".($data['cc_type']==1?'selected':'').">MasterCard/EUROCARD</option> 
            <option value='2' ".($data['cc_type']==2?'selected':'').">VISA</option> 
            <option value='3' ".($data['cc_type']==3?'selected':'').">American Express</option> 
            <option value='4' ".($data['cc_type']==4?'selected':'').">Diners Card</option>
            </select><span class='error'>".$_SHOP_PAY_ERR['cc_type']."</span></td></tr><tr>
	    <td class='payment_form_item'>".cc_number."</td>

            <td ><input type='text' name='cc_number' size='20' maxlength='16' value='".$data['cc_number']."'>
	    <span class='error'>".$_SHOP_PAY_ERR['cc_number']."</span></td>
            </tr><tr><td class='payment_form_item'><label for='cc_month_sel'>".valable_until."</label></td>

            <td> <select name='cc_month' id='cc_month_sel'> 
            <option value='0' ".(isset($data['cc_month'])?'':'selected')."></option>
	    <option value='1' ".($data['cc_month']==1?'selected':'').">01</option> 
            <option value='2' ".($data['cc_month']==2?'selected':'').">02</option>
            <option value='3' ".($data['cc_month']==3?'selected':'').">03</option>
            <option value='4' ".($data['cc_month']==4?'selected':'').">04</option>
            <option value='5' ".($data['cc_month']==5?'selected':'').">05</option>
            <option value='6' ".($data['cc_month']==6?'selected':'').">06</option>
            <option value='7' ".($data['cc_month']==7?'selected':'').">07</option>
            <option value='8' ".($data['cc_month']==8?'selected':'').">08</option>
            <option value='9' ".($data['cc_month']==9?'selected':'').">09</option>
            <option value='10' ".($data['cc_month']==10?'selected':'').">10</option>
            <option value='11' ".($data['cc_month']==11?'selected':'').">11</option>
            <option value='12' ".($data['cc_month']==12?'selected':'').">12</option>
            </select><span class='error'>".$_SHOP_PAY_ERR['cc_month']."</span>";

      $year=date('Y'); 

      echo "<select name='cc_year'> 
            <option value='0' ".(isset($data['cc_year'])?'':'selected')."></option>";
      for($i=0;$i<5;$i++){ 
        echo "<option value='".($year+$i)."' ".($data['cc_year']==($year+$i)?'selected':'').">".($year+$i)."</option>";
      }

       echo "</select><span class='error'>".$_SHOP_PAY_ERR['cc_year']."</span></td></tr>
             <tr><td class='payment_form_item'>".cc_owner."</td>
             <td><input type='text' name='cc_owner' size='30' value='".$data['cc_owner']."'>
	     <span class='error'>".$_SHOP_PAY_ERR['cc_owner']."</span></td>
             </tr></table></td></tr>";
    }   
    echo "<tr><td><input class='radio_button' type='radio' id='post_check' name='payment' value='post' ".($data['payment']=='mail'?'checked':'')." $checked>
           </td>
	   <td class='payment_form_soustitle' ><label for='post_check'>".per_post."</label>   <span class='payment_form_note' >(".nur_schweiz.")</span></td>
	   <td class='payment_form_soustitle'>+ ".$_SHOP->mail_charge." CHF </td></tr>
           <tr><td colspan='3' align='center'><input type='submit' name='Submit' value='".weiter."'></td> </tr>
            </table></form>";

}

function check_cc(&$data,&$err){

  $cc_type=$data['cc_type'];
  if(!isset($cc_type) or
     !_is_num($cc_type) or
     $cc_type<1 or $cc_type>4)
  {
    $err['cc_type']=invalid;
  }
  
  $cc_number=$data['cc_number'];
  if(!isset($cc_number) or
     !_is_num($cc_number) or
     strlen($cc_number)!=16)
  {
    $err['cc_number']=invalid;
  }
  
  $cc_month=$data['cc_month'];
  if(!isset($cc_month) or
     !_is_num($cc_month) or
     $cc_month<1 or $cc_month>12)
  {
    $err['cc_month']=invalid;
  }

  $cc_year=$data['cc_year'];
  if(!isset($cc_year) or
     !_is_num($cc_year) or
     $cc_year<date('Y') or $cc_year>(date('Y')+4))
  {
    $err['cc_year']=invalid;
  }

  if(!$_SHOP_PAY_ERR['cc_year'] and !$err['cc_month'] and
     $cc_year<=date('Y') and $cc_month<date('n')
  ) {
    $err['cc_month']=outdated;
  }

  $cc_owner=$data['cc_owner'];
  if(!isset($cc_owner) or
     strlen(trim($cc_owner))==0)
  {
    $err['cc_owner']=invalid;
  }

  return empty($err);  
}



function _is_num($s){
  return preg_match('/^([0-9]+)$/', $s);
}
?>