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

require_once ( "../includes/config/init_common.php" );

require_once ( 'smarty/Smarty.class.php' );
require_once ( 'classes/MyCart_Smarty.php' );
require_once ( 'classes/POS_Smarty.php' );
require_once ( 'classes/User_Smarty.php' );
require_once ( 'classes/Order_Smarty.php' );
require_once('classes/gui_smarty.php');
require_once('classes/Update_Smarty.php');
require_once ( "config/init_spoint.php" );

global $_SHOP;

$smarty = new Smarty;
$gui    = new Gui_smarty($smarty);
$cart_s = new MyCart_Smarty( $smarty );
$pos    = new POS_Smarty( $smarty );
$user   = new User_Smarty( $smarty );
$order  = new Order_Smarty( $smarty );
$update = new Update_Smarty($smarty);

$smarty->assign( '_SHOP_root', $_SHOP->root );
$smarty->assign( '_SHOP_root_secured', $_SHOP->root_secured );
$smarty->assign( '_SHOP_lang', $_SHOP->lang );
$smarty->assign( '_SHOP_theme', $_SHOP->theme_dir );

$smarty->assign( 'organizer_currency', $_SHOP->organizer_data->organizer_currency );
$smarty->assign( 'organizer', $_SHOP->organizer_data );


$smarty->template_dir = $_SHOP->tpl_dir . 'pos' . DS;
$smarty->compile_dir  = $_SHOP->tmp_dir; // . '/web/templates_c/';
$smarty->compile_id   = 'pos_'.$_SHOP->lang;
$smarty->cache_dir    = $_SHOP->tmp_dir; // . '/web/cache/';
$smarty->config_dir   = $_SHOP->includes_dir . 'lang' . DS;

$smarty->plugins_dir  = array( "plugins", $_SHOP->includes_dir . "shop_plugins" );

$smarty->display( $fond . '.tpl' );

?>