<?php
/*
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 * Copyright (C) 2007-2008 Christopher Jenkins. All rights reserved.
 *
 * Original Design:
 *	phpMyTicket - ticket reservation system
 * 	Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of fusionTicket.
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
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@noctem.co.uk if any conditions of this licencing isn't 
 * clear to you.
 
 */

require_once("admin/AdminView.php");
require_once("classes/Organizer.php");

class OrganizerView extends AdminView{

  function organizer_form (&$data, &$err,$title,$mode){
    global $_SHOP;

    echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
    echo "<tr><td class='admin_list_title' colspan='2'>".$title."</td></tr>";
  	echo "<form method='POST' action='{$_SERVER['PHP_SELF']}' enctype='multipart/form-data'>\n";

    $this->print_input('organizer_name',$data, $err,25,100);
    $this->print_input('organizer_address',$data, $err,25,100);
    $this->print_input('organizer_plz',$data, $err,25,100);
    $this->print_input('organizer_ort',$data, $err,25,100);
    $this->print_input('organizer_state',$data, $err,25,100);
    echo "<tr><td class='admin_name'>" . organizer_country . "</td><td class='admin_value'>";
    $this->print_countrylist('organizer_country', $data['organizer_country'], $err);
    echo "</td></tr>";

    $this->print_input('organizer_phone',$data, $err,25,100 );
    $this->print_input('organizer_fax',$data, $err,25,100 );
    $this->print_input('organizer_email',$data, $err,25,100 );
    $this->print_input('organizer_currency',$data, $err,4,3 );

    $this->print_file('organizer_logo',$data,$err);

  /*  echo "<tr> <td class='admin_name'>".old_password."</td>
           <td class='admin_value'><input type='password' name='old_password' size='10'  maxlength='10'>
  	 <span class='err'>{$err['old_password']}</span></td></tr>
           <tr> <td class='admin_name'>".new_password."</td>
           <td class='admin_value'><input type='password' name='new_password1' size='10'  maxlength='10'><span class='err'>{$err['new_password']}</span></td> </tr>
  	 <tr> <td class='admin_name'>".new_password."</td>
           <td class='admin_value'><input type='password' name='new_password2' size='10'  maxlength='10'></td></tr>";
  */
    echo "<tr><td align='center' class='admin_value' colspan='2'>
           <input type='submit' name='save' value='".save."'> &nbsp;";

    echo "<input type='reset' name='reset' value='".res."'></td></tr>";

    echo "</form></table>\n";
  }



  function draw () {
  global $_SHOP;

    if($_POST['save']){

      if(!$this->organizer_check($_POST,$err)){
        $this->organizer_form($_POST,$err,organizer_update_title,"update");
      }else{

        $query="UPDATE Organizer SET
        organizer_name="._ESC($_POST['organizer_name']).",
        organizer_address="._ESC($_POST['organizer_address']).",
        organizer_plz="._ESC($_POST['organizer_plz']).",
        organizer_ort="._ESC($_POST['organizer_ort']).",
        organizer_email="._ESC($_POST['organizer_email']).",
        organizer_fax="._ESC($_POST['organizer_fax']).",
        organizer_phone="._ESC($_POST['organizer_phone']).",
        organizer_currency="._ESC($_POST['organizer_currency']).",
        organizer_state="._ESC($_POST['organizer_state']).",
        organizer_country="._ESC($_POST['organizer_country'])."
        limit 1";

        if(!ShopDB::query($query)){

          return 0;
        }

        if(!$this->logo_post($_POST, 0)){
          echo "<div class=error>".img_loading_problem."<div>";
        }

       unset($_SESSION['_SHOP_ORGANIZER_DATA']);
       redirect('admin/view_organizer.php', true);
       return;
      }
    }
    $query="SELECT * FROM Organizer limit 1";
    $row=ShopDB::query_one_row($query);
    $this->organizer_form($row,$err,organizer_update_title,"update");
  }

  function logo_post ($data,$organizer_id){
    global $_SHOP;
  	return $this->file_post($data, 0, 'Organizer', 'organizer','_logo');
  }


  function organizer_check (&$data, &$err){
    global $_SHOP;
    if(empty($data['organizer_name'])){$err['organizer_name']=mandatory;}
    if(empty($data['organizer_currency'])){$err['organizer_currency']=mandatory;}
    if(empty($data['organizer_address'])){$err['organizer_address']=mandatory;}
    if(empty($data['organizer_plz'])){$err['organizer_plz']=mandatory;}
    if(empty($data['organizer_ort'])){$err['organizer_ort']=mandatory;}

    //if(empty($data['user_email'])){$err['user_email']=mandatory;}
    if($email=$data['organizer_email']){
      $check_mail= eregi("^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$", $email);
      if(!$check_mail){$err['organizer_email']=not_valid_email;}
    }
    return empty($err);
  }

}
?>