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
global $_SHOP, $_COUNTRY_LIST;
if (!isset($_COUNTRY_LIST)) {
  If (file_exists($_SHOP->includes_dir."/lang/countries_". $_SHOP->lang.".inc")){
    include_once("lang/countries_". $_SHOP->lang.".inc");
  }else {
    include_once("lang/countries_en.inc");
  }
}

class CountriesList {
  function printForm($sel_name,$selected,&$err){
  global $_SHOP,  $_COUNTRY_LIST;
    if($_SHOP->lang=='de'){
  	  if(empty($selected)){$selected='CH';}
    }else{
   	  if(empty($selected)){$selected='US';}
    }

    echo "<select name='$sel_name'>";
    $si[$selected]=' selected';
    foreach ($_COUNTRY_LIST as $key=>$value){
      echo "<option value='$key' {$si[$key]}>$value</option>";
    }
    echo "</option>";
    echo "<div class='error'>{$err[$sel_name]}</div>";
  }

  function getCountry($val){
    global $_SHOP, $_COUNTRY_LIST;
    $val=strtoupper($val);
    return $_COUNTRY_LIST[$val];
  }
}
?>