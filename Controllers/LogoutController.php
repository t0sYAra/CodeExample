<?php
namespace AntonPavlov\PersonalSite\Controllers;

use AntonPavlov\PersonalSite\Base\Controller;
use AntonPavlov\PersonalSite\Base\Authorizer;

/**
 * Контроллер, разлогинивающий пользователя
 *
 * @package AntonPavlov\PersonalSite
 *
 * @author Anton Pavlov <mail@antonpavlov.ru>
 *
 */
class LogoutController extends Controller
{

    /**
     * Разлогинивает пользователя и редиректит на главную
     *
     * @return void
     */
    public function logout()
    {
        Authorizer::logout();
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: http://'.$_SERVER['SERVER_NAME'].'/');
        die;
    }

}
