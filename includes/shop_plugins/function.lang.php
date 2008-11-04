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


function smarty_function_lang ($params,&$smarty) {
  global $_SHOP;

  if($params['assign']){
    $smarty->assign($params['assign'],$_SHOP->lang);
  }

  if($lang=$params['set'] and in_array($lang,$_SHOP->langs)){
    $_SHOP->lang=$lang;
    $_SESSION['_SHOP_LANG']=$lang;
    setlocale(LC_TIME,$_SHOP->lang) or
    setlocale(LC_TIME,$_SHOP->lang.'_'.strtoupper($_SHOP->lang));
  }

  if(!$params['assign'] and !$params['set']){
    return $_SHOP->lang;
  }
}
?>