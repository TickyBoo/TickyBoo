<?php
	 $secret_key = "1a70c1d07dd41fc43c4225507b689a0d";	 // Your EBS Secret Key
if(isset($_GET['DR'])) {
	 require('Rc43.php');
	 $DR = preg_replace("/\s/","+",$_GET['DR']);

	 $rc4 = new Crypt_RC4($secret_key);
 	 $QueryString = base64_decode($DR);
	 $rc4->decrypt($QueryString);
	 $QueryString = split('&',$QueryString);

	 $response = array();
	 foreach($QueryString as $param){
	 	$param = split('=',$param);
		$response[$param[0]] = urldecode($param[1]);
	 }
}

if($response['ResponseCode'] == 0) {
    $status = 'Y';
} else {
    $status = 'N';
}

$transid = $response['MerchantRefNo'];
$amount = $response['Amount'];
$rkey = $response['PaymentID'];

?>
<HTML>
<HEAD>
<TITLE>E-Billing Solutions Pvt Ltd - Payment Page</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<style>
	h1       { font-family:Arial,sans-serif; font-size:24pt; color:#08185A; font-weight:100; margin-bottom:0.1em}
    h2.co    { font-family:Arial,sans-serif; font-size:24pt; color:#FFFFFF; margin-top:0.1em; margin-bottom:0.1em; font-weight:100}
    h3.co    { font-family:Arial,sans-serif; font-size:16pt; color:#000000; margin-top:0.1em; margin-bottom:0.1em; font-weight:100}
    h3       { font-family:Arial,sans-serif; font-size:16pt; color:#08185A; margin-top:0.1em; margin-bottom:0.1em; font-weight:100}
    body     { font-family:Verdana,Arial,sans-serif; font-size:11px; color:#08185A;}
	th 		 { font-size:12px;background:#015289;color:#FFFFFF;font-weight:bold;height:30px;}
	td 		 { font-size:12px;background:#DDE8F3}
	.pageTitle { font-size:24px;}
</style>
</HEAD>
<BODY onLoad="document.responsepage.submit()">
<form name="responsepage" action="postpayment.php">
    <input type="hidden" name="status" value="<?php echo $status; ?>">	
	<input name="transid" type="hidden" value="<?php echo $transid; ?>" />
	<input name="sellingcurrencyamount" type="hidden" value="<?php echo $amount; ?>" />
	<input name="accountingcurrencyamount" type="hidden" value= "<?php echo $amount; ?>" />
</form>		
</body>
</html>