<?php 
	 session_start();
	 session_save_path("./reseller"); //path on your server where you are storing session


	//file which has required functions
	require("functions.php");	
 ?>
<html>
<head><title>Post Payment</title></head>
<body onLoad="document.f1.submit()">
<font size=4>

<?php
		$key = "2nquOsQnzO3lzqwWfXEgmU7ZD4tAjtzP"; //replace ur 32 bit secure key , Get your secure key from your Reseller Control panel
	    

		$redirectUrl = $_SESSION['redirecturl'];  // redirectUrl received from foundation
		$transId = $_SESSION['transid'];		 //Pass the same transid which was passsed to your Gateway URL at the beginning of the transaction.
		$sellingCurrencyAmount = $_SESSION['sellingcurrencyamount'];
		$accountingCurrencyAmount = $_SESSION['accountingcurencyamount'];


		$status = $_REQUEST["status"];	 // Transaction status received from your Payment Gateway
        //This can be either 'Y' or 'N'. A 'Y' signifies that the Transaction went through SUCCESSFULLY and that the amount has been collected.
        //An 'N' on the other hand, signifies that the Transaction FAILED.

		/**HERE YOU HAVE TO VERIFY THAT THE STATUS PASSED FROM YOUR PAYMENT GATEWAY IS VALID.
	    * And it has not been tampered with. The data has not been changed since it can * easily be done with HTTP request. 
		*
		**/
		
		srand((double)microtime()*1000000);
		$rkey = rand();


		$checksum =generateChecksum($transId,$sellingCurrencyAmount,$accountingCurrencyAmount,$status, $rkey,$key);


?>
		<form name="f1" action="<?php echo $redirectUrl;?>">
			<input type="hidden" name="transid" value="<?php echo $transId;?>">
		    <input type="hidden" name="status" value="<?php echo $status;?>">
			<input type="hidden" name="rkey" value="<?php echo $rkey;?>">
		    <input type="hidden" name="checksum" value="<?php echo $checksum;?>">
		    <input type="hidden" name="sellingamount" value="<?php echo $sellingCurrencyAmount;?>">
			<input type="hidden" name="accountingamount" value="<?php echo $accountingCurrencyAmount;?>">

		</form>
</font>
</body>
</html>