<?
namespace AntonPavlov\PersonalSite\Base;

class Route
{
    public static function validateRequest()
    {
        // по умолчанию - всё ок
        $requestOK = true;

        // здесь будут правила валидации
        
        // выкидываем пользователя, если нам что-то не понравилось
        if (!$requestOK) {
            Route::showPage403();
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
            Route::showPage404();
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
            Route::showPage404();
        }
        
		// создаем экземпляр класса контроллера
        $controller = 'AntonPavlov\PersonalSite\Controllers\\'.$controller;
		$controllerObj = new $controller;

		if(method_exists($controllerObj, $action)) {

            // инициализируем реестр
            $reg = Registry::init();
            //$reg->set('surname','pavlov');
            
			// вызываем метод контроллера
			$controllerObj->$action();
		}
		else {
			Route::showPage404();
		}
        
    }
    
    public static function showPage403()
    {
        header('HTTP/1.1 403 Frobidden');
		header('Status: 403 Frobidden');
        die;
    }
    
       
    public static function showPage404()
    {
        header('HTTP/1.1 404 Not Found');
		header('Status: 404 Not Found');
        die;
    }
    
}