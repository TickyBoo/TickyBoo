<?php
global $action;
$action =(isset($_REQUEST['action']) and $_REQUEST['action'])?$_REQUEST['action']:'index';
$_REQUEST['pos'] = true;
require_once('../includes/web/checkout.php');

?>