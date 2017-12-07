<?php
namespace AntonPavlov\PersonalSite\Controllers;

use AntonPavlov\PersonalSite\Controllers\ErrorController;
use AntonPavlov\PersonalSite\Base\Controller;
use AntonPavlov\PersonalSite\Models\PictureModel;
use AntonPavlov\PersonalSite\Models\EntryModel;

class CaptchaController extends Controller
{
    
    function generateCaptcha()
    {
        session_start();
        if (isset($_SESSION['icndhcak'])) {
            unset($_SESSION['icndhcak']);
        }
        $_SESSION['icndhcak'] = random_int(10000, 99999);

        $cvet = '243243243';
        
        $cvet1 = substr($cvet, 0, 3);
        $cvet2 = substr($cvet, 3, 3);
        $cvet3 = substr($cvet, 6, 3);
        
        $height = 30;
        $width = 74;
        $im = ImageCreate($width, $height);
        
        if (!$im) {
            (new ErrorController())->showPage404();
        }
        
        $fon = ImageColorAllocate($im, $cvet1, $cvet2, $cvet3);
        $textcolor = ImageColorAllocate($im, 153, 153, 153);
        
        $kod = '';
        for ($i = 0; $i < 5; $i++)
        {
            $cifra = random_int(2, 9);
            $kod = $kod.$cifra;
            ImageTTFText($im, 25, random_int(-1, 1)*7, 2 + $i * 13, $height - 3, $textcolor, 'img/arial.ttf', $cifra);
        }
        $_SESSION['icndhcak'] = $kod;

        ImageFill($im, 0, 0, $fon);
        
        $randx3 = random_int(4, 7);
        $randy3 = random_int(4, 26);
        $randx4 = random_int(67, 70);
        $randy4 = random_int(11, 19);
        
        for ($i = 0; $i < 5; $i++)
        {
            $x = random_int(0, $width - 30);
            $y = random_int(0, $height);
            $xadd = random_int(30, 50);
            $yadd = random_int(-10, 10);
            ImageLine($im, $x, $y, $x + $xadd, $y + $yadd, $textcolor);
            ImageLine($im, $x + 1, $y, $x + $xadd + 1, $y + $yadd, $textcolor);
        }
        
        ImageLine($im, $randx3, $randy3, $randx4, $randy4, $textcolor);
        ImageLine($im, $randx3, $randy3 + 1, $randx4, $randy4 + 1, $textcolor);
        ImageLine($im, $randx3 + 1, $randy3, $randx4 + 1, $randy4, $textcolor);
        
        Header('Content-type: image/png');
        Header("Pragma: no-cache");
        Header("Cache-control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check = 0, pre-check = 0", false);
        Header("Expires: Mon, 01 Jan 2007 01:01:01 GMT");
        Header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT");
        ImagePng($im);
        ImageDestroy($im);

    }

    
}
