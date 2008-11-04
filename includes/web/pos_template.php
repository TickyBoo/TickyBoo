<?php
require_once("../includes/config/init_common.php");

require_once('smarty/Smarty.class.php');
require_once('classes/MyCart_Smarty.php');
require_once('classes/UserAuth_Smarty.php');
require_once('classes/User_Smarty.php');
require_once('classes/Order_Smarty.php');

require_once("config/init_spoint.php");

global $_SHOP;

$smarty = new Smarty;

$cart_s = new MyCart_Smarty($smarty);
$user_s = new UserAuth_Smarty($smarty);
$user = new User_Smarty($smarty);
$order = new Order_Smarty($smarty);

$smarty->assign('_SHOP_user_root', $_SHOP->user_root);
$smarty->assign('_SHOP_user_root_secured', $_SHOP->user_root_secured);
$smarty->assign('_SHOP_root', $_SHOP->root);
$smarty->assign('_SHOP_root_secured', $_SHOP->root_secured);
$smarty->assign('organizer_currency', $_SHOP->organizer_data->organizer_currency);
$smarty->assign('organizer', $_SHOP->organizer_data);
$smarty->assign('_SHOP_lang', $_SHOP->lang);

$smarty->template_dir = $_SHOP->tpl_dir . '/pos/';
$smarty->compile_dir = $_SHOP->tmp_dir . '/pos/templates_c/';
$smarty->cache_dir = $_SHOP->tmp_dir . '/pos/cache/';
$smarty->config_dir = $_SHOP->includes_dir . '/lang/';

$smarty->plugins_dir = array("plugins", $_SHOP->includes_dir . "/shop_plugins");
$smarty->config_load("shop_" . $_SHOP->lang . ".conf");

$smarty->display($fond . '.tpl');

?>
