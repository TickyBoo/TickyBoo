<?PHP
/*
%%%copyright%%%
 * phpMyTicket - ticket reservation system
 * Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of phpMyTicket.
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
 * The "phpmyticket professional licence" version 1 is available at
 * http://www.phpmyticket.com/ and in the file
 * PROFESSIONAL_LICENCE included in the packaging of this file.
 * For pricing of this licence please contact us via e-mail to 
 * info@phpmyticket.com.
 * Further contact information is available at http://www.phpmyticket.com/
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@phpmyticket.com if any conditions of this licencing isn't 
 * clear to you.
 
 */


class PDFTCompiler {
  var $res_stat ="";
  var $res_dyna ="";
  var $mode=0; //1 static 2 dynamic 0 none 3 ezText 
  var $stack=array(); // local stack
  var $vars=array();
  var $args='data';
  var $errors;
  
  function PDFTCompiler ($font_dir=''){
    global $_SHOP;
    if($font_dir){
      $this->font_dir=$font_dir;
    }else{
      $this->font_dir=$_SHOP->font_dir;
    }
    //$this->template_dir=$_SHOP->template_dir;
    
    $this->template_dir=$_SHOP->tpl_dir;
  }

  function emit ($code){
    if($this->mode==1){   
      $this->res_stat.=$code."\n";
    }else if($this->mode==2){
      $this->res_dyna.=$code."\n";
    }else{
      $this->error("mode not selected");
    }
  }

  function a2p ($val,$def,$next=null){
    if(isset($val)){
     //if($this->mode==2){
      $val=$this->replace_vars($val);
    //}
      if(isset($next)){
        return "$val, $next";
      }else{
        return $val;
      }
    }else{
      if(isset($next)){
        return "$def, $next";
      }
    }
  }

  function a2r ($val,$next=null){
    if(isset($val)){
    //if($this->mode==2){
      $val=$this->replace_vars($val);
    //}
      if(isset($next)){
        return "$val, $next";
      }else{
        return $val;
      }
    }else{
      $this->error("mandatory attribute missing");
    }
  }

  function a2ps ($val,$def,$next=null){
    if(isset($val)){
    //if($this->mode==2){
      $val=$this->replace_vars($val,1);
    //}

      if(isset($next)){
        return "\"$val\", $next";
      }else{
        return "\"$val\"";
      }
    }else{
      if(isset($next)){
        return "\"$def\", $next";
      }
    }
  }

  function a2rs ($val,$next=null){
    if(isset($val)){
    //if($this->mode==2){
      $val=$this->replace_vars($val,1);
    //}
  
      if(isset($next)){
        return "\"$val\", $next";
      }else{
        return "\"$val\"";
      }
    }else{
      $this->error("mandatory attribute missing");
    }
  }

  function a2pi ($val,$def,$next=null){
    if(isset($val)){
      if(isset($this->invert)){ $val=$this->invert-$val;}
      if(isset($next)){
        return "$val, $next";
      }else{
        return $val;
      }
    }else{
      if(isset($next)){
        return "$def, $next";
      }
    }
  }

  function a2ri ($val,$next=null){
    if(isset($val)){
      if(isset($this->invert)){ $val=$this->invert-$val;}
      if(isset($next)){
        return "$val, $next";
      }else{
        return $val;
      }
    }else{
      $this->error("mandatory attribute missing");
    }
  }

//return "array('name'=>'value')"
  function a2opt ($a,$opt_names){
  
    foreach($opt_names as $opt_name){
      $U=strtoupper($opt_name);
      if(isset($a[$U])){
        $res.="$vir'$opt_name'=>".$this->a2rs($a[$U]);
        $vir=", ";
      }  
    }
  
    if(isset($res)){
      return "array($res)";
    }else{
      return NULL;
    }
  }

  function error ($message){
    $this->errors[]=$message." line ".xml_get_current_line_number($this->xml_parser);
  }


  function replace_vars ($val,$quot=0){
    if($quot){
      return preg_replace_callback('/\$(\w+)/',array(&$this,'_rep_q_cbk'),str_replace('"','\"',$val));
//     return preg_replace_callback('/\$(\w+)/','".$data["$1"]."',$val);
    }else{
      return preg_replace_callback('/\$(\w+)/',array(&$this,'_rep_cbk'),$val);
//     return preg_replace_callback('/\$(\w+)/','$data["$1"]',$val);
    }
  }

  function _rep_cbk ($matches){
    array_push($this->vars,$matches[1]);
		if($this->reconv){
			return 'iconv("UTF-8","'.$this->reconv.'",$'.$this->args.'["'.$matches[1].'"])';
		}else{
			return '$'.$this->args.'["'.$matches[1].'"]';
		}
  }

  function _rep_q_cbk ($matches){
    array_push($this->vars,$matches[1]);
		if($this->reconv){
			return '".iconv("UTF-8","'.$this->reconv.'",$'.$this->args.'["'.$matches[1].'"])."';
		}else{
			return '".$'.$this->args.'["'.$matches[1].'"]."';
		}
	}

  function trustedFile($file) {
    // only trust local files owned by ourselves
    if (!eregi("^([a-z]+)://", $file) 
        && fileowner($file) == getmyuid()) {
            return true;
    }
    return false;
  }

  function startElement ($parser, $name, $a) {
  if($this->mode==3){
    $this->ezTextText.="<".strtolower($name).">";
    return;
  }
  //echo "$name<br>";
  switch(strtolower($name)){
    
    case "template" :
      if(isset($a["INVERT"])){
        $this->invert=$a["INVERT"];
      }else{
        $this->invert=0;
      }
    
      if(isset($a["LANG"])){
        $this->lang=$a["LANG"];
      }
    break;
    case "static":
      $this->mode=1;
    break;
    
    case "dynamic":
      $this->mode=2;
    break;
    
    case "text" :
      $sty=$a['STYLE'];
      if($sty=='bold'){
        $a['VALUE']="<b>".$a['VALUE']."</b>";
      }
  
      $this->emit(
      '$pdf->addText('.$this->a2r($a['X'],
                        $this->a2ri($a['Y'],
                         $this->a2r($a['SIZE'],
                          $this->a2rs($a['VALUE'],
                           $this->a2p($a['ANGLE'],0,
                            $this->a2p($a['ADJUST'],0 )))))).');'
      );
      break;   
    case "line_style":
      if(isset($a['DASH'])){
        $dash='array('.$a['DASH'].')';
      }else{
        $dash="''";
      }
      $this->emit('$pdf->setLineStyle('.$this->a2p($a['WIDTH'],1,
                                        $this->a2ps($a['CAP'],'',
					 $this->a2ps($a['JOIN'],'',
					  $dash))).');');
      break;                         
    case "line" :
      $this->emit(
      '$pdf->line('.$this->a2r($a['X1'],
                        $this->a2ri($a['Y1'],
                         $this->a2r($a['X2'],
                          $this->a2ri($a['Y2'] )))).');'
      );
      break;   

      case "curve" :
      $this->emit(
      '$pdf->curve('.$this->a2r($a['X1'],
                        $this->a2ri($a['Y1'],
                         $this->a2r($a['X2'],
                          $this->a2ri($a['Y2'], 
			   $this->a2r($a['X3'],
			     $this->a2ri($a['Y3']  )))))).');'
      );
      break;   

    case "ellipse" :
      $this->emit(
      '$pdf->ellipse('.$this->a2r($a['X0'],
                        $this->a2ri($a['Y0'],
 			  $this->a2r($a['R1'],
			    $this->a2p($a['R2'],0,
			      $this->a2p($a['ANGLE'],0,
			        $this->a2p($a['NSEG'],8 )))))).');'
      );
      break;                            

    case "partellipse" :
      $this->emit(
      '$pdf->partEllipse('.$this->a2r($a['X'],
                        $this->a2ri($a['Y'],
                         $this->a2r($a['A1'],
                          $this->a2r($a['A2'],
			    $this->a2r($a['R1'],
			      $this->a2p($a['R2'],0,
			       $this->a2p($a['ANGLE'],0,
			         $this->a2p($a['NSEG'],8 )))))))).');'
      );
      break;                            

    case "font" :
    
      $this->emit(
      '$pdf->selectFont("'.$this->font_dir."/".$this->a2r($a['NAME']).'.afm");'
      );
      break;                            


    case "image" :
      
      $ext=strtolower(strrchr($a['SRC'],'.'));

      if($ext=='.png'){$img_format='Png';}
      else if($ext=='.jpeg' or $ext=='.jpg'){
        $img_format='Jpeg';
      }else{$img_format='';} 
      
      if($img_format){
        $this->emit(
        '$pdf->add'.$img_format.'FromFile("'.$this->template_dir."/\".".$this->a2rs($a['SRC'],
                        $this->a2r($a['X'],
                         $this->a2ri($a['Y'],
                          $this->a2r($a['WIDTH'],
                           $this->a2p($a['HEIGHT'],0))))).');'
        );
      }
      break;                            

    case "ezsety":
      $this->emit('$pdf->ezSetY('.$this->a2ri($a['Y']).');');
      break; 
      
    case "eztext":
      array_push($this->stack,$this->mode);
      array_push($this->stack,$a['SIZE']); 
      
      $options=array();
      if($a['LEFT']){$options['left']=$this->a2p($a['LEFT'],0);}
      if($a['RIGHT']){$options['right']=$this->a2p($a['RIGHT'],0);}
      if($a['ALEFT']){$options['aleft']=$this->a2p($a['ALEFT'],0);}
      if($a['ARIGHT']){$options['aright']=$this->a2p($a['ARIGHT'],0);}
      if($a['JUSTIFY']){$options['justification']=$this->a2ps($a['JUSTIFY'],'');}
      if($a['JUSTIFICATION']){$options['justification']=$this->a2ps($a['JUSTIFICATION'],'');}
      if($a['LEADING']){$options['leading']=$this->a2p($a['LEADING'],0);}
      if($a['SPACING']){$options['spacing']=$this->a2p($a['SPACING'],0);}
      array_push($this->stack,$options);
      
      $this->mode=3;
      break;
      
    case "eztable":
      $this->emit('$pdf->ezTable('.$this->a2r($a['DATA'],
                           $this->a2p($a['COLS'],0)).');');
      break;
      
    case "table":  
      array_push($this->stack,$this->mode);

      $opt = $this->a2opt($a,array(
        'showLines',
        'showHeadings',
        'shaded',
        'fontSize',
        'rowGap',
        'colGap',
        'titleFontSize',
        'xPos',
        'xOrientation',
        'width',
        'maxWidth',
        'innerLineTickness',
        'outerLineTickness',
        'protectRows'));
      
      if($opt){
        $this->emit('$t_opt='.$opt.';');
      }else{
        $this->emit('$t_opt=array();');
      }
      $this->emit('$t_data=array();');
      
      break; 

    case "trh": 
      $this->emit('$t_cols=array();');
      $this->emit('$t_opt["cols"]=array();');
      break;

    case "tdh":
    
      $opt_1 = $this->a2opt($a,array('width','justification'));
      
      if($opt_1){
        $this->emit('$t_opt["cols"][]='.$opt_1.';');
      }
          
      array_push($this->stack,$this->mode);
      
      $this->mode=3;	      

      break;
    
    case "tr":
      if(isset($a["FOREACH"])){
        $args=$this->args;
	$var=$this->a2rs($a["FOREACH"]);
	$d='$'.$args.'['.$var.']';
	
	$this->emit("if(isset($d)){");
	$this->emit('foreach('.$d.' as $row){');
	array_push($this->stack,$args);
        $this->args='row';
	array_push($this->stack,1);	
      }else{
        array_push($this->stack,0);
      }      
      $this->emit('$t_data[]=array();');
      break;
    
    case "td":
      array_push($this->stack,$this->mode);
      $this->mode=3;      
      
      break;

    case "include":
      $this->res_includes_names[]=$a["NAME"];
      $this->res_includes.='
      $temp =& $this->engine->getTemplate("'.$a["NAME"].'",0);
      $temp->write($pdf,$data);
      
';      
      break;
      
    case "format":
      $type=$this->a2r($a['TYPE']);
      $var=$this->a2rs($a['VAR']);
      $var='$'.$this->args.'['.$var.']';
      $format=$this->a2rs($a['VALUE']);
      
      if(isset($a['OUT'])){
        $out=$this->a2rs($a['OUT']);
        $out='$'.$this->args.'['.$out.']';
      }else{
        $out=$var;
      }
      
      switch($type){
        case "date":
	  if(!isset($a['iformat'])){
	    $iformat='sql';
	  }else{
	    $iformat=strtolower($a['iformat']);
	  }  
	  
	  if($iformat=='sql'){
	    $this->emit("$out=strftime($format,strtotime($var));");
	  }else{
 	    $this->emit("$out=strftime($format,$var);");
	  }
	  break;

	case "spf":
          $this->emit("$out=sprintf($format,$var);");
          break;

        case "barcode":
	  $this->emit("$out=generate_barcode($format,$var);");
	  break;
	default:
	  $this->error("unknown format type $type");  
      }
      
      break;  
    }   
 
  }

  function endElement ($parser, $name) {
  if($this->mode==3  and $name!="EZTEXT" and
      $name!="TDH" and $name!="TD"){
    $this->ezTextText.="</".strtolower($name).">";
    return;
  }

  switch(strtolower($name)){

    case "static":    
    case "dynamic":
      $this->mode=0;
    break;
    case "eztext":
      $options=array_pop($this->stack);
      if(!empty($options)){
        $res="array("; 
        foreach($options as $n=>$v){
	  $res.="$vir'$n'=>$v";
	  $vir=", ";
	}
	$res.=")";
      } 
       

      $size=array_pop($this->stack);      
      $this->mode=array_pop($this->stack);
      
      $this->emit('$pdf->ezText('.$this->a2rs($this->ezTextText, 
                                               $this->a2p($size,0,$res)).');');
      $this->ezTextText='';				       
      break;
  
    case "tdh":
      $this->mode=array_pop($this->stack);
      
      $this->emit('$t_cols[]='.$this->a2ps($this->ezTextText,'').';');

      $this->ezTextText='';				       
      break;

    case "tr":
      if(array_pop($this->stack)){
        $this->emit("}}");
        $this->args=array_pop($this->stack);
      } 
      
      break;    

    case "td":
      $this->mode=array_pop($this->stack);
      $this->emit('array_push($t_data[count($t_data)-1], '.$this->a2ps($this->ezTextText,'').');');

      $this->ezTextText='';				       


      break;      
      
    case "table":
//      $this->emit('echo "<pre>";print_r($t_data);print_r($t_cols);print_r($t_opt);echo"</pre>";');      
      $this->emit('$pdf->ezTable($t_data,$t_cols,"",$t_opt);');      
      break;
  }
  }

  function characterData ($parser, $data) {
    if($this->mode==3 or $this->mode==6){
      $this->ezTextText.=$data;
    }
  }

  function PIHandler($parser, $target, $data) {
    switch (strtolower($target)) {
        case "php":
	  $this->emit($data);
            /*global $parser_file;
            // If the parsed document is "trusted", we say it is safe
            // to execute PHP code inside it.  If not, display the code
            // instead.
            if (trustedFile($parser_file[$parser])) {
                eval($data);
            } else {
                printf("Untrusted PHP code: <i>%s</i>", 
                        htmlspecialchars($data));
            }*/
            break;
    }
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

  function new_xml_parser() {
 
    $xml_parser = xml_parser_create();
    xml_set_object($xml_parser, $this);
    xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 1);
    xml_set_element_handler($xml_parser, "startElement", "endElement");
    xml_set_character_data_handler($xml_parser, "characterData");
    xml_set_processing_instruction_handler($xml_parser, "PIHandler");
    //xml_set_default_handler($xml_parser, "defaultHandler");
    //xml_set_external_entity_ref_handler($xml_parser, "externalEntityRefHandler");
    
    return $xml_parser;
  }

  function make_uses ($vars){
    $res="array(";
    foreach($vars as $var){
      if(is_array($var)){
        $res.=$sep.$this->make_uses($var);
      }else{
        $res.="$sep'$var'";
      }
      $sep=",";
    }
    return "$res)";
  }
  
  function compile ($input,$out_class_name){
    $this->res_includes ="";
    $this->res_stat ="";
    $this->res_dyna ="";
    $this->mode=0; //1 static 2 dynamic 0 none 3 ezText
    $this->stack=array(); // local stack
    $this->vars=array();
    $this->errors=array();
    $this->res_includes_names=array();
    $this->lang=0;

    $this->xml_parser=$this->new_xml_parser();

		if(preg_match('/<\?recode.*encoding.*=.*"(.*)".*\?>/i',$input,$matches)){
		  $recode=$matches[1];
			$input=iconv("UTF-8",$recode,$input);
			$this->reconv=$recode;
		}
		
    if (!xml_parse($this->xml_parser, $input, TRUE)) {
      $this->error(xml_error_string(xml_get_error_code($this->xml_parser)));
    }
  
    xml_parser_free($this->xml_parser);

    if(empty($this->errors)){
      if(!empty($this->vars)){
        $vars="function get_used_vars(){ return ".$this->make_uses($this->vars).";}\n";
      }else{
        $vars='';
      }

      if($this->lang){
        $langcode="setlocale(LC_TIME,'{$this->lang}');";
      }

      $ret=
'
/*this is a generated file. do not edit!

produced '.date("l dS of F Y h:i:s A").' 


*/
require_once(\'functions/barcode_func.php\');
class '.$out_class_name.' {

  var $object_id;
  var $engine;
	var $reconv="'.$this->reconv.'";
  
  function '.$out_class_name.'(){}
  function write(&$pdf,&$data){
    '.$langcode.'
'.
($this->res_stat?
'    if(!isset($this->object_id)){
      $this->object_id=$pdf->openObject();
      '.$this->res_stat.'      
      $pdf->closeObject($this->object_id);
    }
    $pdf->addObject($this->object_id);
':'')
.$this->res_dyna.'
'.$this->res_includes.'    
  }
'.$vars.'

}
';
//echo "<pre>$ret</pre>";
return $ret;
    }else{
      return FALSE;
    }
  }
}
?>