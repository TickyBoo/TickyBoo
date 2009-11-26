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
require_once("admin/AdminView.php");

class HandlingView extends AdminView{
  function print_select_tpl ($name,$type,&$data,&$err){
    global $_SHOP;

    $query="SELECT template_name FROM Template WHERE template_type='{$type}' ORDER BY template_name";
    if(!$res=ShopDB::query($query)){
      return FALSE;
    }

    $sel[$data[$name]]=" selected ";

    echo "<tr><td class='admin_name'  width='40%'>".con($name)."</td>
    <td class='admin_value'>
     <select name='$name'>
     <option value=''></option>\n";

    while($v=shopDB::fetch_row($res)){
      $value=htmlentities($v[0],ENT_QUOTES);
      echo "<option value='$value' ".$sel[$v[0]].">{$v[0]}</option>\n";
    }

    echo "</select><span class='err'>{$err[$name]}</span>
    </td></tr>\n";
  }

  	function handling_check (&$hand, &$data, &$err){
     		global $_SHOP;
     		if(empty($data['handling_pdf_template'])){$err['handling_pdf_template']=mandatory;}

   		if($data['handling_sale_mode_a']){
  			$data['handling_sale_mode']=implode(',',$data['handling_sale_mode_a']);
  	 	}

  	 	$this->save_paper_format('pdf_paper',$data,$err);
  	 	$hand->admin_check($data, $err);
      if ($data['handling_id']) {
   	  	if(empty($data['handling_text_payment'])){$err['handling_text_payment']=mandatory;}
   		  if(empty($data['handling_text_shipment'])){$err['handling_text_shipment']=mandatory;}
      }

     		return empty($err);
    	}

    function handling_form ($data, $err, $title){
  		global $_SHOP;


  		echo "<form method='POST' action='{$_SERVER['PHP_SELF']}'>\n";
  		echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
  		echo "<tr><td class='admin_list_title' colspan='2'>".$title."</td></tr>";

  		if($data['handling_id']){
        $h = handling::load($data['handling_id']);
  			echo "<tr><td class='admin_name'>".con('handling_payment')."</td>
  			<td class='admin_value'>".con($data['handling_payment'])."</td></tr>";
  			echo "<tr><td class='admin_name'>".con('handling_shipment')."</td>
  			<td class='admin_value'>".con($data['handling_shipment'])."</td></tr>";
  		}else{
        $h = new Handling();
  			echo "<tr><td class='admin_name'  width='40%'>".con('handling_payment')."</td>
     	  <td class='admin_value'> <select name='handling_payment'>";
  			$paylist=Handling::getPayment();

    		$sel = array($data["handling_payment"]=>" selected ");
  			foreach($paylist as $k=>$v){
  				 echo "<option value='$v' ".$sel[$v].">".con($v)."</option>\n";
  			}
  			echo "</select><span class='err'>{$err["handling_payment"]}</span></td></tr>\n";


  //			$sel[$data["handling_shipment"]]=" selected ";

  			echo "<tr><td class='admin_name'  width='40%'>".con(handling_shipment)."</td>
  			<td class='admin_value'><select name='handling_shipment'>";
  			$sendlist=Handling::getShipment();
   	  	$sel = array($data["handling_shipment"]=>" selected ");
  			foreach($sendlist as $k=>$v){
  				echo "<option value='$v' ".$sel[$v].">".con($v)."</option>\n";
  			}
  			echo "</select><span class='err'>{$err["handling_shipment"]}</span></td></tr>\n";
  		}
  		if( $data['sale_mode']['sp']){$chk_sp='checked';}

  		if($data['sale_mode']['www']){$chk_www='checked';}

  		echo "<tr><td class='admin_name'>".con(handling_sale_mode)."</td>
  			<td class='admin_value'>
          <input type='checkbox' name='sale_mode[www]' value='www' $chk_www>
  			         ".con(www)."&nbsp;
          <input type='checkbox' name='sale_mode[sp]' value='sp' $chk_sp>
  			         ".con(sp)."&nbsp;
  			</td></tr>";


  		//This is for the alt payments if nothing is slected alt wont be used when close to event.
      $this->print_select_assoc('handling_alt',$data,$err,
             Handling::getHandlings(con('handling_no_alt')));

  		//This to ask if the handling is alturnative only this could be an auto proccess but then you would only be
  		//able to use the handling when close to the event.
  		$sel[$data["handling_alt_only"]]=" selected ";
  		echo "<tr><td class='admin_name'  width='40%'>Only Alt Handling Method</td>
  		<td class='admin_value'><select name='handling_alt_only'>";
  		echo "<option value='No' ".$sel['No'].">No</option>\n";
  		echo "<option value='Yes' ".$sel['Yes'].">Yes</option>\n";
  		echo "</select><span class='err'>{$err["handling_delunpaid"]}</span></td></tr>\n";
  		$this->print_input('handling_expires_min',$data,$err,10);
  		$this->print_input('handling_fee_fix',$data,$err,5,10);
  		$this->print_input('handling_fee_percent',$data,$err,5,10);

  		$temps=explode(",",$data['handling_email_template']);
  		foreach($temps as $temp){
  			$t=explode("=",$temp);
  			$data["handling_email_template_{$t[0]}"]=$t[1];
  		}

  		$this->print_select_tpl('handling_email_template_ord','email',$data,$err);
  		$this->print_select_tpl('handling_email_template_send','email',$data,$err);
  		$this->print_select_tpl('handling_email_template_payed','email',$data,$err);

  		$this->print_select_tpl('handling_pdf_template','pdf2',$data,$err);
  		$this->print_select_tpl('handling_pdf_ticket_template','pdf2',$data,$err);
  //		$this->print_paper_format('pdf_paper',$data,$err);

  		if($data['handling_id']){
  			$this->print_large_area('handling_text_payment',$data,$err,3,92,'');
  			$this->print_large_area('handling_text_shipment',$data,$err,3,92,'');
  			$this->print_large_area('handling_html_template',$data,$err,10,95,'');
  		  $this->extra_form($h, $data, $err);
  		}

  		if($data['handling_id']){
  			echo "<input type='hidden' name='handling_payment' value='{$data['handling_payment']}'/>\n";
  			echo "<input type='hidden' name='handling_shipment' value='{$data['handling_shipment']}'/>\n";
  			echo "<input type='hidden' name='handling_id' value='{$data['handling_id']}'/>\n";
  			echo "<input type='hidden' name='action' value='update'/>\n";
  		}else{
  			echo "<input type='hidden' name='action' value='insert'/>\n";
  		}

  		echo "<tr><td align='center' class='admin_value' colspan='2'>
  			<input type='submit' name='submit' value='".save."'>
  		  <input type='reset' name='reset' value='".res."'></td></tr>";

  		echo "</table></form>\n";

  		echo "<center><a class='link' href='{$_SERVER['PHP_SELF']}'>".admin_list."</a></center>";
    }

    function handling_list (){
  		global $_SHOP;
  		$pay=Handling::get_payment ();
  		$send=Handling::get_shipment();
  		$alt=1;
  		echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='4'>\n";
  		echo "<tr><td class='admin_list_title' colspan='8' align='center'>".handling_title."</td></tr>\n";
  		if($hands=Handling::loadAll()){
  			foreach($hands as $hand){

  				$handling_sale_mode=str_replace('sp','pos',$hand->handling_sale_mode);
  				$handling_sale_mode=trim(str_replace('www','web',$handling_sale_mode));

  				echo "<tr class='admin_list_row_$alt'>";
  				if($hand->handling_id==1){
  //				 	echo  "<td  class='admin_list_item'>".reserved."</td>";
  //				 	echo "<td class='admin_list_item'>".reserved."</td>\n";
  //				 	echo "<td class='admin_list_item' colspan=3>&nbsp;</td>";
  				}else{
  					echo "<td class='admin_list_item'>".con($hand->handling_payment)."</td>";
  					echo "<td class='admin_list_item'>".con($hand->handling_shipment)."</td>\n";
    				echo "<td class='admin_list_item' align='right'>";
      				$perc=$hand->handling_fee_percent;
      				$fixe=$hand->handling_fee_fix;
      				if($perc > 0 ){
      					echo $perc." % ";
      				}
      				if ($perc >0 and $fixe >0 ){
      					echo "+";
      				}
      				if($fixe > 0){
      					echo $fixe." ".$_SHOP->organizer_data->currency;
      				}
    				echo "</td>\n";
    				echo "<td class='admin_list_item'>$handling_sale_mode</td>\n";
    				echo "<td class='admin_list_item' width='60' align='right'>
                    <a class='link' href='view_handling.php?action=edit&handling_id={$hand->handling_id}'><img src='images/edit.gif' border='0' alt='".edit."' title='".edit."'></a>\n
    				        <a class='link' href='javascript:if(confirm(\"".delete_item."\")){location.href=\"view_handling.php?action=remove&handling_id={$hand->handling_id}\";}'><img src='images/trash.png' border='0' alt='".remove."' title='".remove."'></a></td>";
  			 	}
  				echo "</tr>" . test;
  				$alt=($alt+1)%2;
  			 }
  		 }


  		echo "</table>\n";

  		echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}?action=add'>".add."</a></center>";
    }

  	function draw (){
  		global $_SHOP;
    		if($_GET['action']=='remove' and $_GET['handling_id']>0){
      		$hand=new Handling();
  		    $hand->handling_id=$_GET['handling_id'];
  		    $hand->delete();
  		    $this->handling_list();
    		}elseif($_GET['action']=='edit'){
      		$hand=Handling::load($_GET["handling_id"]);
      		$this->handling_form((array)$hand, $err, payment_update_title);
    		}elseif($_POST['action']=='update'){
    			$hand=Handling::load($_POST["handling_id"]);

    			if(!$this->handling_check($hand, $_POST, $err)){
        			$this->handling_form($_POST, $err, handling_update_title);
        			return 0;
    			}

  		  	$hand->_fill($_POST);
  		  	$hand->templates['ord']=$_POST['handling_email_template_ord'];
  		  	$hand->templates['send']=$_POST['handling_email_template_send'];
  		  	$hand->templates['payed']=$_POST['handling_email_template_payed'];
  		  	$hand->save();

  		  	$this->handling_list();
   			// adding new payments here then they are compiled.
    		}elseif($_POST['action']=='insert'){
      		$hand=new Handling();
      		if(!$this->handling_check($hand, $_POST, $err)){
        			$this->handling_form($_POST,$err,handling_add_title);
      		}else{
  		      $hand->_fill($_POST);
  		      $hand->templates['ord']=$_POST['handling_email_template_ord'];
  		      $hand->templates['send']=$_POST['handling_email_template_send'];
  		      $hand->templates['payed']=$_POST['handling_email_template_payed'];

  	  	  	  //Adds the default fields
  	  		  $this->extra_init($hand);
  	  		  $hand->admin_init();
    			// The new handling method is saved
    			  $id=$hand->save();
    			  $hand_a=(array)$hand;
    			  $this->handling_form($hand_a, $err, handling_add_title);
     			}
  		}elseif($_GET['action']=='add'){
  			$this->handling_form(array(), $err, handling_add_title);
  		}else{
    		$this->handling_list();
  		}
  	}

  function extra_form($hand, &$data, &$err){
    Global $_SHOP;

    $extras = $hand->admin_form();
    if ( $extras) {
      require_once('smarty/Smarty.class.php');
      require_once('classes/gui_smarty.php');

      $smarty = new Smarty;
  //    $smarty->plugins_dir = array("plugins", INC . "shop_plugins".DS);
      $smarty->plugins_dir  = array("plugins".DS, $_SHOP->includes_dir . "shop_plugins".DS);

      $smarty->compile_id   = 'AdminHandling_'.$_SHOP->lang;
      $smarty->compile_dir  = $_SHOP->tmp_dir; // . '/web/templates_c/';
      $smarty->cache_dir    = $_SHOP->tmp_dir; // . '/web/cache/';
      $smarty->config_dir   = INC . 'lang'.DS;

      $gui   = new Gui_smarty($smarty);
      $gui->guidata   = $data;
      $gui->errors    = $err;
      $gui->gui_name  = 'admin_name';
  	  $gui->gui_value = 'admin_value';

      $smarty->my_template_source = $extras;
      $smarty->display('text:'. $hand->handling_payment );
    }
  }

  function extra_init(&$hand){
    $hand->handling_text_shipment= con($hand->handling_shipment);
  }
}
?>