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
		}
	}
	
  function load ($handling_id){
    global $_SHOP;
    
    if(isset($_SHOP->_handling_cache[$handling_id])){
      return $_SHOP->_handling_cache[$handling_id];
    }
    
    $query="SELECT * FROM `Handling` WHERE handling_id=".ShopDB::quote($handling_id)." AND handling_organizer_id='{$_SHOP->organizer_id}'";
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
      $sale="AND handling_sale_mode=".ShopDB::quote($handling_sale_mode);
    }
    
    $query="select * from Handling where handling_organizer_id='{$_SHOP->organizer_id}' $handling_sale_mode";
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
      $query,
      handling_organizer_id='{$_SHOP->organizer_id}'";
    }else{
     $query="update Handling 
      set $query 
      where handling_id=".ShopDB::quote($this->handling_id)." 
      and handling_organizer_id='{$_SHOP->organizer_id}'";
    }       
    
    if(ShopDB::query($query)){
      if(!$this->handling_id){
        $this->handling_id=ShopDB::insert_id();
      }
      return $this->handling_id;
    }
  }

  function delete (){
    global $_SHOP;

		$query="SELECT count(order_id) AS count FROM `Order` WHERE order_handling_id=".ShopDB::quote($this->handling_id);
		if($res=ShopDB::query_one_row($query) and $res['count']==0){
		
			$query="DELETE FROM Handling WHERE handling_id=".ShopDB::quote($this->handling_id)." AND handling_organizer_id='{$_SHOP->organizer_id}' limit 1";
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
      require_once("classes/htmlMimeMail.php");
      require_once("classes/TemplateEngine.php");
  
      $te=new TemplateEngine;
      $tpl=&$te->getTemplate($template_name,$_SHOP->organizer_id);
  
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
	
  function get_payment (){
  	$like= " LIKE  'handling_payment'";
    $query="SHOW  COLUMNS  FROM Handling {$like}";
    if(!$res=ShopDB::query_one_row($query)){return;}
    $types=explode("','",preg_replace("/(enum|set)\('(.+?)'\)/","\\2",$res[1]));
    return $types;
  }

  function get_shipment (){
  	$like= " LIKE  'handling_shipment'";
    $query="SHOW  COLUMNS  FROM Handling {$like}";
    if(!$res=ShopDB::query_one_row($query)){return;}
    $types=explode("','",preg_replace("/(enum|set)\('(.+?)'\)/","\\2",$res[1]));
    return $types;
  }
  	function get_handlings ($selectid=0){
		$sqli="SELECT handling_id,handling_payment,handling_shipment FROM `Handling` WHERE handling_id!='1'"; 
		if(!$result=ShopDB::query($sqli)){echo("Error"); return;}	
		$options=""; 
		
		while ($row=shopDB::fetch_array($result)) { 
			$id=$row["handling_id"];
			$selected = ($id==$selectid) ? ' selected="selected"' : '';  
			$payment=$row["handling_payment"];
			$shipping=$row["handling_shipment"];
			$options.="<OPTION VALUE=\"{$id}\" {$selected}>".$id." - ".$this->con($payment)." - ".$this->con($shipping)."</OPTION>\n"; 
		}
		return $options;
	}

  function _fill ($data){
    foreach($data as $k=>$v){
      $this->$k=$v;
    }
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

	function _extra_handle ($order,$new_state,$old_state,$field) {
		$pm_class='pm_'.$this->handling_payment;
		$sm_class='sm_'.$this->handling_shipment;
		$ok=TRUE;
		
		if($this->_dyn_load('classes/'.$pm_class.'.php')){
			$pm=new $pm_class;
			if(method_exists($pm,'handle')){
				$ok=$pm->handle($order,$new_state,$old_state,$field);
			}
		}
		
		if($this->_dyn_load('classes/'.$sm_class.'.php')){
			$sm=new $sm_class;
			if(method_exists($sm,'handle')){
				$ok=($ok and $sm->handle($order,$new_state,$old_state,$field));
			}
		}
		return $ok;
	}

	function _extra_on_order_delete ($order_id) {
		$pm_class='pm_'.$this->handling_payment;
		$sm_class='sm_'.$this->handling_shipment;
		$ok=TRUE;
		
		if($this->_dyn_load('classes/'.$pm_class.'.php')){
			$pm=new $pm_class;
			if(method_exists($pm,'on_order_delete')){
				$ok=$pm->on_order_delete($order_id);
			}
		}
		if($this->_dyn_load('classes/'.$sm_class.'.php')){
			$sm=new $sm_class;
			if(method_exists($sm,'on_order_delete')){
				$ok=($ok and $sm->on_order_delete($order_id));
			}
		}
		return $ok;
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