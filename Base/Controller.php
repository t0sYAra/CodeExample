<?php
namespace AntonPavlov\PersonalSite\Base;

class Controller
{
	protected $model;
	protected $view;
    private $pathStart;
	
	function __construct()
	{
        session_start();
        $this->init();
        $this->view = new View();
	}

    private function initialInputCleaning(&$el)
    {
        if (is_array($el)) {
            foreach($el as $k => $v) {
                initialInputCleaning($el[$k]); 
            }
        } else {
            $el = stripslashes($el);
        }
    }
    
    protected function init()
    {
        // очищаем входные данные
        if (get_magic_quotes_gpc()) {
			initialInputCleaning($_GET);
			initialInputCleaning($_POST);
			initialInputCleaning($_COOKIE); 
			initialInputCleaning($_REQUEST);
            initialInputCleaning($_SERVER);
		}
        
        // включаем реестр
        $reg = Registry::init();
        
        // путь к ресурсам из папки public_html
        $this->pathStart = $this->pathToResources();
        $reg->set('pathStart',$this->pathStart);
        
        
    }
    
    public function pathToResources()
    {
        $pathStart = '';
        
        // удаляем концевые пробелы и слеши
        $path = trim($_SERVER['REQUEST_URI']," \t\n\r\0\x0B\\\/");
        
        // считаем уровень вложенности и соответственно добавляем в путь "../"
        $backs = count(explode('/',$path));
        for ($i = 0; $i < $backs; $i++) {
            $pathStart .= '../';
        }

        return $pathStart;
    }
    
}