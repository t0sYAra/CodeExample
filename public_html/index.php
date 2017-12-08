<?php
/**
 * Мой личный сайт, входная страница
 *
 * Скрипт настраивает и включает автозагрузчик классов
 * проверяет строку запроса
 * и с помощью маршрутизатора определяет, какой контроллёр будет обрабатывать запрос
 *
 * @author Anton Pavlov <mail@antonpavlov.ru>
 *
 * @package antonpavlov\personalsite
 */
namespace AntonPavlov\PersonalSite;

use AntonPavlov\PersonalSite\Base\Route;
use AntonPavlov\PersonalSite\Controllers\ErrorController;
use AntonPavlov\PersonalSite\Exceptions\Page403Exception;
use AntonPavlov\PersonalSite\Exceptions\Page404Exception;

/**
 * автозагрузчик классов
 *
 * автоматически загружает классы, если встречается 
 * их имя во время выполнения скрипта
 *
 * @param string $name полное имя класса с учётом пространства имён
 * @return void
 */
function autoLoader($name)
{
    $name = preg_replace('/^AntonPavlov\\\\PersonalSite\\\\(.*)/siu', "$1", $name);
    $name = preg_replace('/\\\\/siu', "/", $name);
    $name = preg_replace('/(\/)+/siu', "/", $name);
    require_once '../'.$name.'.php';
}

spl_autoload_extensions('.php');
spl_autoload_register('AntonPavlov\PersonalSite\autoLoader');

try {
    Route::validateRequest();
} catch (Page403Exception $e) {
    (new ErrorController())->showPage403();
}

try {
    Route::start();
} catch (Page404Exception $e) {
    (new ErrorController())->showPage404();
}


