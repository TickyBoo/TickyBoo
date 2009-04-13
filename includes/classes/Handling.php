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


require_once("classes/ShopDB.php");

class Handling {

  var $templates;

  function _ser_templates($templates){
    if(is_array($templates)){
      foreach($templates as $state=>$template){
        $t0[]="$state=$template";
      }
      return implode(',',$t0);
    }  
  }

  function _unser_templates($handling_templates){
    if($handling_templates and $t0=explode(',',$handling_templates)){
      foreach($t0 as $s_t){
        list($state,$template)=explode('=',$s_t);
        $templates[$state]=$template;
      }
      return $templates;
    }
  }

	function _ser_pdf_format(){
		$this->handling_pdf_format=serialize(array($this->pdf_paper_size,$this->pdf_paper_orientation));
	}

	function _unser_pdf_format(){
		if(!empty($this->handling_pdf_format)){
      $pdf_format=unserialize($this->handling_pdf_format);
			if(is_array($pdf_format)){
			  $this->pdf_paper_size=$pdf_format[0];
			  $this->pdf_paper_orientation=$pdf_format[1];
			}
		}
	}


	function _ser_extra(){
    if(!empty($this->extra)){
			$this->handling_extra=serialize($this->extra);
		}
    $this->handling_sale_mode =   '';
		If (is_array($this->sale_mode)) {
      $this->handling_sale_mode = implode(",", array_keys($this->sale_mode));
    }
	}

	function _unser_extra(){
		if(!empty($this->handling_extra)){
      $this->extra=unserialize($this->handling_extra);
		}  else
       $this->extra= array();
//    echo $this->handling_sale_mode,'|';
//		If (is_string($this->handling_sale_mode)) {
      $this->sale_mode = array_fill_keys(explode(",", $this->handling_sale_mode),true);
//      print_r($this->sale_mode);
//    }
//		}
	}

  function _fill ($data, $nocheck=false){
    foreach($data as $k=>$v){
      $this->$k=$v;
    }
    if ($return and $pm = $this->pment()) {
			foreach($arr as $key => $val)
				if(in_array($key, $this->extras))
					$this->extra[$key] = $val;
    }
    if (isset($arr['sale_mode']))
      $this->sale_mode = $arr['sale_mode'];
//    print_r($this);
    return $return ;
  }
	function clear() {
	  parent::clear();
    if (isset($this->_pment)){
      $this->_pment->free;
    }
    if (isset($this->_sment)) {
      $this->_sment->free;
      unset($this->_sment);
    }
	}	
  function load ($handling_id){
    global $_SHOP;
    
    if(isset($_SHOP->_handling_cache[$handling_id])){
      return $_SHOP->_handling_cache[$handling_id];
    }
    
    $query="SELECT * FROM `Handling` WHERE handling_id=".ShopDB::quote($handling_id);
    if($res=ShopDB::query_one_row($query)){
      $hand=new Handling;
      $hand->_fill($res);
      $hand->templates=Handling::_unser_templates($res['handling_email_template']);
			$hand->_unser_extra();
			$hand->_unser_pdf_format();
      $_SHOP->_handling_cache[$handling_id]=&$hand;
      return $hand;
    }
  }
	
  function load_all ($handling_sale_mode=''){
    global $_SHOP;
    if($handling_sale_mode){
      $sale="where handling_sale_mode=".ShopDB::quote($handling_sale_mode);
    }
    
    $query="select * from Handling $sale";
    if($res=ShopDB::query($query)){
      while($data=shopDB::fetch_array($res)){
        $hand=new Handling;
        $hand->_fill($data);
        $hand->templates=Handling::_unser_templates($data['handling_email_template']);
				$hand->_unser_extra();
				$hand->_unser_pdf_format();
        $hands[]=$hand;
      }	
    }
    return $hands;
  }
  
  function save (){
    global $_SHOP;
    
		$this->_ser_extra();
		$this->_ser_pdf_format();
    $this->handling_email_template = $this->_ser_templates($this->handling_email_template);
		
    $query=
    $this->_set('handling_alt').
    $this->_set('handling_alt_only').
    $this->_set('handling_fee_fix',0,TRUE).
    $this->_set('handling_fee_percent',0,TRUE).
    $this->_set('handling_email_template',Handling::_ser_templates($this->templates)).
    $this->_set('handling_pdf_template','',TRUE).
    $this->_set('handling_pdf_ticket_template','',TRUE).
    $this->_set('handling_pdf_format').
    $this->_set('handling_html_template').
    $this->_set('handling_sale_mode','',TRUE).
    $this->_set('handling_text_payment').
    $this->_set('handling_text_shipment').
    $this->_set('handling_delunpaid').
    $this->_set('handling_expires_min').
	$this->_set('handling_extra');
	
    if($query){
      $query=substr($query,0,-1);
    }
    if(!$this->handling_id){
      $query="INSERT INTO `Handling` 
      SET handling_payment=".ShopDB::quote($this->handling_payment).",
      handling_shipment=".ShopDB::quote($this->handling_shipment).",
      $query";
    }else{
     $query="update Handling 
      set $query 
      where handling_id=".ShopDB::quote($this->handling_id);
    }       
    
    if(ShopDB::query($query)){
      if(!$this->handling_id){
        $this->handling_id=ShopDB::insert_id();
      }
      return $this->handling_id;
    }
  }

  function CheckValues($arr) {
    $ok = parent::CheckValues($arr);
    return $this->extra_check($arr) and $ok;
  }

  function delete (){
    global $_SHOP;
    if (!$id) $id = $this->handling_id;
		$query="SELECT count(order_id) AS count FROM `Order` WHERE order_handling_id=".ShopDB::quote($id);
		if($res=ShopDB::query_one_row($query) and $res['count']==0){
			$query="DELETE FROM Handling WHERE handling_id=".ShopDB::quote($this->handling_id)." limit 1";
			ShopDB::query($query);
		}else{
		  echo "<div class=err>".in_use."</div>";
			return;
		} 			
  }
// Calculates fee for tickets
  function calculate_fee ($total){
    return round($this->handling_fee_fix+($total/100.00)*$this->handling_fee_percent,2);
  } 

  function handle ($order,$new_state,$old_state='',$field=''){
    global $_SHOP;//print_r($this);

    $ok=TRUE;
	
    if($template_name=$this->templates[$new_state] and $order->user_email){
      $te=new TemplateEngine;
      $tpl=&$te->getTemplate($template_name);
  
      $email = new htmlMimeMail();
      $order_d=(array)$order;
      $link= $_SHOP->root."index.php?personal_page=orders&id=";
      $order_d['order_link']=$link;
      $tpl->build($email,$order_d,$_SHOP->lang);

      if(!$email->send($tpl->to)){
        user_error($email->errors);
        $ok=FALSE;
      }
    }

		if($ok and $pm = $this->pment()){
			if(method_exists($pm, 'on_handle')){
				$ok=$pm->on_handle($order,$new_state,$old_state,$field);
			}
		}

		if($ok and $sm = $this->sment()){
			if(method_exists($sm,'on_handle')){
				$ok= $sm->on_handle($order,$new_state,$old_state,$field);
			}
		}
		return ($ok);  
  }

	function on_order_delete($order_id){
    $ok = true;
		if($ok and $pm = $this->pment()){
			if(method_exists($pm,'on_order_delete')){
				$ok=$pm->on_order_delete($order_id);
      }
    }
		if($ok and $sm = $this->sment()){
			if(method_exists($sm,'on_order_delete')){
				$ok= $sm->on_order_delete($order_id);
			}
    }
    return $ok;
	}
	
  // Loads default extras for payment method eg."pm_paypal_View.php"
  function extra_init(){
  	if($pm=$this->pment()){
      $pm->init();
  	} else {
    	switch ($this->handling_payment) {
        case "invoice"  : $this->handling_text_payment=  "Invoice";
          break;
        case "entrance" : $this->handling_text_payment=  "At the entrance";
          break;
        case "cash"     : $this->handling_text_payment=  "Cash";
          break;
  	  }
    }
  	if($sm=$this->sment()){
      $sm->init();
  	} else {
    	switch ($this->handling_shipment) {
        case "email"    : $this->handling_text_shipment=  "By e-mail";
          break;
        case "post"     : $this->handling_text_shipment=  "By post";
          break;
        case "entrance" : $this->handling_text_shipment=  "At the entrance";
          break;
        case "sp"       : $this->handling_text_shipment=  "Salepoint";
          break;
    	}
    }
  }

  function extra_check(&$data){
  	if($pm=$this->pment()){
      return $pm->check($data, $this->errors);
  	} else return true;
  }

  function admin_view(){
  	if($pm=$this->pment()){
      return $pm->admin_view();
  	}
  }

  function admin_form(){
  	if($pm=$this->pment()){
      return $pm->admin_form();
  	}
  }

  function on_confirm($order) {
  	if($pm=$this->pment()){
      return $pm->on_confirm($order);
  	}
  }

  function on_submit(&$order, $appoved, &$err) {
  	if($pm=$this->pment()){
      return $pm->on_submit($order, $appoved, $err);
  	}
  }

  function on_notify(&$order) {
  	if($pm=$this->pment()){
      return $pm->on_notify($order);
  	}
  }

  function pment() {
    if (!isset($this->handling_payment) or (!$this->handling_payment)) return;
    $file = INC."classes".DS."payments".DS."eph_".$this->handling_payment.".php";
    if (file_exists($file)){
      if (!isset($this->_pment)){
        $name = "EPH_".$this->handling_payment;
        $this->_pment = new $name($this);
        $this->extras = $this->_pment->extras;
      }
    }
    return $this->_pment;
  }

  function sment() {
    if (!isset($this->handling_shipment) or (!$this->handling_shipment)) return;
    $file = INC."classes".DS."shipments".DS."esm_{$this->handling_shipment}.php";
    if (!isset($this->_sment) and file_exists($file)) {
      require_once ($file);
      $name = "ESM_{$this->handling_shipment}";
      $this->_sment = new $name($this);
    }
    return $this->_sment;
  }

  function get_payment (){
    $types=array('','invoice','entrance','cash');
    $dir = INC.'classes'.DS.'payments';
	  if ($handle = opendir($dir)) {
		  while (false !== ($file = readdir($handle))){
        if ($file != "." && $file != ".." && !is_dir($dir.$file) && preg_match("/^epm_(.*?\w+).php/", $file, $matches)) {
          $types[] =  $matches[1];
        }
      }
      closedir($handle);
  	}
    return $types;
  }


  function get_shipment (){
  	$like= " LIKE  'handling_shipment'";
    $query="SHOW  COLUMNS  FROM Handling {$like}";
    if(!$res=ShopDB::query_one_row($query)){return;}
    $types=explode("','",preg_replace("/(enum|set)\('(.+?)'\)/","\\2",$res[1]));
    return $types;
  }

  	function get_handlings ($selectid=0, $include =''){
		$sqli="SELECT handling_id,handling_payment,handling_shipment FROM `Handling` WHERE handling_id!='1'"; 
		if(!$result=ShopDB::query($sqli)){echo("Error"); return;}	
		$options= array();
    if ($include)
			$options["1"] = $include;
		
		while ($row=shopDB::fetch_array($result)) { 
			$id=$row["handling_id"];
			$selected = ($id==$selectid) ? ' selected="selected"' : '';  
			$payment=$row["handling_payment"];
			$shipping=$row["handling_shipment"];
			$options["{$id}"] = $id." - ".con($payment)." - ".con($shipping);
		}
		return $options;
	}


  function _set ($name,$value=0,$mandatory=FALSE){

    if($value){
      $val=$value;
    }else{
      $val=$this->$name;
    }
    if($val or $mandatory){
      return $name.'='.ShopDB::quote($val).',';   
    }
  }


	
		function _myErrorHandler($errno, $errstr, $errfile, $errline) {
			if($errno!=2){
				echo "$errno $errstr $errfil $errline";
			}	
		}

		function _dyn_load($name){

	
		set_error_handler(array(&$this,'_myErrorHandler'));
		$res=include_once($name);
		restore_error_handler();
	
		return $res;
	}	
}
?>