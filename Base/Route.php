<?
namespace AntonPavlov\PersonalSite\Base;

use AntonPavlov\PersonalSite\Exceptions\Page403Exception;
use AntonPavlov\PersonalSite\Exceptions\Page404Exception;

class Route
{
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
    
    private static function applyRules($rules)
    {
        // по умолчанию
        $error = '404';
        $controller = '';
        $model = '';
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
    
    public static function start()
    {
        $rules = RouteRules::getRules();
        $redirectRules = RouteRules::redirectRules();
        
        Route::applyRedirectRules($redirectRules);
        
        list($controller, $action) = Route::applyRules($rules);
        
        // подключаем файл контроллера
        $controllerFile = '../controllers/'.$controller.'.php';
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