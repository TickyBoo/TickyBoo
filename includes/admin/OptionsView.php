<?php
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2008 Christopher Jenkins. All rights reserved.
 *
 * Original Design:
 *	phpMyTicket - ticket reservation system
 * 	Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of fusionTicket.
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
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@noctem.co.uk if any conditions of this licencing isn't 
 * clear to you.
 */

require_once("admin/AdminView.php");
//require_once("classes/Organizer.php");

class OptionsView extends AdminView{

	function draw () { 
	global $_SHOP;
		if($_POST['action']=='update'){
			if(!$this->options_check($_POST,$err)){
				$this->option_form($_POST,$err);
			}else{

				$query="UPDATE `ShopConfig` SET 
	      		shopconfig_lastrun_int="._ESC($_POST['shopconfig_lastrun_int']).",
	      		shopconfig_restime_remind="._ESC($_POST['shopconfig_restime_remind']).",
	      		shopconfig_restime="._ESC($_POST['shopconfig_restime']).",
	      		shopconfig_check_pos="._ESC($_POST['shopconfig_check_pos']).",
	      		shopconfig_delunpaid="._ESC($_POST['shopconfig_delunpaid']).",
	      		shopconfig_posttocollect="._ESC($_POST['shopconfig_posttocollect']).",
	      		shopconfig_user_activate="._ESC((int)$_POST['shopconfig_user_activate']).",
	      		shopconfig_maxres="._ESC($_POST['shopconfig_maxres'])."
	      		limit 1";
				
				if(!ShopDB::query($query)){
					return 0;
				}
				$query="SELECT * FROM `ShopConfig` limit 1";
					if(!$row=ShopDB::query_one_row($query)){
				return 0;
				}
				$this->option_form($row,$err,option_update_title,"update");
				return;
			}
		}else{
			$query="SELECT * FROM `ShopConfig` limit 1";
			if(!$row=ShopDB::query_one_row($query)){
				return 0;
			}
			$this->option_form($row,$err,option_update_title,"update");
			return;
		}
}
function option_form (&$data, &$err){
	global $_SHOP;
	$yesno = array('No'=>'confirm_no', 'Yes'=>'confirm_yes');

	echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
	echo "<tr><td class='admin_list_title' colspan='2'>".con('option_update_title')."</td></tr>";
	echo "<form method='POST' action='{$_SERVER['PHP_SELF']}' enctype='multipart/form-data'>\n";
//  $data['shopconfig_user_activate'] = (int)$data['shopconfig_user_activate'];
	$this->print_field('shopconfig_lastrun',$data, $err,10,10);
	
	$this->print_input('shopconfig_lastrun_int',$data, $err,5,3);
	$this->print_input('shopconfig_restime',$data, $err,25,100);
//	$this->print_input('shopconfig_restime_remind',$data, $err,25,100);
	//this will tell the auto scripts to check POS orders or not.

  $this->print_select_assoc('shopconfig_check_pos',$data,$err,$yesno);
  $this->print_select_assoc('shopconfig_delunpaid',$data,$err,$yesno);

 	echo "</table>\n<br>";
	echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
	echo "<tr><td class='admin_list_title' colspan='2'>".con('option_others_title')."</td></tr>";
    $this->print_select_assoc('shopconfig_user_activate',$data,$err,
     array('0'=>con('act_restrict_all'),
           '1'=>con('act_restrict_later'),
           '2'=>con('act_restrict_w_guest'),
           '3'=>con('act_restrict_quest_only')));

 	$this->print_input('shopconfig_maxres',$data, $err,5,3);
	$this->print_input('shopconfig_posttocollect',$data, $err,25,3);
	$this->print_input('res_delay' ,$data, $err, 5, 3);
  $this->print_input('cart_delay',$data, $err, 5, 3);

 	echo "</table>\n";
  echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
  echo "<input type='hidden' name='action' value='update'>\n";
	
	echo "<tr><td align='center' class='admin_value' colspan='2'>
  	<input type='submit' name='save' value='".save."'> ";
	
	echo "<input type='reset' name='reset' value='".res."'></td></tr>";
	echo "</form>\n";
 	echo "</table>\n";

}
function options_check (&$data, &$err){
	global $_SHOP;
	
	foreach(array('shopconfig_lastrun_int',    'shopconfig_maxres', 'shopconfig_restime',
                'shopconfig_restime_remind', 'shopconfig_posttocollect') as $check) {
    if(empty($data[$check])){
       $err[$check]=mandatory;
    }elseif(!is_numeric($data[$check])){
  		$err[$check]=not_number;
  	}elseif($data[$check]<'0'){
  		$err[$check]=too_low;
    }
	}
	
	return empty($err);

}
}
?>