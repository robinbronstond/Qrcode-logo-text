<?php
/*
*
* PHP QR Code porting with logo and text
*
* @package        	CodeIgniter
* @subpackage    	Libraries
* @category    	Libraries
* 
* @version		1.0
*
*
* data - Qrcode data
*
* IsShowQRLogo - 1 = show logo in Qrcode, 0 = don't show logo in Qrcode
*
* QRLogoPath - Add logo in Qrcode
*
* BGColor - Qrcode Background color (RGB)
*
* FGColor - Qrcode Foreground color (RGB)
*
* level
* L - smallest
* M - medium
* Q - quality
* H - best
*
*
* addText - add text in Qrcode
*
* addTextPosition - text positon in Qrcode
* - TOP
* - BOTTOM
* - CENTER
*
* Size
* 1 - 10
*
*
*/
require_once './assets/js/Ciqrcode.php';

$image_name = "QrCode-image.png";
$params['data'] = "Welcome to code world";
$params['savename'] = "assets/images/".$image_name;
$params['addText'] = " WELCOME ";
$params['addTextPosition'] = "TOP";

$params['IsShowQRLogo'] = 1;
$params['QRLogoPath'] 	= 'assets/images/winner_cup.png';
$params['BGColor'][0] 	= 255;
$params['BGColor'][1] 	= 255;
$params['BGColor'][2] 	= 255;
$params['FGColor'][0] 	= 0;
$params['FGColor'][1] 	= 0;
$params['FGColor'][2] 	= 0;
$params['level'] 		= "H";
$params['size'] 		= 10;

$qrcode = new Ciqrcode();
$returnMsg = $qrcode->generate($params);

if($returnMsg!='')
{
	echo "<div>QrCode created successfully in this path ".$returnMsg."</div>";
}
?>
