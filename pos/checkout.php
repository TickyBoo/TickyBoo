<?php
global $action;
$action =(isset($_REQUEST['action']) and $_REQUEST['action'])?$_REQUEST['action']:'index';
require_once('includes/web/pos_checkout.php');
?>