<?php
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
if (!$msg) $msg = "Le site du spipu\r\nhttp://spipu.net/";

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>Spipu Qrcode</title>
		<meta name="Title"			content="Spipu - codebar 2D" > 
		<meta name="Description"	content="Spipu - codebar 2D" >
		<meta name="Keywords"		content="spipu">
		<meta name="Author"			content="spipu" >
		<meta name="Reply-to"		content="webmaster@spipu.net" >  
		<meta name="Copyright"		content="(c)2009 Spipu" >
		<meta http-equiv="Content-Type"	content="text/html; charset=windows-1252" >
		<style type="text/css">
<!--
table.qr
{
	border-collapse: collapse;
	border: solid 1px black;
	table-layout: fixed;
}

table.qr td
{
	width: 5px;
	height: 5px;
	font-size: 2px;
}

table.qr td.on
{
	background: #000000;
}
-->
		</style>	
	</head>
	<body>
		<center>
			<form method="GET" action="">
				<textarea name="msg" cols="40" rows="7"><?php echo htmlentities($msg); ?></textarea><br>
				<input type="submit" value="Afficher">
			</form>
			<hr>
<?php
	require_once('qrcode.class.php');
	
	$qrcode = new QRcode($msg, 'H');
	$qrcode->displayHTML();
?>
		</center>
	</body>
</html>