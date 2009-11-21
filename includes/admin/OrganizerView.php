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

require_once("admin/AdminView.php");
require_once("classes/Orgenizer.php");

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

    echo "<tr><td align='center' class='admin_value' colspan='2'>
           <input type='submit' name='save' value='".save."'> &nbsp;";

    echo "<input type='reset' name='reset' value='".res."'></td></tr>";

    echo "</form></table>\n";
  }

  function draw () {
  global $_SHOP;
    $org = Organizer::load(); print_r($org);
    if($_POST['save']){
      if(!$this->organizer_check($_POST,$err)){
        $this->organizer_form($_POST, $err, con('organizer_update_title'), "update");
      }else{
        $org->fillPost();
        $org->save();

        if(!$this->logo_post($_POST, 0)){
          $err['organizer_logo'] = con('img_loading_problem');
        }
      }
    }
    $row=(ARRAY)$org;
    $_SESSION['_SHOP_ORGANIZER_DATA'] = $row;
    $this->organizer_form($row, $err, con('organizer_update_title'), "update");
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
      $check_mail = preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i',$email );
      if(!$check_mail){$err['organizer_email']=not_valid_email;}
    }
    return empty($err);
  }
}
?>