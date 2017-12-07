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

    public function showPage403()
    {
        header('HTTP/1.1 403 Frobidden');
		header('Status: 403 Frobidden');
        $this->view->includeViewFile
        (
            'error403.php', // файл с контентом
            'mainTemplate.php', // шаблон
            '', // js-files
            '', // prefetch
            [
                'title' => 'Доступ запрещён', // здесь и далее - дополнительные данные
                'description' => 'Доступ запрещён',
            ]
        );
        die;
    }

    public function showPage404()
    {
        header('HTTP/1.1 404 Not Found');
		header('Status: 404 Not Found');
        $this->view->includeViewFile
        (
            'error404.php', // файл с контентом
            'mainTemplate.php', // шаблон
            '', // js-files
            '', // prefetch
            [
                'title' => 'Не найдено', // здесь и далее - дополнительные данные
                'description' => 'Запрашиваемая страница не найдена',
            ]
        );
        die;
    }

    public function showPicPage404()
    {
        header('HTTP/1.1 404 Not Found');
		header('Status: 404 Not Found');
        die;
    }

    public function showAudPage404()
    {
        header('HTTP/1.1 404 Not Found');
		header('Status: 404 Not Found');
        die;
    }
   
}
