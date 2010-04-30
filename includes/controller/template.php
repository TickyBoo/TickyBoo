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

if (!defined('ft_check')) {die('System intrusion ');}

require_once ('includes/config/init_shop.php');

/*/Check page is secure
if($_SERVER['SERVER_PORT'] != 443 || $_SERVER['HTTPS'] !== "on") {
$url = $_SHOP->root_secured.$_SERVER['REQUEST_URI'];
echo "<script>window.location.href='$url';</script>"; exit;
//header("Location: https://"$_SHOP->root_secured.$_SERVER['SCRIPT_NAME']);exit;}
}
//remove the www. to stop certificate errors.
if(("https://".$_SERVER['SERVER_NAME']."/") != ($_SHOP->root_secured)) {
$url = $_SHOP->root_secured.$_SERVER['REQUEST_URI'];
echo "<script>window.location.href='$url';</script>"; exit;
}*/

require_once ( 'smarty/Smarty.class.php');
require_once ( 'classes/smarty.mycart.php');
require_once ( 'classes/smarty.user.php');
require_once ( 'classes/smarty.order.php');
require_once ( 'classes/smarty.gui.php');
require_once ( 'classes/smarty.update.php');
require_once ( 'config/init.php');


// remove the # below under linux to get a list of locale tags.

#  print_r(list_system_locales());

global $_SHOP;


$smarty = new Smarty;
$_SHOP->smarty = $smarty;

$gui    = new Gui_smarty($smarty);
$cart   = new MyCart_Smarty($smarty);
$user   = new User_Smarty($smarty);
$order  = new Order_Smarty($smarty);
$update = new Update_Smarty($smarty);
$gui->gui_name  ='TblLower';
$gui->gui_value ='TblHigher';

$smarty->assign('_SHOP_root', $_SHOP->root);
$smarty->assign('_SHOP_root_secured', $_SHOP->root_secured);
$smarty->assign('_SHOP_lang', $_SHOP->lang);
$smarty->assign('_SHOP_theme', $_SHOP->tpl_dir . "theme".DS. $_SHOP->theme_name.DS );
$smarty->assign('_SHOP_themeimages', $_SHOP->images_url . "theme/". $_SHOP->theme_name.'/' );
$smarty->assign("_SHOP_files", $_SHOP->files_url );//ROOT.'files'.DS
$smarty->assign("_SHOP_images", $_SHOP->images_url);
$smarty->assign("_SHOP_theme_css", "css/theme/".$_SHOP->theme_name."/" );

$smarty->assign('organizer_currency', $_SHOP->organizer_data->organizer_currency);
$smarty->assign('organizer', $_SHOP->organizer_data);


$smarty->template_dir = array($_SHOP->tpl_dir.'web'.DS.'custom'.DS,$_SHOP->tpl_dir.'web'.DS.'custum'.DS, $_SHOP->tpl_dir.'web'.DS);
$smarty->compile_id   = 'webshop_'.$_SHOP->lang;
$smarty->config_dir   = $_SHOP->includes_dir . 'lang'.DS;
$smarty->compile_dir  = substr($_SHOP->tmp_dir,0,-1); // . '/web/templates_c/';
$smarty->cache_dir    = substr($_SHOP->tmp_dir,0,-1); // . '/web/cache/';


$smarty->plugins_dir  = array("plugins", $_SHOP->includes_dir . "shop_plugins".DS);

If ($fond) {
  $smarty->display($fond . '.tpl');
  orphanCheck();
  trace("End of shop \n\n\r");
 //print_r($_SHOP->Messages);
}

?>