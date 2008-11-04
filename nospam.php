<?php
// het random nr. aanmaken en gecodeerd opslaan in php sessie

session_start();

$randomnr = '';

// captcha plaatje met nummer maken - afmetingen kun je aanpassen gebruikte font

$im = imagecreatetruecolor(100, 46);

// Kleurenbepaling

$grey = imagecolorallocate($im, 198, 198, 198);
$black = imagecolorallocate($im, 0, 0, 0);

// zwarte rechthoek tekenen - afmetingen kun je aanpassen aan verschillende fonts

imagefilledrectangle($im, 0, 0, 100, 46, imagecolorallocate($im, rand(120,255), rand(120,255), rand(120,255)));

 for ($i = 1; $i < 10; $i++) {
    imagefilledrectangle($im,rand(0,50),rand(0,23),rand(50,100),rand(23,46),imagecolorallocate($im,mt_rand(120,255),mt_rand(120,255),mt_rand(120,255)));
    imagefilledellipse($im,rand(0,100),rand(0,60),rand(25,50),rand(25,50),imagecolorallocate($im,mt_rand(120,255),mt_rand(120,255),mt_rand(120,255)));
 }
// hier - font.ttf' vervangen met de locatie van je eigen font bestand
$font = 'includes/fonts/Gibberish.ttf';
$text = '23456789ABCEFGHJKNPRST';
// schaduw toevoegen
 for ($i = 0; $i < 5; $i++) {
   $char = substr($text,rand(0,strlen($text)-1),1);
   $randomnr .= $char;
   $angle = rand(-25,25);
   $y = rand(-10,7);
   $white = imagecolorallocate($im, mt_rand(010,120), mt_rand(010,120), mt_rand(010,120));
   imagettftext($im, 16, $angle, 7+($i*19), 34+$y, $grey, $font, $char);
   imagettftext($im, 16, $angle, 5+($i*19), 36+$y, $white, $font, $char);
 }

// voorkomen dat afbeelding ge-cached wordt
  for ($i = 0; $i < 2500; $i++) {
  	$color_pixel  = imagecolorallocatealpha ($im, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255),64);
		ImageSetPixel($im, rand(0, 100), rand(0, 46), $color_pixel);
 }



$_SESSION['randomnr2'] = md5($randomnr);

header("Expires: Wed, 1 Jan 1997 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// plaatje verzenden naar browser

header ("Content-type: image/gif");
imagegif($im);
imagedestroy($im);
?>