<?php
namespace AntonPavlov\PersonalSite\Controllers;

use AntonPavlov\PersonalSite\Base\Registry;
use AntonPavlov\PersonalSite\Base\Controller;

class ErrorController extends Controller
{
    protected function init()
    {
        // заменяем метод предка (Controller->init()), чтобы не инициализировать зря реестр, БД и т.п.
        
    }

    public static function showPage403()
    {
        echo '403';
        die;
        header('HTTP/1.1 403 Frobidden');
		header('Status: 403 Frobidden');
        die;
    }
    
       
    public static function showPage404()
    {
        echo '404';
        die;
        header('HTTP/1.1 404 Not Found');
		header('Status: 404 Not Found');
        die;
    }
    
}
