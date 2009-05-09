<?php
require_once("includes/config/init_common.php");
require_once("classes/Order.php");
echo "<b>EncodeSecureCode:</b><br>\n";
$order = Order::load(140);
echo $code = $order->EncodeSecureCode(null,'');
echo "<br>\n<br>\n<b>DecodeSecureCode:</b><br>\n";
echo $order->DecodeSecureCode($ordernew,$code),': ';
Print_r($ordernew);

?>