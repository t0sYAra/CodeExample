<?php
namespace AntonPavlov\PersonalSite;

use AntonPavlov\PersonalSite\Base\Route;

function autoLoader($name)
{
    $name = preg_replace('/^AntonPavlov\\\\PersonalSite\\\\(.*)/siu', "$1", $name);
    require_once '../'.$name.'.php';
}

spl_autoload_extensions('.php');
spl_autoload_register('AntonPavlov\PersonalSite\autoLoader');

Route::validateRequest();
Route::start();
