<?php
	session_start();
	@session_save_path("./reseller");  //specify path where you want to save the session.
	require("functions.php");	//file which has required functions
?>	 	
		
<html>
<head><title>Payment Page </title>
<script language="JavaScript">
        function successClicked()
        {
            document.paymentpage.submit();
        }
        function failClicked()
        {
            document.paymentpage.status.value = "N";
            document.paymentpage.submit();
        }
        function pendingClicked()
        {
            document.paymentpage.status.value = "P";
            document.paymentpage.submit();
        }
</script>
</head>
<BODY onLoad="document.paymentpage.submit()">

<?php
		
		$key = "2nquOsQnzO3lzqwWfXEgmU7ZD4tAjtzP"; //replace ur 32 bit secure key , Get your secure key from your Reseller Control panel
		
		
		//Below are the  parameters which will be passed from foundation as http GET request

		$paymentTypeId = $HTTP_GET_VARS["paymenttypeid"];  //payment type id
		$transId = $HTTP_GET_VARS["transid"];			   //This refers to a unique transaction ID which we generate for each transaction
		$userId = $HTTP_GET_VARS["userid"];               //userid of the user who is trying to make the payment
		$userType = $HTTP_GET_VARS["usertype"];  		   //This refers to the type of user perofrming this transaction. The possible values are "Customer" or "Reseller"
		$transactionType = $HTTP_GET_VARS["transactiontype"];  //Type of transaction (ResellerAddFund/CustomerAddFund/ResellerPayment/CustomerPayment)

		$invoiceIds = $HTTP_GET_VARS["invoiceids"];		   //comma separated Invoice Ids, This will have a value only if the transactiontype is "ResellerPayment" or "CustomerPayment"
		$debitNoteIds = $HTTP_GET_VARS["debitnoteids"];	   //comma separated DebitNotes Ids, This will have a value only if the transactiontype is "ResellerPayment" or "CustomerPayment"

		$description = $HTTP_GET_VARS["description"];
		
		$sellingCurrencyAmount = $HTTP_GET_VARS["sellingcurrencyamount"]; //This refers to the amount of transaction in your Selling Currency
        $accountingCurrencyAmount = $HTTP_GET_VARS["accountingcurrencyamount"]; //This refers to the amount of transaction in your Accounting Currency

		$redirectUrl = $HTTP_GET_VARS["redirecturl"];  //This is the URL on our server, to which you need to send the user once you have finished charging him
						
		$checksum = $HTTP_GET_VARS["checksum"];	 //checksum for validation

		$city = $HTTP_GET_VARS["city"];	 //checksum for validation
		$state = $HTTP_GET_VARS["state"];	 //checksum for validation
		$zip = $HTTP_GET_VARS["zip"];	 //checksum for validation
		$country = $HTTP_GET_VARS["country"];	 //checksum for validation
		$emailAddr = $HTTP_GET_VARS["emailAddr"];	 //checksum for validation
		$name = $HTTP_GET_VARS["name"];	 //checksum for validation
		$phone = $HTTP_GET_VARS["telNoCc"] . $HTTP_GET_VARS["telNo"];	 //checksum for validation
		$address = $HTTP_GET_VARS["address1"] . ',' . $HTTP_GET_VARS["address2"] . ',' . $HTTP_GET_VARS["address3"];	 //checksum for validation

		if(verifyChecksum($paymentTypeId, $transId, $userId, $userType, $transactionType, $invoiceIds, $debitNoteIds, $description, $sellingCurrencyAmount, $accountingCurrencyAmount, $key, $checksum))
		{
			//YOUR CODE GOES HERE			

		/** 
		* since all these data has to be passed back to foundation after making the payment you need to save these data
		*	
		* You can make a database entry with all the required details which has been passed from foundation.  
		*
		*							OR
		*	
		* keep the data to the session which will be available in postpayment.php as we have done here.
		*
		* It is recommended that you make database entry.
		**/

			

			
			$_SESSION['redirecturl']=$redirectUrl;
			$_SESSION['transid']=$transId;
			$_SESSION['sellingcurrencyamount']=$sellingCurrencyAmount;
			$_SESSION['accountingcurencyamount']=$accountingCurrencyAmount;


			
?>

<form name="paymentpage" action="https://secure.ebs.in/pg/ma/sale/pay/" method="post" >
    <input type="hidden" name="status" value="Y">
	
	<input name="account_id" type="hidden" value="5600" /> <!-- Your EBS Account ID  -->
	<input name="reference_no" type="hidden" value="<?php echo $transId; ?>" />
	<input name="amount" type="hidden" value="<?php echo $accountingCurrencyAmount; ?>" />
	<input name="description" type="hidden" value= "<?php echo $description; ?>" />
	<input name="name" type="hidden" value="<?php echo $name; ?>" />
	<input name="address" type="hidden" value="<?php echo $address; ?>" />
	<input name="city" type="hidden" value="<?php echo $city; ?>" />
	<input name="state" type="hidden" value="<?php echo $state; ?>" />
	<input name="postal_code" type="hidden" value="<?php echo $zip; ?>" />
	<input name="country" type="hidden" value="<?php echo $country; ?>" />
	<input name="email" type="hidden" value="<?php echo $emailAddr; ?>" />
	<input name="phone" type="hidden" value="<?php echo $phone; ?>" />
	<input name="return_url" type="hidden" size="60" value="http://www.yourdomain.com/response.php?DR={DR}" /> <!-- The return URL after payment. This should not be confused with Redirect URL. Replace www.yourdomain.com with the link to response.php file.   -->
	<input name="mode" type="hidden" value="TEST" /> <!--- After testing done. Raise a ticket at https://support.ebs.in for making your account LIVE. And Replace the value="TEST" with value="LIVE" --->	
	
	
</form>

<?php

		}
		else
		{
			/**This message will be dispayed in any of the following case
			*
			* 1. You are not using a valid 32 bit secure key from your Reseller Control panel
			* 2. The data passed from foundation has been tampered.
			*
			* In both these cases the customer has to be shown error message and shound not
			* be allowed to proceed  and do the payment.
			*
			**/

			echo "Checksum mismatch !";			

		}
?>
</body>
</html>
