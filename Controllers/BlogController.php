<?php
namespace AntonPavlov\PersonalSite\Controllers;

use AntonPavlov\PersonalSite\Base\Route;
use AntonPavlov\PersonalSite\Base\Registry;
use AntonPavlov\PersonalSite\Base\Controller;
use AntonPavlov\PersonalSite\Models\BlogModel;
use AntonPavlov\PersonalSite\Base\Formatter;
use AntonPavlov\PersonalSite\Base\View;

class BlogController extends Controller
{
    
	function showBlog()
	{
        // значения по умолчанию
        $title = 'Записи в блоге';
        $descr = 'Содержание записей в блоге';
        
        $viewObj = new View();
        $pathStart = $viewObj->pathToResources();
        
        // получаем параметры запроса
        $params['year'] = preg_replace("/^\/blog(?:\/(20[0-9]{2}))?(?:\/tags=([-_\s\%a-z0-9абвгдеёжзийклмнопрстуфхцчшщьыъэюя]+))?(?:\/(best))?(?:\/search=([-_\s\%a-z0-9абвгдеёжзийклмнопрстуфхцчшщьыъэюя]+))?(?:(?:\/?)|(?:\/([1-9][0-9]{0,8})-([1-9][0-9]{0,8})\/?)?)$/siu","$1",$_SERVER['REQUEST_URI']);
        $params['tags'] = preg_replace("/^\/blog(?:\/(20[0-9]{2}))?(?:\/tags=([-_\s\%a-z0-9абвгдеёжзийклмнопрстуфхцчшщьыъэюя]+))?(?:\/(best))?(?:\/search=([-_\s\%a-z0-9абвгдеёжзийклмнопрстуфхцчшщьыъэюя]+))?(?:(?:\/?)|(?:\/([1-9][0-9]{0,8})-([1-9][0-9]{0,8})\/?)?)$/siu","$2",$_SERVER['REQUEST_URI']);
        $params['sort'] = preg_replace("/^\/blog(?:\/(20[0-9]{2}))?(?:\/tags=([-_\s\%a-z0-9абвгдеёжзийклмнопрстуфхцчшщьыъэюя]+))?(?:\/(best))?(?:\/search=([-_\s\%a-z0-9абвгдеёжзийклмнопрстуфхцчшщьыъэюя]+))?(?:(?:\/?)|(?:\/([1-9][0-9]{0,8})-([1-9][0-9]{0,8})\/?)?)$/siu","$3",$_SERVER['REQUEST_URI']);
        $params['search'] = preg_replace("/^\/blog(?:\/(20[0-9]{2}))?(?:\/tags=([-_\s\%a-z0-9абвгдеёжзийклмнопрстуфхцчшщьыъэюя]+))?(?:\/(best))?(?:\/search=([-_\s\%a-z0-9абвгдеёжзийклмнопрстуфхцчшщьыъэюя]+))?(?:(?:\/?)|(?:\/([1-9][0-9]{0,8})-([1-9][0-9]{0,8})\/?)?)$/siu","$4",$_SERVER['REQUEST_URI']);
        $params['begin'] = preg_replace("/^\/blog(?:\/(20[0-9]{2}))?(?:\/tags=([-_\s\%a-z0-9абвгдеёжзийклмнопрстуфхцчшщьыъэюя]+))?(?:\/(best))?(?:\/search=([-_\s\%a-z0-9абвгдеёжзийклмнопрстуфхцчшщьыъэюя]+))?(?:(?:\/?)|(?:\/([1-9][0-9]{0,8})-([1-9][0-9]{0,8})\/?)?)$/siu","$5",$_SERVER['REQUEST_URI']);
        $params['end'] = preg_replace("/^\/blog(?:\/(20[0-9]{2}))?(?:\/tags=([-_\s\%a-z0-9абвгдеёжзийклмнопрстуфхцчшщьыъэюя]+))?(?:\/(best))?(?:\/search=([-_\s\%a-z0-9абвгдеёжзийклмнопрстуфхцчшщьыъэюя]+))?(?:(?:\/?)|(?:\/([1-9][0-9]{0,8})-([1-9][0-9]{0,8})\/?)?)$/siu","$6",$_SERVER['REQUEST_URI']);
        
        // отступы от начала записей в блоге
        $diff=15;
        $begin = 1;
        $end = $begin + $diff - 1;
        if (($params['begin'] != '')&&($params['end'] != '')) {
            $begin = $params['begin'];
            $end = $params['end'];
        }
        if ($end < $begin) {
            $begin = 1;
            $end = $begin + $diff - 1;
        }
        if ($end>$begin+100) {
            $end = $begin+100;
        }
        $diff = $end-$begin+1;

        // получаем записи
        $blog = new BlogModel();
        $allEntries = $blog->getMain($begin,$end,$params);

        if (!$allEntries) {
            // не найдена запись или ошибка во время запроса к БД
            Route::showPage404();
        }
                
        $abzacLimit = 6;
        $symbolsLimit = 900;
        for ($i=0; $i < count($allEntries); $i++) {
            $allEntries[$i]['text'] = Formatter::clearPlainTags($allEntries[$i]['text']);
            $allEntries[$i]['text'] = Formatter::getFirstParagraphs($allEntries[$i]['translit'], $allEntries[$i]['text'], $abzacLimit, $symbolsLimit);
            $allEntries[$i]['text'] = Formatter::convertPlainTagsToHtmlTags('abzac1',$allEntries[$i]['text'],$allEntries[$i]['entryid'],$pathStart);
	
            $allEntries[$i]['text'] = preg_replace("/class=\"h2text\"/smiu","class=\"h2blog\"",$allEntries[$i]['text']);
            $allEntries[$i]['text'] = preg_replace("/\<div class=\"h2orangeline\"\>\<\/div\>/smiu","",$allEntries[$i]['text']);
	    
            $allEntries[$i]['published'] = Date('j',strtotime($allEntries[$i]['published'])).' '.Formatter::getMonthInRussian(Date('m',strtotime($allEntries[$i]['published'])));

            $firstPicture = $blog->getPicture($allEntries[$i]['entryid']);
            if ($firstPicture) {
                $allEntries[$i]['picid'] = ($firstPicture['picid'] * 1).'';
                $allEntries[$i]['pictype'] = $firstPicture['type'];
            } else {
                $allEntries[$i]['picid'] = '';
                $allEntries[$i]['pictype'] = '';
            }
        }
        
        $navField = Formatter::getNavField($params, $begin, $end, $allEntries[0]['kolstrok'], count($allEntries), $diff, $pathStart);

		$this->view->includeViewFile
        (
            'blog.php', // файл с контентом
            'mainTemplate.php', // шаблон
            'comments.js', // js-files
            '', // prefetch
            [
                'title' => $title, // здесь и далее - дополнительные данные
                'description' => $descr,
                'allEntries' => $allEntries,
                'navField' => $navField
            ]
        );
	}

}
