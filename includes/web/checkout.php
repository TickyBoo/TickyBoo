<?php

global $_SHOP, $action;

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
require_once('smarty/Smarty.class.php');
require_once('classes/MyCart_Smarty.php');
require_once('classes/User_Smarty.php');
require_once('classes/Order_Smarty.php');
require_once('classes/Update_Smarty.php');
require_once('classes/gui_smarty.php');

require_once("config/init_shop.php");


$smarty = new Smarty;

$gui    = new Gui_smarty($smarty);
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


if (isset($_REQUEST['sor'])) {
  if (is_callable($action.'action') and ($fond = call_user_func_array($action.'action',array($smarty)))) {
      $smarty->display($fond . '.tpl');
  }
  die();

} elseIf ($cart->can_checkout_f() or isset($_SESSION['_SHOP_order']) ) { //or isset($_SESSION['order'])
  If (!$user->logged and
      $action !== 'register' and
      $action !== 'login' ) {
    $smarty->display('user.tpl');
    die();
  }
  if (is_callable($action.'action') and ($fond = call_user_func_array($action.'action',array($smarty)))) {
      $smarty->display($fond . '.tpl');
  }
  die();
}

redirect("index.php?action=cart_view",403);
die();

  function setordervalues($aorder, $smarty){
    if (!is_object($aorder)) exit;
    if (isset($aorder) and isset($aorder->places)) {
      foreach($aorder->places as $ticket){
        $seats[$ticket->id]=TRUE;
      }
    }
    $smarty->assign('order_success',true);
    $smarty->assign('order_id',$aorder->order_id);
    $smarty->assign('order_fee',$aorder->order_fee);
    $smarty->assign('order_total_price',$aorder->order_total_price);
    $smarty->assign('order_partial_price',$aorder->order_partial_price);
    $smarty->assign('order_tickets_nr',$aorder->size());
    $smarty->assign('order_shipment_mode',$aorder->order_shipment_mode);
    $smarty->assign('order_payment_mode',$aorder->order_payment_mode);

    $smarty->assign('shop_handling', (array)$aorder->order_handling);

    $smarty->assign('order_seats_id',$seats);
  }

  Function loginAction ($smarty){
    global $user;
    if (!$user->logged) {
  	  If (! $user->login_f($_POST['username'], $_POST['password'], $errors)) {
  	    $smarty->assign('login_error',$errors);
  	    return "user";
      }
    }
    return "checkout_preview";
  }

  Function registerAction ($smarty){
    $type = 'guest';
    if (!isset($_POST['submit']) or ($type=$_POST['type'])) {
      $errors['_error'] = con('RegisterError');
    } elseif ($type =='guest') {
      $smarty->assign('newuser_id',$user->guest_f($_POST, $errors));
    } elseif ($type=='member') {
      $smarty->assign('newuser_id',$user->Member_f($_POST, $errors));
    } else
      $errors['_error'] = con('RegisterError');

    $smarty->assign('reg_type', $type);
    $smarty->assign('errors', $errors);

    If ($errors) {
      return "user";
    } else
      return "checkout_preview";
  }


  function indexaction($smarty) {
    unset( $_SESSION['_SHOP_order']);
    return "checkout_preview";
  }

  function reserveaction($smarty) {
    global $order;
    $myorder = $order->make_f(1,"www");
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
    global $order, $cart;
    if (!isset($_SESSION['_SHOP_order'])) {
      $myorder = $order->make_f($_POST['handling_id'],"www");
    } else
      $myorder = $_SESSION['_SHOP_order'];

    If (!$myorder) {
      $smarty->assign('order_error', $order->error);
      return "checkout_preview";
    } else {
      setordervalues($myorder, $smarty);
    //  print_r($myorder);
      $cart->destroy_f();
      $hand= $myorder->order_handling;
      $confirmtext = $hand->on_confirm($myorder);

      $smarty->assign('confirmtext', $confirmtext);
      if ($hand->is_eph()) {
        $_SESSION['_SHOP_order'] = $myorder;
      }
      $order->obj = $myorder;
      return "checkout_confirm";
    }
  }

  function  submitaction($smarty) {
    $myorder = is($_SESSION['_SHOP_order'],nil);
    if(!Order::DecodeSecureCode($myorder)) {
      header('HTTP/1.1 404 '.con('OrderNotFound'), true, 404);
      unset( $_SESSION['_SHOP_order']);
      return;
    }
    setordervalues($myorder, $smarty);
    $hand= $myorder->order_handling;
    $pm_return = $hand->on_submit($myorder,$errors);
    $smarty->assign('errors', $errors);
    if (is_string($pm_return)) {
      $order->obj = $myorder;
      $smarty->assign('confirmtext', $pm_return);
      return "checkout_confirm";
    } else
      $smarty->assign('pm_return',$pm_return);
      if(!$pm_return['approved'])
         $myorder->order_delete($order_id );
      unset( $_SESSION['_SHOP_order']);
      return "checkout_result";
  }

  function  printaction($smarty) {
    Global $order;
    $myorder = is($_SESSION['_SHOP_order'],null);
    if(!Order::DecodeSecureCode($myorder)) {
      header('HTTP/1.1 502 '.con('OrderNotFound'), true, 502);
      echo 'print error' ; print_r($myorder);
      unset( $_SESSION['_SHOP_order']);
      return;
    }

    require_once("functions/order_func.php");
    print_order($myorder->order_id, '', 'stream', false, 1);
    return;
  }


  function  cancelaction($smarty) {
    $myorder = is($_SESSION['_SHOP_order'],null);
    if(!Order::DecodeSecureCode($myorder)) {
      header('HTTP/1.1 502 '.con('OrderNotFound'), true, 502);
      unset( $_SESSION['_SHOP_order']);
      return;
    }
    setordervalues($myorder, $smarty);
    $hand=$myorder->order_handling;
    $pm_return = $hand->on_return($myorder, false );
    $smarty->assign('pm_return',$pm_return);
    $myorder->order_delete($order_id );
    unset( $_SESSION['_SHOP_order']);
    return "checkout_result";
  }

  function  acceptaction($smarty) {
    $myorder = is($_SESSION['_SHOP_order'],nil);
    if(!Order::DecodeSecureCode($myorder)) {
      header('HTTP/1.1 502 '.con('OrderNotFound'), true, 502);
      unset( $_SESSION['_SHOP_order']);
      return;
    }
    $hand=$myorder->order_handling;
    setordervalues($myorder, $smarty);
    $pm_return = $hand->on_return($myorder, true);
    $smarty->assign('pm_return',$pm_return);
    unset( $_SESSION['_SHOP_order']);
    return "checkout_result";
  }

  function  notifyaction() {
    $myorder = is($_SESSION['_SHOP_order'],nil);
    if(!Order::DecodeSecureCode($myorder)) {
       header('HTTP/1.1 502 Action not allowed', true, 502);
       ShopDB::dblogging("notify error : $order_id\n");
       return;
    }
//       print_r($myorder);
//         ShopDB::dblogging("notify: $order_id\n");
    $hand=$myorder->order_handling;
    $hand->on_notify($myorder);
  }

?>