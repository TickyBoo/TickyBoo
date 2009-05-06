<?php
require_once("includes/config/init_common.php");

require_once('smarty/Smarty.class.php');
require_once('classes/MyCart_Smarty.php');
require_once('classes/User_Smarty.php');
require_once('classes/Order_Smarty.php');
require_once('classes/Update_Smarty.php');
require_once('classes/gui_smarty.php');

require_once("config/init_shop.php");

global $_SHOP;

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
$smarty = new Smarty;

$cart   = new Gui_smarty($smarty);
$cart   = new MyCart_Smarty($smarty);
$user   = new User_Smarty($smarty);
$order  = new Order_Smarty($smarty);
$update = new Update_Smarty($smarty);

$smarty->assign('_SHOP_root', $_SHOP->root);
$smarty->assign('_SHOP_root_secured', $_SHOP->root_secured);
$smarty->assign('_SHOP_lang', $_SHOP->lang);
$smarty->assign('_SHOP_theme', $_SHOP->theme_dir);

$smarty->assign('organizer_currency', $_SHOP->organizer_data->organizer_currency);
$smarty->assign('organizer', $_SHOP->organizer_data);

$smarty->template_dir = array($_SHOP->tpl_dir.'web'.DS.'custum'.DS, $_SHOP->tpl_dir.'web'.DS);
$smarty->compile_dir  = $_SHOP->tmp_dir; // . '/web/templates_c/';
$smarty->compile_id   = 'webshop';
$smarty->cache_dir    = $_SHOP->tmp_dir;// . '/web/cache/';
$smarty->config_dir   = $_SHOP->includes_dir . 'lang'.DS;
$smarty->plugins_dir = array("plugins", $_SHOP->includes_dir . "shop_plugins".DS);
$smarty->display($fond . '.tpl');
session_write_close();
?>