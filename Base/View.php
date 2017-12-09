<?php
namespace AntonPavlov\PersonalSite\Base;

use AntonPavlov\PersonalSite\Base\Authorizer;

/**
 * Класс представления
 *
 * Подключает фалйы js, файлы, которые должны асинхронного грузиться заранее.
 * Загружает основной шаблон html-файла
 * и передаёт в него разные переменные
 *
 * @package AntonPavlov\PersonalSite
 *
 * @author Anton Pavlov <mail@antonpavlov.ru>
 *
 */
class View
{
	
    /**
     * Подключает html-шаблон
     *
     * Подключает фалйы js, файлы, которые должны асинхронного грузиться заранее.
     * Загружает основной шаблон html-файла
     * и передаёт в него разные переменные
     *
     * @param string $contentViewFile имя файла-шаблона для основного содержания страницы
     * @param string $templateViewFile имя файла-шаблона, содержащего header, footer  и т.п.
     * @param string $jsFiles список подключаемых js-файлов (через запятую, без пробела)
     * @param string $prefetchFiles список подключаемых больших файлов, требующих предварительной асинхронной загрузки (через запятую, без пробела)
     * @param array $data массив с дополнительными переменными, по умолчанию отсутствует
     *
     * @see Registry::get()
     *
     * @return void
     */
	public function includeViewFile($contentViewFile, $templateViewFile, $jsFiles, $prefetchFiles, $data = null)
	{
        // преобразуем элементы массива в переменные
		if(is_array($data)) {
			extract($data);
		}
        
        $reg = Registry::init();
        $pathStart = $reg->get('pathStart');
        
        $page = $_SERVER['REQUEST_URI']; // адрес страницы
        
        // подключаем основной и дополнительные файлы javascript
        $jsArr=explode(',',$jsFiles);
		$jsFiles='<script async src="'.$pathStart.'js/all.js" charset="UTF-8"></script>'.PHP_EOL;
		for ($i=0;$i<count($jsArr);$i++) {
			if ($jsArr[$i]!='') {
                $jsFiles.='<script async src="'.$pathStart.'js/'.$jsArr[$i].'" charset="UTF-8"></script>'.PHP_EOL;
                }
		}
        
        // подключаем ресурсы, которые должны быть предварительно загружены
        $prefetchArr=explode(',',$prefetchFiles);
		$prefetchFiles='';
		for ($i=0;$i<count($prefetchArr);$i++) {
			if ($prefetchArr[$i]!='') {
                $prefetchFiles.='<link rel="prefetch" href="'.$pathStart.'img/'.$prefetchArr[$i].'">'.PHP_EOL;
                }
		}
        
        $authorized = Authorizer::getStatus();

        // подключаем файл шаблона (а он уже внутри себя подключит контент и т.п.)
		include_once __DIR__.'/../views/'.$templateViewFile;
	}

}