<?php
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2010 Christopher Jenkins, Niels, Lou. All rights reserved.
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

global $_SHOP, $_COUNTRY_LIST;
if (!isset($_COUNTRY_LIST)) {
  If (file_exists($_SHOP->includes_dir."/lang/countries_". $_SHOP->lang.".inc")){
    include_once("lang/countries_". $_SHOP->lang.".inc");
  }else {
    include_once("lang/countries_en.inc");
  }
}

function smarty_block_countries ($params, $content, &$smarty, &$repeat) {
global $_COUNTRY_LIST;
  if ($repeat) {
    $data = array();
    if($params['code']){
      $res=$_COUNTRY_LIST[$params['code']];
      $data[] = array('key'=>$params['code'], 'name'=>$res);

    } else {
      if($params['first'] and isset($_COUNTRY_LIST[$params['first']])){
        $data[] = array('key'=>$params['first'],'name'=>$_COUNTRY_LIST[$params['first']]);
      }
      foreach ($_COUNTRY_LIST as $k => $v) {
        if($params['first'] <> $k){
          $data[] = array('key'=>$k, 'name'=>$v);
        }
      }
    }
    $country=$data[0];
    $res = array($data,1);
  }else{
    $res=array_pop($smarty->_SHOP_db_res);
    If (count($res[0]) >= $res[1]) {
      $country=$res[0][$res[1]++];
    }
  }

  $repeat=!empty($country);

  if($country){
    $smarty->assign("country",$country);

    $smarty->_SHOP_db_res[]=$res;
  }

  return $content;
}
?>