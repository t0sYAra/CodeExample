<?php
namespace AntonPavlov\PersonalSite;

use AntonPavlov\PersonalSite\Base\Route;
use AntonPavlov\PersonalSite\Controllers\ErrorController;
use AntonPavlov\PersonalSite\Exceptions\Page403Exception;
use AntonPavlov\PersonalSite\Exceptions\Page404Exception;

function autoLoader($name)
{
    $name = preg_replace('/^AntonPavlov\\\\PersonalSite\\\\(.*)/siu', "$1", $name);
    require_once '../'.$name.'.php';
}

spl_autoload_extensions('.php');
spl_autoload_register('AntonPavlov\PersonalSite\autoLoader');

try {
    Route::validateRequest();
} catch (Page403Exception $e) {
    ErrorController::showPage403();
}

try {
    Route::start();
} catch (Page404Exception $e) {
    ErrorController::showPage404();
}


