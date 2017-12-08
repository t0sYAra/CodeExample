<?php
namespace AntonPavlov\PersonalSite\Controllers;

use AntonPavlov\PersonalSite\Base\Registry;
use AntonPavlov\PersonalSite\Base\Controller;

/**
 * Контроллер, обрабатывающий запрос на показ страниц 403 или 404
 *
 * @package AntonPavlov\PersonalSite
 *
 * @author Anton Pavlov <mail@antonpavlov.ru>
 *
 */
class ErrorController extends Controller
{
    
    /**
     * Перезаписывает конструктор предка
     *
     * @return void
     */
    protected function init()
    {
        // заменяем метод предка (Controller->init()), чтобы не инициализировать зря реестр, БД и т.п.
        
    }

    /**
     * Выводит страницу 403
     *
     * @return void
     */
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

    /**
     * Выводит страницу 404
     *
     * @return void
     */
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

    /**
     * Выводит страницу 404 для картинки
     *
     * @return void
     */
    public function showPicPage404()
    {
        header('HTTP/1.1 404 Not Found');
		header('Status: 404 Not Found');
        die;
    }

    /**
     * Выводит страницу 404 для аудио
     *
     * @return void
     */
    public function showAudPage404()
    {
        header('HTTP/1.1 404 Not Found');
		header('Status: 404 Not Found');
        die;
    }
   
}
