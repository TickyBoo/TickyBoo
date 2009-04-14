<?php
require_once("includes/config/init_common.php");
/*
//Check page is secure
if($_SERVER['SERVER_PORT'] != 443 || $_SERVER['HTTPS'] !== "on") {
  $url = $_SHOP->root_secured.$_SERVER['REQUEST_URI'];
  echo "<script>window.location.href='$url';</script>"; exit;
  //header("Location: https://"$_SHOP->root_secured.$_SERVER['SCRIPT_NAME']);exit;}
}
//remove the www. to stop certificate errors.
if(("https://".$_SERVER['SERVER_NAME']."/") != ($_SHOP->root_secured)) {
  $url = $_SHOP->root_secured.$_SERVER['REQUEST_URI'];
  echo "<script>window.location.href='$url';</script>"; exit;
}
*/
require_once('smarty/smarty.class.php');
require_once('classes/MyCart_Smarty.php');
require_once('classes/User_Smarty.php');
require_once('classes/Order_Smarty.php');
require_once('classes/Update_Smarty.php');
require_once('classes/gui_Smarty.php');

require_once("config/init_shop.php");

global $_SHOP;


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

$smarty->template_dir = $_SHOP->tpl_dir . 'web'.DS;
$smarty->compile_dir  = $_SHOP->tmp_dir; // . '/web/templates_c/';
$smarty->compile_id   = 'webshop';
$smarty->cache_dir    = $_SHOP->tmp_dir;// . '/web/cache/';
$smarty->config_dir   = $_SHOP->includes_dir . 'lang'.DS;

$smarty->plugins_dir = array("plugins", $_SHOP->includes_dir . "shop_plugins");

if ($action == 'notify') {
  //noting
} elseIf ($MyCart->can_checkout() or isset($_SESSION['order'])) {
  If (!$User->is_logged() and
      $action !== 'user_register' and
      $action !== 'user_login' ) {
    $smarty->display('user.tpl');
    die();
  }
}

if (is_callable($action.'action')) {
  if ($fond = call_user_func_array($action.'action')) {
    $smarty->display($fond . '.tpl');
  }
} else {
  redirect("index.php?action=cart_view",403);
}
die();

  function setordervalues($order, $smarty){

    foreach($order->places as $ticket){
      $seats[$ticket->id]=TRUE;
    }
    $smarty->assign('order_success',true);
    $smarty->assign('order_id',$order_id);
    $smarty->assign('order_fee',$order->order_fee);
    $smarty->assign('order_total_price',$order->order_total_price);
    $smarty->assign('order_partial_price',$order->order_partial_price);
    $smarty->assign('order_tickets_nr',$order->size());
    $smarty->assign('order_shipment_mode',$order->order_shipment_mode);
    $smarty->assign('order_payment_mode',$order->order_payment_mode);

    $smarty->assign('shop_handling', $order->handling);

    $smarty->assign('order_seats_id',$seats);
  }

  Function loginAction ($smarty){
    if (!$User->is_logged()) {
  	  If (! $User->login_f($_REQUEST['username'], $_REQUEST['password'])) {
  	    $smarty->assign('errors',$User->errors);
      }
    }
    return "checkout_preview";
  }

  Function registerAction ($smarty){
    $type =
    if (!isset($_POST['submit']) or ($type=$_POST['type'])) {
      $errors['_error'] = con('RegisterError');
    } elseif ($type =='guest') {
      $smarty->assign('newuser_id',$User->guest_f($_POST, $errors));
    } elseif ($type=='member') {
      $smarty->assign('newuser_id',$User->Member_f($_POST, $errors));
    } else
      $errors['_error'] = con('RegisterError');

    $smarty->assign('reg_type', $type);
    $smarty->assign('errors', $errors);

    If ($User->errors) {
      return "user";
    } else
      return "checkout_preview";
  }

  function indexaction($smarty) {
    unset( $_SESSION['order']);
    return "checkout_preview";
  }

  function reserveaction($smarty) {
    $myorder = $Order->make_f(1,"www");
    If (!$myorder) {
      return "checkout_preview";
    } else {
      setordervalues($myorder, $smarty);
      $Mycart->destroy();
      $smarty->assign('pm_return',array('approved'=>TRUE));
      return "checkout_result";
    }
  }

  function confirmaction($smarty) {
    if (!isset($_SESSION['order'])) {
      $myorder = $Order->make_f($_POST['handling_id'],"www");
      $_SESSION['order'] = $myorder;
    } else
      $myorder = $_SESSION['order'];

    If (!$myorder) {
      return "checkout_preview";
    } else {
      setordervalues($myorder, $smarty);
      $hand= $myorder->handling;
      $Mycart->destroy();
      $confirmtext = $hand->on_confirm($myorder);
      if (is_string ($confirmtext) and $confirmtext) {
        $smarty->assign('confirmtext', $confirmtext);
        return "checkout_confirm";
      } elseif (is_array($confirmtext)) {
        $smarty->assign('pm_return',$confirmtext);
        return "checkout_result";
      } else {
        $smarty->assign('pm_return',array('approved'=>TRUE));
        return "checkout_result";
      }
    }
  }

  function  submitaction($smarty) {
    $order_id = $_REGUEST['order_id'];
    if(!is_numeric($order_id) or !$myorder = $_SESSION['order'] or $myorder->order_id <> $order_id) {
      $smarty->assign('order_error', con('OrderNotFound'));
      return "checkout_preview";
    }
    setordervalues($myorder, $smarty);
    $hand= $myorder->handling;
    $pm_return = $hand->on_submit($myorder, true, $errors);
    $smarty->assign('errors', $errors);
    if (!$pm_return) {
      $confirmtext = $hand->on_confirm($myorder);
      $smarty->assign('confirmtext', $confirmtext);
      return "checkout_confirm";
    } else
      $smarty->assign('pm_return',$pm_return);
      return "checkout_result";
  }

  function  canceledaction($smarty) {
    $order_id = $_REGUEST['order_id'];
    if(!is_numeric($order_id) or !$myorder = $_SESSION['order'] or $myorder->order_id <> $order_id) {
      $smarty->assign('order_error', con('OrderNotFound'));
      return "checkout_preview";
    }
    $setordervalues($myorder, $smarty);
    $hand=$myorder->handling;
    $pm_return = $hand->on_submit($myorder, false, $errors);
    $smarty->assign('pm_return',$pm_return);
    $smarty->assign('errors', $errors);
    return "checkout_result";
  }

  function  acceptaction($smarty) {
    $order_id = $_REGUEST['order_id'];
    if(!is_numeric($order_id) or !$myorder = $_SESSION['order'] or $myorder->order_id <> $order_id) {
      $smarty->assign('order_error', con('OrderNotFound'));
      return "checkout_preview";
    }
    $hand=$myorder->handling;
    $setordervalues($myorder, $smarty);
    $pm_return = $hand->on_submit($myorder, true, $errors);
    $smarty->assign('pm_return',$pm_return);
    $smarty->assign('errors', $errors);
    return "checkout_result";
  }

  function  notifyaction() {
    $order_id = $_REGUEST['order_id'];
    if(!is_numeric($order_id) or !$myorder = Order::load($order_id, true) or $myorder->order_id <> $order_id) {
       header('HTTP/1.1 502 Action not allowed');
       ShopDB::dblogging("notify error : $order_id\n");
   die ;
    }
//       print_r($myorder);
//         ShopDB::dblogging("notify: $order_id\n");
    $hand=$myorder->handling;
    $hand->on_notify($myorder);
    die();
  }

?>