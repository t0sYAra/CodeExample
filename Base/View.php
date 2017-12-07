<?
namespace AntonPavlov\PersonalSite\Base;

class View
{
	
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

        // подключаем файл шаблона (а он уже внутри себя подключит контент и т.п.)
		include_once __DIR__.'/../views/'.$templateViewFile;
	}

}