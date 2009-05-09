<?php 
error_reporting(E_ALL);
$fond='test_main';
require_once("includes/config/init_common.php");

//require_once('includes/web/test.php');
require_once('classes/Order.php');

$_POST['sor']= 'a2picTBnOjVnOjkwY3o4ZWU%3D';

echo Order::DecodeSecureCode($order ,'a2picTBnOjVnOjkwY3o4ZWU%3D' );
print_r($order);
?>