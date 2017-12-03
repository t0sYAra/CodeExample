<?php
namespace AntonPavlov\PersonalSite\Controllers;

use AntonPavlov\PersonalSite\Base\Route;
use AntonPavlov\PersonalSite\Base\Controller;
use AntonPavlov\PersonalSite\Models\EntryModel;
use AntonPavlov\PersonalSite\Base\Formatter;
use AntonPavlov\PersonalSite\Base\View;

class EntryController extends Controller
{
    
	function showEntry()
	{
        // значения по умолчанию
        $title = 'Запись в блоге';
        $descr = 'Содержание записи в блоге';
        
        // получаем транслитерированное название из строки запроса
        $nameTranslitted = preg_replace("/^\/(menedzher_internet-proektov|stroitel|pilot|trener|spasatel|specialist_po_fito-svetu|mebelshchik|chlen_pravleniya_tszh|kandidat_ehkonomicheskih_nauk)\/?$/siu","$1",$_SERVER['REQUEST_URI']);
        if ($nameTranslitted === $_SERVER['REQUEST_URI']) {
            $nameTranslitted = preg_replace("/^\/blog\/([-_0-9a-zA-Z]{2,255})\/?$/siu","$1",$_SERVER['REQUEST_URI']);
        }
        
        // получаем теги из всех записей
        $entry = new EntryModel();
        $entryArr = $entry->getMain($nameTranslitted);
        
        if (!$entryArr) {
            // не найдена запись или ошибка во время запроса к БД
            Route::showPage404();
        }
        
        $entryArr = $this->cleanEntryParts($entryArr);
        $additionalJStext = $this->preparePictureArrForJS($entryArr['entryid'],$entryArr['text']);

        $published = Date('j',strtotime($entryArr['published'])).' '.Formatter::getMonthInRussian(Date('m',strtotime($entryArr['published'])));
        $title = $entryArr['zag'];
	
		$this->view->includeViewFile
        (
            'entry.php', // файл с контентом
            'mainTemplate.php', // шаблон
            'comments.js', // js-files
            '', // prefetch
            [
                'title' => $title, // здесь и далее - дополнительные данные
                'description' => $descr,
                'entryid' => $entryArr['entryid'],
                'zag' => $entryArr['zag'],
                'epigraf' => $entryArr['epigraf'],
                'text' => $entryArr['text'],
                'published' => $published,
                'additionalJStext' => $additionalJStext,
                'epigraf' => $entryArr['epigraf']
            ]
        );
	}

    function preparePictureArrForJS($entryId,$text)
    {
        // загружаем данные о всех картинках в массив
        preg_match_all("/pics\/[01abcd]\/[0-9]+\/([0-9]+).(?:jpg|gif|png)\" class=\"pictext\"/smiu",$text,$pictures,PREG_SET_ORDER);
        $additionalJStext = 'var pics = new Array(';
        for ($o = 0; $o < count($pictures); $o++) {
            $additionalJStext.=', "'.$pictures[$o][1].'"';
        }
        unset($pictures);
        $additionalJStext = '<script language="javascript" type="text/javascript" charset="UTF-8">
		'.substr($additionalJStext, 0, 21).substr($additionalJStext, 23).');
		var beforepic = 0;
		var arrindex = 0;
		var tekpic = pics[arrindex];
		var afterpic = pics[1];
		var entryid = '.$entryId.';
		</script>
        ';
        
        return $additionalJStext;
    }
    
    function cleanEntryParts($entryArr)
    {
        $viewObj = new View();
        $pathStart = $viewObj->pathToResources();
        
        $entryArr['zag']=trim(strip_tags($entryArr['zag']));
        $entryArr['zag']=preg_replace("/[\r\n\t]+/smiu"," ",$entryArr['zag']);
        $entryArr['epigraf']=trim(strip_tags($entryArr['epigraf']));
        $entryArr['epigraf']=preg_replace("/\r\n\r\n(?:\r\n)+/smiu","\r\n\r\n",$entryArr['epigraf']);
        $entryArr['epigraf']=preg_replace("/\r\n/smiu","<br>\r\n",$entryArr['epigraf']);

        if ($entryArr['epigraf']!='')
        {
            $entryArr['epigraf']='<div class="epigraf1"></div><div class="epigraf2">'.$entryArr['epigraf'].'</div>';
        }
	
        $entryArr['text'] = Formatter::convertPlainTagsToHtmlTags('abzac1',$entryArr['text'],$entryArr['entryid'],$pathStart);
        $entryArr['text'] = preg_replace("/(([0-9]+)\/([0-9]+)\.(jpg|gif|png)\" class=\"pictext\")/smiu","$1 onClick=\"showpic($2,$3,'$4');\"",$entryArr['text']);

        return $entryArr;
    }

}
