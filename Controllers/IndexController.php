<?php
namespace AntonPavlov\PersonalSite\Controllers;

use AntonPavlov\PersonalSite\Base\Controller;
use AntonPavlov\PersonalSite\Base\RandomHelper;

/**
 * Контроллер, обрабатывающий запрос на загрузку главной страницы сайта
 *
 * @package AntonPavlov\PersonalSite
 *
 * @author Anton Pavlov <mail@antonpavlov.ru>
 *
 */
class IndexController extends Controller
{

    /**
     * Выводит главную страницу сайта
     *
     * @return void
     */
	function indexAction()
	{
		$this->view->includeViewFile
        (
            'index.php', // файл с контентом
            'mainTemplate.php', // шаблон
            '', // js-files
            'predprinimatel1.jpg,stroitel1.jpg,pilot1.jpg,trener1.jpg,spasatel1.jpg,rassada1.jpg,krovat1.jpg,tszh1.jpg,kandidat1.jpg', // prefetch
            [
                'title' => 'Антон Павлов - личный сайт', // здесь и далее - дополнительные данные
                'description' => 'Главная страница сайта',
                'mainImg' => 'antonpavlov'.RandomHelper::getRandomNum(0,2).'.jpg'
            ]
        );
	}
}
