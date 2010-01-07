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
require_once("admin/class.adminview.php");

class HandlingView extends AdminView{

  function table (){
		global $_SHOP;
		$alt=1;
		echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='2'>\n";
		echo "<tr><td class='admin_list_title' colspan='4' align='left'>".con('handling_title')."</td>\n";
    echo "<td class='admin_list_title' colspan='2' align='right'>".$this->show_button("{$_SERVER['PHP_SELF']}?action=add","add",3)."</td>";
    echo "</tr>\n";
		if($hands=Handling::loadAll()){
			foreach($hands as $hand){
				$handling_mode_pos=(strpos($hand->handling_sale_mode,'sp')!==false)?'pos':'&nbsp;';
				$handling_mode_web=(strpos($hand->handling_sale_mode,'www')!==false)?'web':'&nbsp;';

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
  				echo "<td width='30' class='admin_list_item'>$handling_mode_web</td>\n";
  				echo "<td width='30' class='admin_list_item'>$handling_mode_pos</td>\n";

          echo "<td class='admin_list_item' width='45' align='right' nowrap><nowrap>";
          echo $this->show_button("{$_SERVER['PHP_SELF']}?action=edit&handling_id={$hand->handling_id}","edit",2);
          echo $this->show_button("javascript:if(confirm(\"".con('delete_item')."\")){location.href=\"{$_SERVER['PHP_SELF']}?action=remove&handling_id={$hand->handling_id}\";}","remove",2,array('tooltiptext'=>"Delete {$row['ort_name']}?"));
          echo "</nowrap></td>\n";
			 	}
				echo "</tr>";
				$alt=($alt+1)%2;
			 }
		 }
		echo "</table>\n";
  }

  function form ($data, $err, $title){
		global $_SHOP;


		echo "<form method='POST' action='{$_SERVER['PHP_SELF']}'>\n";
		echo "<input type='hidden' name='action' value='save'/>\n";
		if($data['handling_id']){
      $h = Handling::load($data['handling_id']);
			echo "<input type='hidden' name='handling_payment' value='{$data['handling_payment']}'/>\n";
			echo "<input type='hidden' name='handling_shipment' value='{$data['handling_shipment']}'/>\n";
			echo "<input type='hidden' name='handling_id' value='{$data['handling_id']}'/>\n";
		}else{
      $h = new Handling();
		}

  	$this->form_head($title);

		if($data['handling_id']){
      $this->print_field('handling_payment', con($data['handling_payment']));
      $this->print_field('handling_shipment', con($data['handling_shipment']));
		}else{
      $this->print_select_assoc('handling_payment', $data, $err, Handling::getPayment());
      $this->print_select_assoc('handling_shipment', $data, $err, Handling::getShipment());
		}

		if( $data['sale_mode']['sp']){$chk_sp='checked';}
		if($data['sale_mode']['www']){$chk_www='checked';}
		echo "<tr><td class='admin_name'>".con('handling_sale_mode')."</td>
			<td class='admin_value'>
        <input type='checkbox' name='sale_mode[www]' value='www' $chk_www> ".con('www')."&nbsp;
        <input type='checkbox' name='sale_mode[sp]' value='sp' $chk_sp> ".con('sp')."&nbsp;
			</td></tr>";


		//This is for the alt payments if nothing is slected alt wont be used when close to an event.
    $this->print_select_assoc('handling_alt',$data,$err,
           Handling::getHandlings(con('handling_no_alt')));

		//This to ask if the handling is alturnative only this could be an auto proccess but then you would only be
		//able to use the handling when close to the event.
    $this->print_select_assoc('handling_alt_only',$data,$err,array('No'=>'no','Yes'=>'yes'));
		$this->print_input('handling_expires_min',$data,$err,10);
		$this->print_input('handling_fee_fix',$data,$err,5,10);
		$this->print_input('handling_fee_percent',$data,$err,5,10);
    
    //print_r($data['handling_email_template']);

		$temps=explode(",",$data['handling_email_template']);
		foreach($temps as $temp){
			$t=explode("=",$temp);
			$data["handling_email_template_{$t[0]}"]=$t[1];
		}

		$this->print_select_tpl('handling_email_template_ord',"'email','swift'",$data,$err, true);
		$this->print_select_tpl('handling_email_template_payed',"'email','swift'",$data,$err, true);
    $this->print_select_tpl('handling_email_template_send',"'email','swift'",$data,$err, true);

		$this->print_select_tpl('handling_pdf_template',"'pdf2'",$data,$err);
		$this->print_select_tpl('handling_pdf_ticket_template',"'pdf2'",$data,$err);
    $this->print_select_assoc('handling_only_manual_send',$data,$err,array('No'=>'no','Yes'=>'yes'));
//		$this->print_paper_format('pdf_paper',$data,$err);

		if($data['handling_id']){
			$this->print_large_area('handling_text_payment',$data,$err,3,92,'');
			$this->print_large_area('handling_text_shipment',$data,$err,3,92,'');
			$this->print_large_area('handling_html_template',$data,$err,10,92,'');
		  $this->extra_form($h, $data, $err);
		}

    $this->form_foot(2,$_SERVER['PHP_SELF']);
  }


  function draw (){
    global $_SHOP;
      if($_GET['action']=='add'){
        $hand= new Handling(true);
        $this->form((array)$hand, null, con('handling_add_title'));
        return 0;
		  }elseif($_GET['action']=='edit'){
  		  $hand=Handling::load($_GET["handling_id"]);
  		  $this->form((array)$hand, null, con('payment_update_title'));
 			  return 0;
		  }elseif($_POST['action']=='save'){
        $new = false;
        if(!$hand=Handling::load($_POST["handling_id"])){
          $hand= new Handling(true); $new = true;
        }
        var_dump($ok = $hand->fillPost());
        if(!$ok || !$hand->saveEx()){
    		  $this->form($_POST, null, con('handling_update_title')); //handling_add_title
    			return 0;
        } elseif ($new){
          $this->form((array)$hand, null, con('handling_update_title'));
          return 0;
        }else{
          addNotice('save_successful');
        }

		}elseif($_GET['action']=='remove' and $_GET['handling_id']>0){
   		$hand=new Handling();
      $hand->handling_id=$_GET['handling_id'];
      $hand->delete();
  	}
  	$this->table();
	}


  function extra_form($hand, &$data, &$err){
    Global $_SHOP;

    $extras = $hand->admin_form();
    if ( $extras) {
      require_once('smarty/Smarty.class.php');
      require_once('classes/smarty.gui.php');

      $smarty = new Smarty;
  //    $smarty->plugins_dir = array("plugins", INC . "shop_plugins".DS);
      $smarty->plugins_dir  = array("plugins".DS, $_SHOP->includes_dir . "shop_plugins".DS);

      $smarty->compile_id   = 'AdminHandling_'.$_SHOP->lang;
      $smarty->compile_dir  = $_SHOP->tmp_dir; // . '/web/templates_c/';
      $smarty->cache_dir    = $_SHOP->tmp_dir; // . '/web/cache/';
      $smarty->config_dir   = INC . 'lang'.DS;

      $gui   = new Gui_smarty($smarty);
      $gui->guidata   = $data;
      $gui->gui_name  = 'admin_name';
  	  $gui->gui_value = 'admin_value';

      $smarty->my_template_source = $extras;
      $smarty->display('text:'. $hand->handling_payment );
    }
  }

  function print_select_tpl ($name, $type, &$data, &$err, $inclPdf=false){
    global $_SHOP;

    $query="SELECT template_name FROM Template WHERE template_type IN ({$type}) ORDER BY template_name";
    if(!$res=ShopDB::query($query)){
      return FALSE;
    }

    $sel[$data[$name]]=" selected ";

    echo "<tr><td class='admin_name'  width='40%'>".con($name)."</td>
    <td class='admin_value'><nowrap>
     <select name='$name'>
     <option value=''></option>\n";

    while($v=shopDB::fetch_row($res)){
      $value=htmlentities($v[0],ENT_QUOTES);
      echo "<option value='$value' ".$sel[$v[0]].">{$v[0]}</option>\n";
    }

    echo "</select>";
    if ($inclPdf) {
      echo "</nowrap>".printMsg($name, $err)."</td></tr>\n";
      
      echo "<tr><td class='admin_name'  width='40%'>".con($name."_incl_pdf")."</td>";
      echo "<td class='admin_value'>";
      
      //Include Inv/Ord
      $checked = ($data["{$name}_incl_inv_pdf"]==1)?"selected":"";
      echo "&nbsp;&nbsp;".con("{$name}_incl_inv_pdf")."
             <select name='{$name}_incl_inv_pdf'>
                <option value='0'>".con('confirm_no')."</option>\n
                <option value='1' $checked>".con('confirm_yes')."</option>\n";
      echo "</select>";
      
      //Include Tickets
      $checked = ($data["{$name}_incl_ticket_pdf"]==1)?"selected":"";
      echo "&nbsp;&nbsp;".con("{$name}_incl_ticket_pdf")."
             <select name='{$name}_incl_ticket_pdf'>
                <option value='0'>".con('confirm_no')."</option>\n
                <option value='1' $checked>".con('confirm_yes')."</option>\n";
      echo "</select>";
      
      echo "</td></tr>\n";

    }else{
      echo "</nowrap>".printMsg($name, $err)."</td></tr>\n";
    }
  }

}
?>