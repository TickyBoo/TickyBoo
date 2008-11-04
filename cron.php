<?php 
//remove this exit to enable cron
exit;

require_once('includes/config/init_common.php');
require_once('classes/Order.php');


//Remove orders that was not paid for the given time.
//Order::delete_expired(handling_id, ttl_in_minutes);

Order::delete_expired(33,60);
?>