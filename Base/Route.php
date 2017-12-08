<?php
namespace AntonPavlov\PersonalSite\Base;

use AntonPavlov\PersonalSite\Exceptions\Page403Exception;
use AntonPavlov\PersonalSite\Exceptions\Page404Exception;

/**
 * Класс маршрутизатора
 *
 * Проверяет валидность запроса,
 * анализирует правила маршрутизации.
 * Назначает контроллер, если найдено соответствующее ему правило
 *
 * @package AntonPavlov\PersonalSite
 *
 * @author Anton Pavlov <mail@antonpavlov.ru>
 *
 */
class Route
{
    /**
     * Метод проверяет строку запроса
     *
     * Если запрос содержит запрещённые шаблоны,
     * то выдаётся страница 403: forbidden
     *
     * @return void
     */
    public static function validateRequest()
    {
        $forbiddenRules = RouteRules::forbiddenRules();
        
        // получаем строку запроса
        $path = $_SERVER['REQUEST_URI'];

        for ($i = 0; $i < count ($forbiddenRules); $i++) {
            if (preg_match('/'.$forbiddenRules[$i].'/siu',$path) == 1) {
                throw new Page403Exception();
            }
        }

    }

    /**
     * Метод применяет правила редиректа
     *
     * Если запрос содержит шаблон редиректа,
     * то происходит редирект 301 на указанную в правилах страницу
     *
     * @param array $redirectRules массив с правилами редиректа
     * @return void
     */
    private static function applyRedirectRules($redirectRules)
    {
        // получаем строку запроса
        $path = $_SERVER['REQUEST_URI'];
        
        foreach ($redirectRules as $key => $value) {
            if (preg_match('/^'.$redirectRules[$key]['from'].'$/siu',$path) == 1) {
                header('HTTP/1.1 301 Moved Permanently');
                header('Location: http://'.$_SERVER['SERVER_NAME'].'/'.$redirectRules[$key]['to']);
                die;
            }
        }

    }
    
    /**
     * Метод применяет правила маршрутизации
     *
     * Если запрос содержит шаблон маршрутизации,
     * то вызывается контроллер и его метод, ответственный за обработку запроса
     *
     * @param array $rules массив с правилами маршрутизации
     * @return array
     */
    private static function applyRules($rules)
    {
        // по умолчанию
        $error = '404';
        $controller = '';
		$action = '';

        // получаем строку запроса
        $path = $_SERVER['REQUEST_URI'];

        foreach ($rules as $key => $value) {
            if (preg_match('/^'.$rules[$key]['regExp'].'$/siu',$path) == 1) {
                $controller = $rules[$key]['controller'];
                $action = $rules[$key]['action'];
                $includeFilePath = $rules[$key]['include'];
                $error = '200';
                if ((empty($controller))&&(empty($action))&&(!empty($includeFilePath))) {
                    include_once $includeFilePath;
                    die;
                }
                break;
            }
        }
        
        if ($error !== '200') {
            throw new Page404Exception();
        }

        $arr[]=$controller;
        $arr[]=$action;
        
        return $arr;
    }
    
    /**
     * Метод проверяет правила редиректа и маршрутизации
     *
     * Метод проверяет правила редиректа и маршрутизации и
     * либо перенаправляет на другую страницу,
     * либо вызывает метод нужного контроллера для обработки запроса,
     * либо выбрасывает исключение 404: not found
     *
     * @throws Page404Exception если найденой пары класс контроллера -> метод не существует
     *
     * @return void
     */
    public static function start()
    {
        $rules = RouteRules::getRules();
        $redirectRules = RouteRules::redirectRules();
        
        Route::applyRedirectRules($redirectRules);
        
        list($controller, $action) = Route::applyRules($rules);
        
        // подключаем файл контроллера
        $controllerFile = '../Controllers/'.$controller.'.php';
		if(!file_exists($controllerFile)) {
            throw new Page404Exception();
        }
        
		// создаем экземпляр класса контроллера
        $controller = 'AntonPavlov\PersonalSite\Controllers\\'.$controller;
		$controllerObj = new $controller;

		if(method_exists($controllerObj, $action)) {
			// вызываем метод контроллера
			$controllerObj->$action();
		}
		else {
			throw new Page404Exception();
		}
        
    }

}