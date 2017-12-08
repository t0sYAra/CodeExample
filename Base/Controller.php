<?php
namespace AntonPavlov\PersonalSite\Base;

/**
 * Класс-предок для всех контроллеров
 *
 * Класс-предок для всех котроллеров. Во время создания экземпляра класса стартует сессии,
 * очищает данные от слешей во входных данных глобальных массивов $_SERVER (и т.п.),
 * инициализирует реестр для хранения данных,
 * определяет путь к ресурсам типа img, css, js и т.п.,
 * создаёт объект класса View
 *
 * @package AntonPavlov\PersonalSite
 *
 * @author Anton Pavlov <mail@antonpavlov.ru>
 *
 */
class Controller
{
    /**
     * @var object $view экземпляр класса View
     */
	protected $view;
    
    /**
     * @var string $pathStart путь к ресурсам типа img, css, js и т.п.
     */
    private $pathStart;

    /**
     * Конструктор класса
     *
     * Стартует сессию, выполняет инициализацию,
     * создаёт экземпляр класса View
     *
     * @return void
     */
	function __construct()
	{
        session_start();
        $this->init();
        $this->view = new View();
	}

    /**
     * Чистит слеши
     *
     * Рекурсивно чистит слеши у значений глобальных массивов
     *
     * @param mixed $el переменная или массив, которую нужно очистить
     *
     * @return void
     */
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
    
    /**
     * Выполняет инициализацию
     *
     * Выполняет инициализацию: чистит слеши, включает реестр, определяет путь к ресурсам.
     * Может быть перезаписан в дочернем классе (например, если не требуется какой-то этап инициализации)
     *
     * @return void
     */
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
        
    /**
     * Определяет путь к ресурсам
     *
     * Определяет путь к ресурсам типа img, css, js и т.п. исходя из строки запроса
     *
     * @return string
     */
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