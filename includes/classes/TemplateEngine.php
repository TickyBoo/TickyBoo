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


 //Manages templates. 
 //Usage: 
 //$engine = new TemplateEngine();
 //$template = $engine->getTemplate('ticket',$_SHOP->organizer_id);
 //$res=$template->write($data);
 class TemplateEngine {

  
  function TemplateEngine (){
  }

	//internal function: loads, initializes the template object, and updates cache
  function &try_load ($name, $t_class_name, $code){
    global $_SHOP;
    //print_r($code['template_code']);
    eval($code['template_code']);
    if(class_exists($t_class_name)){
      $tpl=&new $t_class_name;
      $tpl->sourcetext= $code[template_text];
//      $tpl->engine=&$this;
      $_SHOP->templates[$name]=&$tpl;
      return $tpl;
    }
  }  

	//returns the template object or false
  function &getTemplate($name){
    global $_SHOP;

    //check if the template is in cache
    if(isset($_SHOP->templates[$name])){
      $res=&$_SHOP->templates[$name];
      return $res;
    }
    
		//if not: load the template record from db
    
    $query="SELECT * FROM Template WHERE template_name='$name'";
    if(!$data=ShopDB::query_one_row($query)){
      return FALSE;
    }
    
    $t_class_name= str_replace(' ','_',"TT_{$data['template_name']}");
    
    //trying to load already compiled template
    if($data['template_status']=='comp'){
      if($tpl= TemplateEngine::try_load($name, $t_class_name, $data)) {
        return $tpl;
      }
    }
    //need to compile: loading compiler
    if($data['template_type']=='email'){
      require_once("classes/EmailTCompiler.php");
      $comp=new EmailTCompiler;
    }else if($data['template_type']=='pdf2'){
      require_once("classes/PDF2TCompiler.php");
      $comp=new PDF2TCompiler;
    }else{
      user_error("unsupported template type: ".$data['template_type']);
    }


    //trying to compile
    if(!$code=$comp->compile($data['template_text'],$t_class_name)){

      $this->errors = $comp->errors;
      $query="UPDATE Template SET template_status='error' WHERE template_id='{$data['template_id']}'";
      ShopDB::query($query);
      return FALSE;
    }
    $data['template_code'] = $code;
		//truying to load just compile template
	  if($tpl=TemplateEngine::try_load($name, $t_class_name, $data)){

      //compilation ok: saving the code in db
      $query="UPDATE Template SET template_status='comp', template_code="._esc($code)." WHERE template_id='{$data['template_id']}'";

      if(!ShopDB::query($query)){
				return FALSE;
      }

      return $tpl;
    }else{
			//compilation failed
      $query="UPDATE Template SET template_status='error', template_code=NULL WHERE template_id='{$data['template_id']}'";

      if(!ShopDB::query($query)){
				return FALSE;
      }
    }
    return false;
  }
}
?>