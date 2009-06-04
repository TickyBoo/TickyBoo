<?php

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