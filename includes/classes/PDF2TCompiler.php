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


class PDF2TCompiler {
  var $res_stat ="";
  var $res_dyna ="";
  var $mode=0; //1 static 2 dynamic 0 none 3 ezText 
  var $stack=array(); // local stack
  var $vars=array();
  var $args='data';
  var $errors;
  
  function PDF2TCompiler ($font_dir=''){
    global $_SHOP;
    if($font_dir){
      $this->font_dir=$font_dir;
    }else{
      $this->font_dir=$_SHOP->font_dir;
    }
    //$this->template_dir=$_SHOP->template_dir;
    
    $this->template_dir=$_SHOP->tpl_dir;
  }

  function build ($pdf, $data, $testme=false){
    global $_SHOP;
    require_once("smarty/Smarty.class.php");
    require_once("classes/gui_smarty.php");

    $smarty = new Smarty;
    $gui    = new gui_smarty($smarty);

    $smarty->plugins_dir  = array("plugins", $_SHOP->includes_dir . "shop_plugins");
    $smarty->cache_dir    = $_SHOP->tmp_dir;
    $smarty->compile_dir  = $_SHOP->tmp_dir;
    $smarty->compile_id   = "HTML2PDF";
    $smarty->assign("_SHOP_lang", $_SHOP->lang);
    $smarty->assign((array)$_SHOP->organizer_data);
    $smarty->assign($data);
    $smarty->assign("OrderData",$data);
    $smarty->assign("_SHOP_files",  $_SHOP->files_url);
    $smarty->assign("_SHOP_images", $_SHOP->images_url);


    $smarty->my_template_source = $this->sourcetext;
    $htmlresult = $smarty->fetch("text:".'.$out_class_name.');
    $pdf->WriteHTML($htmlresult, $testme);
    unset($smarty);
    unset($gui);
  }

  function compile ($input, $out_class_name){

$ret=
'
/*this is a generated file. do not edit!

produced '.date("l dS of F Y h:i:s A").' 

*/
require_once("classes/PDF2TCompiler.php");

class '.$out_class_name.' extends PDF2TCompiler {
  function write($pdf, $data, $testme=false){
    $this->build($pdf, $data, $testme);
  }
}
';
    //  echo "<pre>$ret</pre>";
      return $ret;
  }
}
?>