<?php
namespace AntonPavlov\PersonalSite;

$cvet='243243243';

$cvet1=substr($cvet,0,3);
$cvet2=substr($cvet,3,3);
$cvet3=substr($cvet,6,3);


$height=30;
$width=74;
$im=ImageCreate($width,$height);
$fon=ImageColorAllocate($im,$cvet1,$cvet2,$cvet3);
$textcolor=ImageColorAllocate($im,153,153,153);

$kod='';
for ($i=0;$i<5;$i++)
{
	$cifra=rand(2,9);
	$kod=$kod.$cifra;
	ImageTTFText($im,25,rand(-1,1)*7,2+$i*13,$height-3,$textcolor,'img/arial.ttf',$cifra);
}

ImageFill($im,0,0,$fon);

$randx3=rand(4,7);
$randy3=rand(4,26);
$randx4=rand(67,70);
$randy4=rand(11,19);

for ($i=0;$i<5;$i++)
{
	$x=rand(0,$width-30);
	$y=rand(0,$height);
	$xadd=rand(30,50);
	$yadd=rand(-10,10);
	ImageLine($im,$x,$y,$x+$xadd,$y+$yadd,$textcolor);
	ImageLine($im,$x+1,$y,$x+$xadd+1,$y+$yadd,$textcolor);
}

ImageLine($im,$randx3,$randy3,$randx4,$randy4,$textcolor);
ImageLine($im,$randx3,$randy3+1,$randx4,$randy4+1,$textcolor);
ImageLine($im,$randx3+1,$randy3,$randx4+1,$randy4,$textcolor);

session_start();
if (isset($_SESSION['icndhcak'])) {unset($_SESSION['icndhcak']);}
$_SESSION['icndhcak']=$kod;

Header('Content-type: image/png');
Header("Pragma: no-cache");
Header("Cache-control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
Header("Expires: Mon, 01 Jan 2007 01:01:01 GMT");
Header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT");
ImagePng($im);
ImageDestroy($im);

?>
