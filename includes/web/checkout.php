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
$_SHOP->smarty = $smarty;

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

$smarty->template_dir = array($_SHOP->tpl_dir.'web'.DS.'custum'.DS, $_SHOP->tpl_dir.'web'.DS);
$smarty->compile_dir  = $_SHOP->tmp_dir; // . '/web/templates_c/';
$smarty->compile_id   = 'webshop';
$smarty->cache_dir    = $_SHOP->tmp_dir;// . '/web/cache/';
$smarty->config_dir   = $_SHOP->includes_dir . 'lang'.DS;

$smarty->plugins_dir = array("plugins", $_SHOP->includes_dir . "shop_plugins");


if (isset($_REQUEST['sor'])) {
  if (is_callable($action.'action') and ($fond = call_user_func_array($action.'action',array($smarty)))) {
      $smarty->display($fond . '.tpl');
  }
//  session_write_close();
  exit();

} elseIf ($cart->can_checkout_f() or isset($_SESSION['_SHOP_order']) ) { //or isset($_SESSION['order'])
  If (!$user->logged and
      $action !== 'register' and
      $action !== 'login' ) {
    $smarty->display('checkout_user.tpl');
//    session_write_close();

    exit();
  }
  if (is_callable($action.'action') and ($fond = call_user_func_array($action.'action',array($smarty)))) {
      $smarty->display($fond . '.tpl');
  }
//  session_write_close();
  exit();
}
if ($action == 'edituser') {
  echo "
    <script>
      window.close();
    </script>";
} else {
  redirect("index.php?action=cart_view",403);
}
die();

  function getsecurecode() {
    if (isset($_POST['sor'])) {
      $return = urldecode( $_POST['sor']);
    } elseif (isset($_GET['sor'])) {
      $return = $_GET['sor'];
    } elseif (strlen( $_SERVER["PATH_INFO"])>1) {
      $return = substr($action, 1);
    } else {
      Print_r($_REQUEST); Print_r($_SERVER);
      $return ='';
    }
  //  echo $return;
    return $return;
  }

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
    $smarty->assign('shop_order', (array)$aorder);

    $smarty->assign('order_seats_id',$seats);
  }

  Function loginAction ($smarty){
    global $user;
    if (!$user->logged) {
  	  If (! $user->login_f($_POST['username'], $_POST['password'], $errors)) {
  	    $smarty->assign('login_error',$errors);
  	    return "checkout_user";
      }
    }
    return "checkout_preview";
  }

  Function usereditAction ($smarty){
    global $user;
    $smarty->assign('usekasse',true);
    if ($_POST.submit_update) {
      if ($user->update_member_f($_POST, $errors)) {
        echo "
          <script>
             window.opener.location.href = window.opener.location.href;
             window.close();
          </script>";
        return "";
      }
      $smarty->assign('user_errors', $errors);
      $smarty->assign('user_data',   $_POST);
    } else {
      print_r($_SESSION);
      $smarty->assign('user_data',   $_SESSION['_SHOP_USER']);
    }
    return "user_update";
  }

  Function registerAction ($smarty){
    global $user;
    $type = 'guest';
    if ($type =='guest') {
      $smarty->assign('newuser_id',$user->guest_f($_POST, $errors));
    } elseif ($type=='member') {
      $smarty->assign('newuser_id',$user->Member_f($_POST, $errors));
    } else
      $errors['_error'] = con('RegisterError');

    If ($errors) {
      $smarty->assign('user_data',   $_POST);
      $smarty->assign('reg_type',    $type);
      $smarty->assign('user_errors', $errors);
      return "checkout_user";
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
      $cart->destroy_f();
      $hand= $myorder->order_handling;
      $confirmtext = $hand->on_confirm($myorder);

      if (is_array($confirmtext)) {

        $smarty->assign('pm_return',$confirmtext);
        if(!$confirmtext['approved'])
           $myorder->order_delete($myorder->order_id );
        unset( $_SESSION['_SHOP_order']);
        return "checkout_result";
      } else {
      if ($hand->is_eph()) {
        $_SESSION['_SHOP_order'] = $myorder;
      }
      $order->obj = $myorder;
        $smarty->assign('confirmtext', $confirmtext);
      return "checkout_confirm";
    }
  }
  }

  function  submitaction($smarty) {
    $myorder = is($_SESSION['_SHOP_order'],null);
    $test = Order::DecodeSecureCode($myorder, getsecurecode());
    if($test < 1) {
  //    header('HTTP/1.1 404 '.con('OrderNotFound'), true, 404);
      ShopDB::dblogging("submit error ($test): $myorder->order_id\n". print_r($myorder, true));

      unset( $_SESSION['_SHOP_order']);
      return;
    }
    setordervalues($myorder, $smarty);
    $hand= $myorder->order_handling;
    $pm_return = $hand->on_submit($myorder,$errors);
    $smarty->assign('errors', $errors);
    if (empty($pm_return)) {
      return '';
    } elseif (is_string($pm_return)) {
      $order->obj = $myorder;
      $smarty->assign('confirmtext', $pm_return);
      return "checkout_confirm";
    } else  {
      $smarty->assign('pm_return',$pm_return);
      if(!$pm_return['approved'])
         $myorder->order_delete($myorder->order_id );
      unset( $_SESSION['_SHOP_order']);
      return "checkout_result";
    }
  }

  function  printaction($smarty) {
    Global $order;
    $myorder = is($_SESSION['_SHOP_order'],null);
    $test = Order::DecodeSecureCode($myorder, getsecurecode());
    if($test < 1) {
      header('HTTP/1.1 502 '.con('OrderNotFound'), true, 502);
      ShopDB::dblogging("print error ($test): $myorder->order_id\n". print_r($myorder, true));
      echo "print error $test" ; print_r($myorder);
      unset( $_SESSION['_SHOP_order']);
      return;
    }

    Order::print_order($myorder->order_id, '', 'stream');
    return;
  }

  function  acceptaction($smarty) {
    $myorder = is($_SESSION['_SHOP_order'],nil);
    $test = Order::DecodeSecureCode($myorder, getsecurecode());
    if($test < 1) {
      echo "accept error ($test): $myorder->order_id\n". print_r($myorder, true);
      //header('HTTP/1.1 502 '.con('OrderNotFound'), true, 502);
      ShopDB::dblogging("accept error ($test): $myorder->order_id\n". print_r($myorder, true));
      unset( $_SESSION['_SHOP_order']);
      return;
    }
 //   echo "accept ok ($test): $myorder->order_id\n". print_r($myorder, true);
    $hand=$myorder->order_handling;
    setordervalues($myorder, $smarty);

    $pm_return = $hand->on_return($myorder, true);
    $smarty->assign('pm_return',$pm_return);
    If (!$pm_return['approved']) {
       $myorder->order_delete($myorder->order_id );
       $pm_return['response'] .= "<div class='error'>".con('orderdeleted')."</div>";
       
    }
    unset( $_SESSION['_SHOP_order']);
    return "checkout_result";
  }

  function  cancelaction($smarty) {
    $myorder = is($_SESSION['_SHOP_order'],null);
    $test = Order::DecodeSecureCode($myorder, getsecurecode());
    if($test < 1) {
      header('HTTP/1.1 502 '.con('OrderNotFound'), true, 502);
      ShopDB::dblogging("cancel error ($test): $myorder->order_id\n". print_r($myorder, true));
      unset( $_SESSION['_SHOP_order']);
      return;
    }
    setordervalues($myorder, $smarty);
    $hand=$myorder->order_handling;
    $myorder->order_delete($myorder->order_id );
    $pm_return = $hand->on_return($myorder, false );
    $pm_return['response'] .= "<div class='error'>".con('orderdeleted')."</div>";
    $smarty->assign('pm_return',$pm_return);
    unset( $_SESSION['_SHOP_order']);
    return "checkout_result";
  }

  function  notifyaction() {
    $myorder = is($_SESSION['_SHOP_order'], null);
    $test = Order::DecodeSecureCode($myorder, getsecurecode());
    if($test < 1) {
       header('HTTP/1.1 502 Action not allowed', true, 502);
       ShopDB::dblogging("notify error ($test): $myorder->order_id\n". print_r($myorder, true));
       return;
    }
    ShopDB::dblogging("notify action ($test): $myorder->order_id.\n");
    $hand=$myorder->order_handling;
    $hand->on_notify($myorder);
  }
//session_write_close();
?>