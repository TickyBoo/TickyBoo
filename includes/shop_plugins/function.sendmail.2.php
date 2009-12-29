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

//data,files,required,template
function smarty_function_sendmail ($params,&$smarty) {
  global $_SHOP;
  
  $data= (array)$params['data'];
  $attach_files=$params['attach_files'];
  $required=$params['required'];
  $template=$params['template'];
  
  if(!$template){
    $smarty->assign('sendmail_status',FALSE);
    return;
  }   
 
  if($data and $required){
    $required_a=explode(',',$required);
    foreach($required_a as $name){
      if(!$data[$name]){
        $errs[$name]=TRUE;
      }
    }
  }
  
  if($errs){
    $smarty->assign('sendmail_status',FALSE);
    $smarty->assign('sendmail_errors',$errs);
    return;
  }
  
  if($files){
    foreach($files as $f_name=>$file){
      if($file){
        foreach($file as $name=>$value){
	  $data[$f_name.'_'.$name]=$value;
	}
      }
    }
  }

  require_once("classes/htmlMimeMail.php");
  require_once("classes/TemplateEngine.php");
  
  $te=new TemplateEngine;
  $tpl=&$te->getTemplate($template);
  
  $email = new htmlMimeMail();
  
  $tpl->build($email,$data,$_SHOP->lang);

  if($attach_files){
    if($_FILES){
      foreach($_FILES as $file){
        $email->addAttachment($email->getFile($file['tmp_name']),$file['name']);
      }
    }
  } 

  if(!$email->send($tpl->to)){
    $smarty->assign('sendmail_status',FALSE);
    return;
  }

  $smarty->assign('sendmail_status',TRUE);
}
?>