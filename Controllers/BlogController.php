<?php
namespace AntonPavlov\PersonalSite\Controllers;

use AntonPavlov\PersonalSite\Controllers\ErrorController;
use AntonPavlov\PersonalSite\Base\Controller;
use AntonPavlov\PersonalSite\Models\BlogModel;
use AntonPavlov\PersonalSite\Base\Formatter;
use AntonPavlov\PersonalSite\Exceptions\SearchStringNotFoundException;

/**
 * Контроллер, обрабатывающий запрос на загрузку множества записей в блоге
 *
 * @package AntonPavlov\PersonalSite
 *
 * @author Anton Pavlov <mail@antonpavlov.ru>
 *
 */
class BlogController extends Controller
{

    /**
     * Выводит несколько записей из блога
     *
     * @return void
     */
	function showBlog()
	{
        // значения по умолчанию
        $title = 'Записи в блоге';
        $descr = 'Содержание записей в блоге';
        
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
        $errors = '';
        $errorType = 0;
        
        try {
            $blog = new BlogModel();
            $allEntries = $blog->getMain($begin,$end,$params);
            
            // форматируем текст
            $abzacLimit = 6;
            $symbolsLimit = 900;
            for ($i=0; $i < count($allEntries); $i++) {
                $allEntries[$i]['text'] = Formatter::clearPlainTags($allEntries[$i]['text']);
                $allEntries[$i]['text'] = Formatter::getFirstParagraphs($allEntries[$i]['translit'], $allEntries[$i]['text'], $abzacLimit, $symbolsLimit);
                $allEntries[$i]['text'] = Formatter::convertPlainTagsToHtmlTags('abzac1',$allEntries[$i]['text'],$allEntries[$i]['entryid']);
        
                $allEntries[$i]['text'] = preg_replace("/class=\"h2text\"/smiu","class=\"h2blog\"",$allEntries[$i]['text']);
                $allEntries[$i]['text'] = preg_replace("/\<div class=\"h2orangeline\"\>\<\/div\>/smiu","",$allEntries[$i]['text']);
            
                $allEntries[$i]['published'] = Date('j',strtotime($allEntries[$i]['published'])).' '.Formatter::getMonthInRussian(Date('m',strtotime($allEntries[$i]['published'])));
    
                // картинки
                try {
                    $firstPicture = $blog->getPicture($allEntries[$i]['entryid']);
                    $allEntries[$i]['picid'] = ($firstPicture['picid'] * 1).'';
                    $allEntries[$i]['pictype'] = $firstPicture['type'];
                } catch (\Exception $e) {
                    $allEntries[$i]['picid'] = '';
                    $allEntries[$i]['pictype'] = '';
                }
                
                // считаем комментарии
                $allEntries[$i]['commentsAmmount'] = '';
                try {
                    $comments = $blog->getCommentsAmmount($allEntries[$i]['entryid']);
                    if ($comments['commentsAmmount'] > 0) {
                        $allEntries[$i]['commentsAmmount'] = $comments['commentsAmmount'].' '.Formatter::defineWordEnding($comments['commentsAmmount'],'комментарий');
                    }
                } catch (\Exception $e) {
                    // что-то пошло не так при запросе к БД
                    // ну и ладно, просто не выводим на экран информацию
                    // о количестве комментариев
                }
                
            }
        
            $navField = Formatter::getNavField($params, $begin, $end, $allEntries[0]['kolstrok'], count($allEntries), $diff);
        } catch (SearchStringNotFoundException $e) {
            $errors = $e->getMessage().' Вы осуществляли поиск по запросу "'.$e->getSearchString().'"';
            $errorType = 1;
        } catch (\Exception $e) {
            $errors = $e->getMessage();
            $errorType = 2;
        }

		$this->view->includeViewFile
        (
            'blog.php', // файл с контентом
            'mainTemplate.php', // шаблон
            'comments.js,search.js', // js-files
            '', // prefetch
            [
                'title' => $title, // здесь и далее - дополнительные данные
                'description' => $descr,
                'allEntries' => $allEntries,
                'navField' => $navField,
                'errors' => $errors,
                'errorType' => $errorType
            ]
        );
	}

}
