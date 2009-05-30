<?php
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