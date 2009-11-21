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

class Handling Extends model {

  protected $_idName    = 'handling_id';
  protected $_tableName = 'Handling';
  protected $_columns   = array( '#event_id',
      '*event_name', 'event_text', 'event_short_text', 'event_url',
      'event_image', '*event_ort_id', '#event_pm_id', 'event_date', 'event_time',
      'event_open', 'event_end', '*event_status', '*event_order_limit', 'event_template',
      '#event_group_id', 'event_mp3', '*event_rep', '#event_main_id', 'event_type');

  var $templates;
  protected $_pment = null;

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
		} else {
			$this->extra= array();
		}
  	if ( $pm = $this->pment()) {
			foreach($this->extra as $key => $val){
				if(in_array($key, $pm->extras)){
					$this->$key = $val;
				}
			}
  	}

    $keys  = explode(",", $this->handling_sale_mode);
    if (count($keys)>0) {
      $this->sale_mode = array_combine($keys,array_fill(0,count($keys),true));
    } else {
      $this->sale_mode = array();
    }
	}

	function _fill ($data, $nocheck=false){
	  parent::_fill($data,$nocheck);
  	if ( $pm = $this->pment()) {
    	foreach($pm->extras as $key)
    		$this->extra[$key] = is($data[$key], null);
  	}
		$this->sale_mode = $data['sale_mode'];
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
    	return null;
  	}

  function load_all ($handling_sale_mode=''){
    global $_SHOP;
    if($handling_sale_mode){
      $sale="where handling_sale_mode=".ShopDB::quote($handling_sale_mode);
    }

    $query="select * from Handling $sale";
    if($res=ShopDB::query($query)){
      while($data=shopDB::fetch_assoc($res)){
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

    return $this->extra_check($arr) and $ok;
  }

  function delete (){
    global $_SHOP;
    if (!$id) $id = $this->handling_id;
		$query="SELECT count(order_id) AS count FROM `Order` WHERE order_handling_id=".ShopDB::quote($id);
		if($res=ShopDB::query_one_row($query, false) and $res['count']==0){
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
    include_once(INC.'classes'.DS.'TemplateEngine.php');
    include_once(INC.'classes'.DS.'htmlMimeMail.php');

    $ok=TRUE;

    if($template_name=$this->templates[$new_state] and $order->user_email){
      $te=new TemplateEngine;
      $tpl=&$te->getTemplate($template_name);

      $email = new htmlMimeMail();
      $order_d=(array)$order;   //print_r( $order_d);
      $link= $_SHOP->root."index.php?personal_page=orders&id=";
      $order_d['order_link']=$link;
      $tpl->build($email,$order_d,$_SHOP->lang);

      if(!$email->send($tpl->to)){
        user_error('error: '.print_r($email->errors,true));
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

	/**
	 * @return true if the handling uses an extended payment handler (ie paypal, ideal)
	 * @access public
	 */
	public function is_eph() {
    	return ($this->pment())?true:false;
  	}

  // Loads default extras for payment method eg."pm_paypal_View.php"
  function admin_init(){
  	if($pm=$this->pment()){
      $pm->admin_init();
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
      $sm->admin_init();
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

	function admin_check(&$data, &$errors){
		if($pm = $this->pment()){
			return $pm->admin_check($data, $errors);
		}else{
			return true;
		}
	}

	/**
	 * Handling::isValidCallback()
	 *
	 * Will ask the eph to verify its details set in the encodeCallback method.
	 *
	 * @param string $code
	 * @return boolean : true
	 * @since 1.0b5
	 */
	public function isValidCallback($code){
		if($pm=$this->pment()){
			return $pm->decodeCallback($code);
  		}
	}

	/**
	 * Handling::decodeEPHCallback()
	 *
	 * It will break down the callback hash, find which eph then check against its validation method
	 * to check that the handling id matches the settings within the eph.
	 * The handling object filled will then be returned on successfull decode and validation.
	 *
	 * @return handling Object or null.
	 * @uses Handling
	 * @since 1.0b5
	 */
	public function decodeEPHCallback($callbackCode){

		if (empty($callbackCode) and isset($_REQUEST['cbr'])) $callbackCode =$_REQUEST['cbr'];

		if(!empty($callbackCode)){

			$hand = null; //handling var

  			$text = base64_decode($callbackCode);
      		$code = explode(':',$text);
    		//  print_r( $text );
      		$code[1] = base_convert($code[1],36,10);

      		if(is_numeric($code[1])){
	  			$hand = Handling::load($code[1]);
	  		}
	  		if($hand == null){
	  			return null;
			}
	  		if($hand->is_eph()){
				if($hand->handling_payment != $code[0]){
					return null;
				}
				if($hand->isValidCallback($code[2])){
					return $hand;
				}
	  		}
	  		return null;
		}
	}

  	/**
  	 * @name OnConfirm
  	 *
  	 * The function is used to get the payment form/method from
  	 * the extended payment handler.
  	 *
  	 * @param order : the order object [Required]
  	 * @return Array or html
  	 * @access public
  	 * @author Niels
  	 * @uses Order Object, EPH Object
  	 */
	public function on_confirm($order) {
    	$return ='';
  		if($pm=$this->pment()){
      		$return = $pm->on_confirm($order);
  		} else {
      return array('approved'=>true,
                   'transaction_id'=>false,
                   'response'=> $this->handling_html_template);
    	}
    	return (is_array($return))?$return:$this->handling_html_template.'<br>'.$return;
  	}

  function on_submit(&$order, &$errors) {
  	if($pm=$this->pment()){
      return $pm->on_submit($order, $errors);
  	}
  }

  function on_return(&$order, $accepted) {
  	if($pm=$this->pment()){
      return $pm->on_return($order, $accepted);
  	} else {
      return array('approved'=>$accepted,
                   'transaction_id'=>false,
                   'response'=> '');
  	}
  }

  function on_notify(&$order) {
  	if($pm=$this->pment()){
      return $pm->on_notify($order);
  	}
  }

  function on_check(&$order) {
  	if($pm=$this->pment()){
      return $pm->on_check($order);
  	}
  }

	/**
	 * @name PaymentMethod
	 *
	 * Will load the eph file and create the eph object
	 *
	 * @example : eph_paypal.php would be loaded and the eph object would be created like:
	 *  EPH_paypal then added the to this handling object on _pment varible.
	 *
	 * @return EPH Object
	 * @since 1.0
	 * @author Niels
	 * @uses EPH Object
	 * @access private
	 */
	private function pment() {
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
    $types=array('cash','entrance','invoice');
    $dir = INC.'classes'.DS.'payments';
	  if ($handle = opendir($dir)) {
		  while (false !== ($file = readdir($handle))){
        if ($file != "." && $file != ".." && !is_dir($dir.$file) && preg_match("/^eph_(.*?\w+).php/", $file, $matches)) {
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
    $types=explode("','",preg_replace("/(enum|set)\('(.+?)'\)/","\\2",$res['Type']));
    return $types;
  }

  function get_handlings ($include =''){
		$sqli="SELECT handling_id, handling_payment, handling_shipment FROM `Handling` WHERE handling_id!='1'";
		if(!$result=ShopDB::query($sqli)){echo("Error"); return;}
		$options= array();
    if ($include)
			$options["1"] = $include;

		while ($row=shopDB::fetch_assoc($result)) {
			$id=$row["handling_id"];
			$payment= $row["handling_payment"];
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