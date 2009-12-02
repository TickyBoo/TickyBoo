<?PHP
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
require_once "classes/xml2php.php";

class EmailSwiftXMLCompiler {

  var $res=array(); //result of execution, indexed by language

  var $mode=0; //0 normal 1 text
  var $stop_tag; //that stops the mode nr 1

  var $stack=array(); // local stack for various purposes
  var $vars=array(); //variables are collected for informative purposes
  var $args='data'; //name of the parameter array where variables are stored
  protected $langs = array();

  var $deflang=0;
  var $errors=array();

  function EmailSwiftXMLCompiler (){
  }
  
  
  private function addParam ($key,$val,$lang=0){
    if(!$lang){
      $lang=0; //insure that this is '0' and not other 'null' values
    }

    $this->res[$lang][$key]=$val;
  }

  private function addToParam ($key,$val,$lang=0){
    if(!$lang){
      $lang=0; //insure that this is '0' and not other 'null' values
    }

    $this->res[$lang][$key][]=$val;
  }

  private function attribToParam ($val){
    return $this->replace_vars($val);
  }

  private function attribToParamString ($val){
    return "\"".$this->replace_vars(str_replace('"','\"',$val),1)."\""; 
  }
  
  /**
   * EmailSwiftXMLCompiler::emailToParam()
   * Will try to turn email xml into an array format.
   * @return $email or [$email] => "$names"
   */
  private function emailToParam($val){
    preg_match_all("/(.*?)(<)([^>]+)(>)/",$val,$matches);
    if(is($matches[3][0])){
      $email = $this->replace_vars(str_replace('"','\"',$matches[3][0]),0);
      if(is($matches[1][0])){
        $names = $this->replace_vars(str_replace('"','\"',$matches[1][0]),1);
        $ret = $email." => \"".$names."\"";
        return $ret;
      }else{
        $ret = "\"".$email."\"";
      }
    }
    $ret = "\"".$this->replace_vars(str_replace('"','\"',$val),1)."\"";
    return $ret;
  }

  function error ($message){
    $this->errors[]=$message." line ".xml_get_current_line_number($this->xml_parser);
  }


  private function replace_vars ($val,$quot=0){
    if($quot){
      $return = preg_replace_callback('/\$(\w+)/',array(&$this,'replaceCallbackQuote'),$val);
      return $return;
    }else{
      return preg_replace_callback('/\$(\w+)/',array(&$this,'replaceCallback'),$val);
    }
  }

  private function replaceCallback ($matches){
    array_push($this->vars,$matches[1]);
    return '$'.$this->args.'["'.$matches[1].'"]';
  }

  private function replaceCallbackQuote ($matches){
    array_push($this->vars,$matches[1]);
    return '".$'.$this->args.'["'.$matches[1].'"]."';
  }


  function startElement ($parser, $name, $a) {
    if($this->mode==1){
      $this->text.="<".strtolower($name)." ";
      foreach($a as $name=>$value){
        $this->text.="$name=\"$value\" ";
      }
      $this->text.=">";
      return;
    }
    //echo $name."<br>";
    switch(strtolower($name)){

      case "template" :
        if(isset($a["DEFLANG"])){
          $this->deflang=$a["DEFLANG"];
        }
        break;

      case "text" :
        array_push($this->stack,$this->mode);
        array_push($this->stack,$a["LANG"]);
        $this->end_tag="TEXT";
        $this->mode=1;
        break;
  
      case "html":
        array_push($this->stack,$this->mode);
        array_push($this->stack,$a["LANG"]);
        $this->end_tag="HTML";
        $this->mode=1;
        break;
  
      case "from" :
        $this->addParam('from',$this->emailToParam($a['EMAIL']),$a['LANG']);
        break;
  
      case "to" :
        //$this->addParam('to',$this->attribToParamString($a['EMAIL']),$a['LANG']);
        $this->addParam('to',$this->emailToParam($a['EMAIL']),$a['LANG']);
        break;
        
      case "cc" :
        $this->addToParam('cc',$this->emailToParam($a['EMAIL']),$a['LANG']);
        break;
  
  		case "bcc" :
        $this->addToParam('bcc',$this->emailToParam($a['EMAIL']),$a['LANG']);
        break;
  
  		case "header" :
        $this->addToParam('header',array(
  					'name'=>$this->attribToParamString($a['NAME']),
  					'value'=>$this->attribToParamString($a['VALUE']),
  				),$a['LANG']);
        break;
  
  		case "return" :
        $this->addParam('return',$this->attribToParamString($a['EMAIL']),$a['LANG']);
        break;
  
  		case "text_charset" :
        $this->addParam('text_charset',$this->attribToParamString($a['VALUE']),$a['LANG']);
        break;
  
  		case "html_charset" :
        $this->addParam('html_charset',$this->attribToParamString($a['VALUE']),$a['LANG']);
        break;
  
  		case "head_charset" :
        $this->addParam('head_charset',$this->attribToParamString($a['VALUE']),$a['LANG']);
        break;
  
  		case "subject" :
        $this->addParam('subject',$this->attribToParamString($a['VALUE']),$a['LANG']);
        break;
  
      case "attachment":
        $this->addToParam('attachment',array(
          'file'=>$this->attribToParamString($a['FILE']),
          'name'=>$this->attribToParamString($a['NAME']),
          'type'=>$this->attribToParamString($a['TYPE']),
          'data'=>$this->attribToParamString($a['DATA'])
        ),$a['LANG']);
        break;
  
      case "order_pdf" :
        $this->addToParam('order_pdf',array(
          'name'=>$this->attribToParamString($a['NAME']),
          'order_id'=>$this->attribToParam($a['ORDER_ID']),
          'mark_send'=>$this->attribToParam($a['MARK_SEND']),
  				'summary'=>$this->attribToParam($a['SUMMARY']),
  				'mode'=>$this->attribToParam($a['MODE'])
        ),$a['LANG']);
        break;
    }

  }

  function endElement ($parser, $name) {
  if($this->mode==1  and $name!=$this->end_tag){
    $this->text.="</".strtolower($name).">";
    return;
  }

  switch(strtolower($name)){

    case "text":
      $lang=array_pop($this->stack);
      $this->mode=array_pop($this->stack);

      $this->addParam('text',$this->attribToParamString($this->text),$lang);

      $this->text='';
      break;

    case "html":
      $lang=array_pop($this->stack);
      $this->mode=array_pop($this->stack);

      $this->addParam('html',$this->attribToParamString($this->text),$lang);

      $this->text='';
      break;

    case "template":

      $code='  function build(&$message,&$data,$lang="'.$this->deflang.'"){'."\n";

      foreach($this->res as $lang=>$data){
        if($lang){
          $this->langs[] = "'{$lang}'";
      	  $code.='    '.$els.'if($lang=="'.$lang."\"){\n";
      	  $code.=$this->_gen_lang($lang,$data);
      	  $code.="    }\n";
      	  $els="else ";
      	}
      }
      $code.=$this->_gen_lang(0,$this->res[0]);
      $code.="  }\n";

      $this->build=$code;
      break;
  }
  }

  function _gen_lang ($lang,$data){
    global $_SHOP;
    $pre='      ';
    $post=";\n";
    if(isset($data['from'])){
   		$res.=$pre.'$message->setFrom(array('.$data['from'].'))'.$post;
    }else if ($lang===0){
    	$res.=$pre.'$message->setFrom(array("'.$_SHOP->organizer_data->organizer_email.'"=>"'.$_SHOP->organizer_data->organizer_name.'" ))'.$post;
    }

    if(isset($data['cc'])){
			$cc=implode(',',$data['cc']);
     	$res.=$pre.'$message->setCc(array('. $cc .'))'.$post;
    }

    if(isset($data['bcc'])){
			$bcc=implode(',',$data['bcc']);
     	$res.=$pre.'$message->setBcc(array('. $bcc .'))'.$post;
    }

    if(isset($data['to'])){
      $res.=$pre.'$message->setTo(array('.$data['to'].'))'.$post;
    }

    if(isset($data['subject'])){
      $res.=$pre.'$message->setSubject('. $data['subject'] .')'.$post;
    }

    if(isset($data['return'])){
      $res.=$pre.'$message->setReturnPath('. $data['return'] .')'.$post;
    }

    //defaults to UTF
    if(isset($data['head_charset'])){
      $res.=$pre.'$message->->setCharset('. $data['head_charset'] .')'.$post;
    }

    if(isset($data['html'])){
      $res.=$pre.'$message->setBody('.$data['html'].",'text/html',".is($data['html_charset'],"null").")".$post;
    }
    if(isset($data['text'])){
      $res.=$pre.'$message->addPart('.$data['text'].",'text/plain',".is($data['text_charset'],"null").")".$post;
    }

    if(isset($data['order_pdf'])){
      foreach($data['order_pdf'] as $order_pdf){
        $order_id =$order_pdf['order_id'];

				if(strcasecmp($order_pdf['mode'],'tickets')=='0'){
					$mode=1;
				}elseif(strcasecmp($order_pdf['mode'],'summary')==0){
					$mode=2;
				}else{
					$mode=3;
				}
        
        $res .= $pre.'$message->attach(Swift_Attachment::newInstance(Order::print_order('.$order_id.",'".$order_pdf['summary']."', 'data', FALSE, $mode), ".$order_pdf['name'].", 'application/pdf'))".$post;

        if(strcasecmp($order_pdf['mark_send'],'yes')==0){
          $res.=$pre.'$order=Order::load('.$order_id.')'.$post;
          $res.=$pre."if (\$order) {\n";
          $res.=$pre.'  $order->set_shipment_status(\'send\')'.$post;
          $res.=$pre."}\n";
	      }
      }
    }

    if(isset($data['attachment'])){
      foreach($data['attachment'] as $attach){
        $file=$attach['file'];
        $data1=$attach['data'];

        $r_data='$'.$this->args.'['.$data1.']';

        if(isset($data1)){
          $res.=$pre.'if(isset('.$r_data.")){\n";
          $res.=$pre.'  $message->attach(Swift_Attachment::newInstance( '.$r_data.", ".$attach['name'].", ".$attach['type']."))".$post;
          $res.=$pre."}\n";
        }

        if(isset($data1) and isset($file)){
          $res.=$pre."else{\n";
        }

        if(isset($file)){
          $res.=$pre.'$message->attach(Swift_Attachment::fromPath('.$attach['file'].', '.$attach['type']."))".$post;
        }

        if(isset($data1) and isset($file)){
          $res.=$pre."}\n";
        }
      }
    }
    return $res;
  }

  function characterData ($parser, $data) {
    if($this->mode==1){
      $this->text.=$data;
    }
  }

  function new_xml_parser () {

    $xml_parser = xml_parser_create();
    xml_set_object($xml_parser, $this);
    xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 1);
    xml_set_element_handler($xml_parser, "startElement", "endElement");
    xml_set_character_data_handler($xml_parser, "characterData");

    return $xml_parser;
  }

  function make_uses ($vars){
    $res="array(";
    $row= 0;
    foreach($vars as $var){
      if(is_array($var)){
        $res.=$sep.$this->make_uses($var);
      }else{
        $res.="$sep'$var'";
      }
      $sep=",";
      If ($row==7) {
        $row=0;
        $res."/n";
      } else {
        $row++;
      }

    }
    return "$res)";
  }

  function compile ($xml, $className){
    $this->res=array();

    $this->mode=0;
    $this->stop_tag;

    $this->stack=array();
    $this->vars=array();
    $this->args='data';

    $this->deflang=0;
    $this->errors=array();

    $this->xml_parser=$this->new_xml_parser();
    if (!xml_parse($this->xml_parser, $xml, TRUE)) {
      $this->error(xml_error_string(xml_get_error_code($this->xml_parser)));
    }
    xml_parser_free($this->xml_parser);

    if (!$this->errors) {    	
    	$langs = implode(",",$this->langs);
    	$langs = str_replace('\'', '"', $langs);
      $xyz =
'/*this is a generated code. do not edit! produced '.date("C").' */

require_once (LIBS."swift".DS."swift_required.php");

class '.$className.' {
  public $object_id;
  public $engine;
  public $langs = array('.$langs.');
  protected $deflang = "'.$this->deflang.'";
  
  function '.$className.'(){}
  
  function write(&$message,&$data,$lang="'.$this->deflang.'",$testEmail=""){
    if(!is_object($message)){
      $message = Swift_Message::newInstance();
    }
    if(!in_array($lang,$this->langs)){
      $lang = $this->deflang;
    }
    $this->build($message,$data,$lang);
  }
  
  '.$this->build.'
}';
//    echo ($xyz);
    return $xyz;
  }else{
    return FALSE;
  }
}
}
?>