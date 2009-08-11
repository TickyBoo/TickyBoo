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

class EmailTCompiler {

  var $res=array(); //result of execution, indexed by language

  var $mode=0; //0 normal 1 text
  var $stop_tag; //that stops the mode nr 1

  var $stack=array(); // local stack for various purposes
  var $vars=array(); //variables are collected for informative purposes
  var $args='data'; //name of the parameter array where variables are stored

  var $deflang=0;
  var $errors=array();

  function EmailTCompiler (){
  }

  function emit ($key,$val,$lang=0){
    if(!$lang){
      $lang=0; //insure that this is '0' and not other 'null' values
    }

    $this->res[$lang][$key]=$val;
  }

  function add ($key,$val,$lang=0){
    if(!$lang){
      $lang=0; //insure that this is '0' and not other 'null' values
    }

    $this->res[$lang][$key][]=$val;
  }

  function a2p ($val){
    return $this->replace_vars($val);
  }



  function a2ps ($val){
    return "\"".$this->replace_vars(str_replace('"','\"',$val),1)."\"";
  }

  function error ($message){
    $this->errors[]=$message." line ".xml_get_current_line_number($this->xml_parser);
  }


  function replace_vars ($val,$quot=0){
    if($quot){
      return preg_replace_callback('/\$(\w+)/',array(&$this,'_rep_q_cbk'),$val);
    }else{
      return preg_replace_callback('/\$(\w+)/',array(&$this,'_rep_cbk'),$val);
    }
  }

  function _rep_cbk ($matches){
    array_push($this->vars,$matches[1]);
    return '$'.$this->args.'["'.$matches[1].'"]';
  }

  function _rep_q_cbk ($matches){
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
      $this->emit('from',$this->a2ps($a['EMAIL']),$a['LANG']);
      break;

    case "to" :
      $this->emit('to',$this->a2ps($a['EMAIL']),$a['LANG']);
      break;

    case "cc" :
      $this->add('cc',$this->a2ps($a['EMAIL']),$a['LANG']);
      break;

		case "bcc" :
      $this->add('bcc',$this->a2ps($a['EMAIL']),$a['LANG']);
      break;

		case "header" :
      $this->add('header',array(
					'name'=>$this->a2ps($a['NAME']),
					'value'=>$this->a2ps($a['VALUE']),
				),$a['LANG']);
      break;

		case "return" :
      $this->emit('return',$this->a2ps($a['EMAIL']),$a['LANG']);
      break;

		case "text_charset" :
      $this->emit('text_charset',$this->a2ps($a['VALUE']),$a['LANG']);
      break;

		case "html_charset" :
      $this->emit('html_charset',$this->a2ps($a['VALUE']),$a['LANG']);
      break;

		case "head_charset" :
      $this->emit('head_charset',$this->a2ps($a['VALUE']),$a['LANG']);
      break;

		case "subject" :
      $this->emit('subject',$this->a2ps($a['VALUE']),$a['LANG']);
      break;

    case "attachment":
      $this->add('attachment',array(
        'file'=>$this->a2ps($a['FILE']),
        'name'=>$this->a2ps($a['NAME']),
        'type'=>$this->a2ps($a['TYPE']),
        'data'=>$this->a2ps($a['DATA'])
      ),$a['LANG']);
      break;

    case "order_pdf" :
      $this->add('order_pdf',array(
        'name'=>$this->a2ps($a['NAME']),
        'order_id'=>$this->a2ps($a['ORDER_ID']),
        'mark_send'=>$this->a2p($a['MARK_SEND']),
				'summary'=>$this->a2p($a['SUMMARY']),
				'mode'=>$this->a2p($a['MODE'])
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

      $this->emit('text',$this->a2ps($this->text),$lang);

      $this->text='';
      break;

    case "html":
      $lang=array_pop($this->stack);
      $this->mode=array_pop($this->stack);

      $this->emit('html',$this->a2ps($this->text),$lang);

      $this->text='';
      break;

    case "template":

      $code='  function build(&$mail,&$data,$lang="'.$this->deflang.'"){'."\n";

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
      $res.=$pre.'$mail->setFrom('. $data['from'] .')'.$post;
    } else if ($lang===0){
      $res.=$pre.'$mail->setFrom("'. $_SHOP->organizer_data->organizer_name.' <'.$_SHOP->organizer_data->organizer_email.'>" )'.$post;
    }

    if(isset($data['cc'])){
			$cc=implode('.",".',$data['cc']);
     	$res.=$pre.'$mail->setCc('. $cc .')'.$post;
    }

    if(isset($data['bcc'])){
			$bcc=implode('.",".',$data['bcc']);
     	$res.=$pre.'$mail->setBcc('. $bcc .')'.$post;
    }

    if(isset($data['to'])){
      $res.=$pre.'$this->to[]='.$data['to'].$post;
    }

    if(isset($data['subject'])){
      $res.=$pre.'$mail->setSubject('. $data['subject'] .')'.$post;
    }

    if(isset($data['return'])){
      $res.=$pre.'$mail->setReturnPath('. $data['return'] .')'.$post;
    }

    if(isset($data['text_charset'])){
      $res.=$pre.'$mail->setTextCharset('. $data['text_charset'] .')'.$post;
    }else{
      $res.=$pre.'$mail->setTextCharset("UTF-8")'.$post;
		}

    if(isset($data['html_charset'])){
      $res.=$pre.'$mail->setHtmlCharset('. $data['html_charset'] .')'.$post;
    }else{
      $res.=$pre.'$mail->setHtmlCharset("UTF-8")'.$post;
		}

    if(isset($data['head_charset'])){
      $res.=$pre.'$mail->setHeadCharset('. $data['head_charset'] .')'.$post;
    }else{
      $res.=$pre.'$mail->setHeadCharset("UTF-8")'.$post;
		}

    if(isset($data['header'])){
			foreach($data['header'] as $header){
				$res.=$pre.'$mail->setHeader('. $header['name'] .','.$header['value'].')'.$post;
			}
		}

    if(isset($data['html'])){
      $res.=$pre.'$mail->setHtml('.$data['html'].")".$post;
    }
    if(isset($data['text'])){
      $res.=$pre.'$mail->setText('.$data['text'].")".$post;
    }

    if(isset($data['order_pdf'])){
      foreach($data['order_pdf'] as $order_pdf){
        $r_data=$order_pdf['order_id'];

				if(strcasecmp($order_pdf['mode'],'tickets')=='0'){
					$mode=1;
				}elseif(strcasecmp($order_pdf['mode'],'summary')==0){
					$mode=2;
				}else{
					$mode=3;
				}


       $res.=$pre.'$mail->addAttachment( new Attachment( Order::print_order('.$r_data.",'".
             $order_pdf['summary']."','data',FALSE,$mode), ".$order_pdf['name'].", 'application/pdf', new Base64Encoding()))".$post;

        if(strcasecmp($order_pdf['mark_send'],'yes')==0){
          $res.=$pre.'$order=Order::load('.$r_data.')'.$post;
          $res.=$pre.'$order->set_shipment_status(\'send\')'.$post;
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



	  $res.=$pre.'  $mail->addAttachment(new Attachment( '.$r_data.", ".$attach['name'].", ".$attach['type'].",new Base64Encoding()))$post";
	  $res.=$pre."}\n";
	}

        if(isset($data1) and isset($file)){
	  $res.=$pre."else{\n";
	}

	if(isset($file)){
	  $res.=$pre.'$mail->addAttachment(new FileAttachment('.$attach['file'].', '.$attach['type']."))$post";
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

  function PIHandler($parser, $target, $data) {
  /*  switch (strtolower($target)) {
        case "php":
            global $parser_file;
            // If the parsed document is "trusted", we say it is safe
            // to execute PHP code inside it.  If not, display the code
            // instead.
            if (trustedFile($parser_file[$parser])) {
                eval($data);
            } else {
                printf("Untrusted PHP code: <i>%s</i>",
                        htmlspecialchars($data));
            }
            break;
    }
  */
  }

  function defaultHandler($parser, $data) {
  /*
    if (substr($data, 0, 1) == "&" && substr($data, -1, 1) == ";") {
        printf('<font color="#aa00aa">%s</font>',
                htmlspecialchars($data));
    } else {
        printf('<font size="-1">%s</font>',
                htmlspecialchars($data));
    }
  */
  }

  function externalEntityRefHandler($parser, $openEntityNames, $base, $systemId,
                                  $publicId) {
  /*
    if ($systemId) {
        if (!list($parser, $fp) = new_xml_parser($systemId)) {
            printf("Could not open entity %s at %s\n", $openEntityNames,
                   $systemId);
            return false;
        }
        while ($data = fread($fp, 4096)) {
            if (!xml_parse($parser, $data, feof($fp))) {
                printf("XML error: %s at line %d while parsing entity %s\n",
                       xml_error_string(xml_get_error_code($parser)),
                       xml_get_current_line_number($parser), $openEntityNames);
                xml_parser_free($parser);
                return false;
            }
        }
        xml_parser_free($parser);
        return true;
    }
    return false;
  */
  }

  function new_xml_parser () {

    $xml_parser = xml_parser_create();
    xml_set_object($xml_parser, $this);
    xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 1);
    xml_set_element_handler($xml_parser, "startElement", "endElement");
    xml_set_character_data_handler($xml_parser, "characterData");
    //xml_set_processing_instruction_handler($xml_parser, "PIHandler");
    //xml_set_default_handler($xml_parser, "defaultHandler");
    //xml_set_external_entity_ref_handler($xml_parser, "externalEntityRefHandler");

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

  function compile ($input, $out_class_name){
    $this->res=array();

    $this->mode=0;
    $this->stop_tag;

    $this->stack=array();
    $this->vars=array();
    $this->args='data';

    $this->deflang=0;
    $this->errors=array();

    $this->xml_parser=$this->new_xml_parser();

    if (!xml_parse($this->xml_parser, $input, TRUE)) {
      $this->error(xml_error_string(xml_get_error_code($this->xml_parser)));
    }

    xml_parser_free($this->xml_parser);

    if (!$this->errors) {
    $xyz =
'/*this is a generated code. do not edit!
produced '.date("C").'
*/

class '.$out_class_name.' {
  var $object_id;
  var $engine;
  var $langs = array('.implode(",",$this->langs).');
  
  function '.$out_class_name.'(){}

  '.$this->build.'
}
';
//    echo nl2br($xyz);
    return $xyz;
  }else{
    return FALSE;
  }
}
}
?>