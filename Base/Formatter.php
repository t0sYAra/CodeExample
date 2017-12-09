<?php
namespace AntonPavlov\PersonalSite\Base;

use AntonPavlov\PersonalSite\Base\Registry;

/**
 * Форматирование текста
 *
 * Содержит методы для очистки текста от своих тегов,
 * получения первых абзацев текста и т.п.
 *
 * @package AntonPavlov\PersonalSite
 *
 * @author Anton Pavlov <mail@antonpavlov.ru>
 *
 */
trait Formatter
{

    /**
     * @var string $pathStart путь к ресурсам типа img, css, js  и т.п.
     */
    private static $pathStart;

    /**
     * Выдаёт путь к ресурсам, записанный в данных рестра
     *
     * @return string
     */
    private function getPathStart()
    {
        $reg = Registry::init();
        return $reg->get('pathStart');
    }

    /**
     * Выдаёт название месяца с маленькой буквы в родительном падеже
     *
     * @param string $month номер месяца с ведущим нулём (от 01 до 12)
     *
     * @return string
     */
    function getMonthInRussian($month)
    {
        $monthText = $month;
		$correspondence = array("01" => "января",
		"02" => "февраля",
		"03" => "марта",
		"04" => "апреля",
		"05" => "мая",
		"06" => "июня",
		"07" => "июля",
		"08" => "августа",
		"09" => "сентября",
		"10" => "октября",
		"11" => "ноября",
		"12" => "декабря");
		$monthText = strtr($month,$correspondence);

        return $monthText;
	}
     
    /**
     * Удаляет внутренние теги типа zag, tgg, vid  и т.п., а также множественные переводы строк
     *
     * @param string $text текст, подлежащий очистке
     *
     * @return string
     */
	function clearPlainTags($text)
	{
		$text = trim(strip_tags($text));
		$text = preg_replace("/(zag|tgg|vid|pic|aud)(111|444|777)(.*?)(zag|tgg|vid|pic|aud)(333|666|999)/smiu", "", $text);
		$text = preg_replace("/\r\n\r\n(?:\r\n)+/smiu", "\r\n\r\n", $text);
		$text = preg_replace("/([^\r\n])\r\n([^\r\n])/smiu", "$1<br>\r\n$2", $text);

		return trim($text);
	}
     
    /**
     * Удаляет часть текста, у которой есть открывающий тег, но нет закрывающего, обычно - для ссылок
     *
     * @param string $patternstart RegExp шаблон начала, обычно - внутренний открывающий тег
     * @param string $patternend RegExp шаблон конца, обычно - внутренний закрывающий тег
     * @param string $text текст, подлежащий очистке
     *
     * @return string
     */
	function clearTails1($patternstart,$patternend,$text)
	{
		$kol1=preg_match_all("/$patternstart/",$text);
		$kol2=preg_match_all("/$patternend/",$text);
		if ($kol1!=$kol2)
		{
			$text=preg_replace("/^(.*)$patternstart(.*)/smiu","$1...",$text);
		}
		return trim($text);
	}
	     
    /**
     * Добавляет внутренний закрывающий тег, если его нет, обычно - для тегов iii, hhh, bld и т.п.
     *
     * @param string $patternstart RegExp шаблон начала, обычно - внутренний открывающий тег
     * @param string $patternend RegExp шаблон конца, обычно - внутренний закрывающий тег
     * @param string $text текст, подлежащий очистке
     *
     * @return string
     */
	function clearTails2($patternstart,$patternend,$text)
	{
		$kol1=preg_match_all("/$patternstart/",$text);
		$kol2=preg_match_all("/$patternend/",$text);
		if ($kol1!=$kol2)
		{
			$text=preg_replace("/^(.*)$patternstart(.*)/smiu","$1$patternstart$2$patternend",$text);
		}
		return trim($text);
	}
		     
    /**
     * Корректирует чатси оборванные части текста, содержащие только открывающие теги, удаляет конечные знаки препинания
     *
     * @param string $text текст, подлежащий очистке
     *
     * @return string
     */
	function clearTagsTails($text)
	{
		// очищаем части слов
		$text=preg_replace("/[\s]+[^\s]+$/smiu","...",$text);

		// удаляем не закрытые собственные теги
		$patternslinks=[
		['lnk111','lnk333'],
		['lnk444','lnk666'],
		['lnk777','lnk999']
		];
		for ($i=0;$i<count($patternslinks);$i++)
		{
			// не закрытые ссылки удаляем
			$text=Formatter::clearTails1($patternslinks[$i][0],$patternslinks[$i][1],$text);
		}
		$patternsformat=[
		['hhh111','hhh999'],
		['iii111','iii999'],
		['bld111','bld999'],
		['stk111','stk999'],
		['mst111','mst999']
		];
		for ($i=0;$i<count($patternsformat);$i++)
		{
			// не закрытые теги форматирования закрываем
			$text=Formatter::clearTails2($patternsformat[$i][0],$patternsformat[$i][1],$text);
		}
		
		// очищаем открывающие теги, если они "оборваны"
		$text=preg_replace("/[lnkmsthibs]{1,3}[124578]{0,2}[.]+/smiu","...",$text);
		// очищаем конечные знаки препинания
		$text=preg_replace("/[-_!?, ]+[.]+/smiu","...",$text);
		
		return $text;
	}

    /**
     * Возвращает начальную часть текста, ограниченную указанным количеством символов и абзацев
     *
     * @param string $translit транслитерированный заголовок записи в блоге
     * @param string $text текст, подлежащий очистке
     * @param int $abzacLimit лимит на количество абзацев
     * @param int $symbolsLimit лимит на количество символов
     *
     * @return string
     */
    function getFirstParagraphs($translit, $text, $abzacLimit, $symbolsLimit)
    {
        $abzacarr=explode("\r\n\r\n",$text);
        $tempstr='';
        $tempabzaclimit=$abzacLimit;
        if (count($abzacarr)<$tempabzaclimit) {$tempabzaclimit=count($abzacarr);}
        for ($t=0;$t<$tempabzaclimit;$t++)
        {
            $tempstr.=$abzacarr[$t];
            $tempdlina=mb_strlen($tempstr);
            if ($tempdlina>$symbolsLimit)
            {
                // заканчиваем цикл
                $symbolsdiff=$tempdlina-$symbolsLimit;
                mb_strlen($abzacarr[$t]);
                $abzacarr[$t]=mb_substr($abzacarr[$t],0,mb_strlen($abzacarr[$t])-$symbolsdiff).'...';
                
                $abzacarr[$t]=Formatter::clearTagsTails($abzacarr[$t]);
                $abzacarr[$t].=' lnk444blog/'.$translit.'lnk555читать дальшеlnk666';
                $vstupleniearr[]="\r\n\r\n".$abzacarr[$t];
                break;
            } else {
                // пока ещё символов хватает
                $vstupleniearr[]="\r\n\r\n".$abzacarr[$t];
            }
        }

        $temptext=trim(implode("\r\n",$vstupleniearr));

        return $temptext;
    }

    /**
     * Возвращает текст, в котором внутренние теги замененые на html-теги
     *
     * @param string $pclass основной стиль абзаца <p>
     * @param string $text текст, подлежащий обработке
     * @param int $entryid id записи в блоге
     *
     * @return string
     */
	function convertPlainTagsToHtmlTags($pclass,$text,$entryid)
	{
		// подчищаем текст, удаляем возможные html-теги и т.п.
		$text=trim(strip_tags($text));
		$text=preg_replace("/\r\n\r\n(?:\r\n)+/smiu","\r\n\r\n",$text);
		$text=preg_replace("/([^\r\n])\r\n([^\r\n])/smiu","$1<br>\r\n$2",$text);
		$text=preg_replace("/zag111(.*?)zag999[\r\n]+/smiu","",$text);
		$text=preg_replace("/hhh999\<br\>[\r\n]+/smiu","hhh999\r\n\r\n",$text);
		$text='<p class="'.$pclass.'">'.preg_replace("/\r\n\r\n/smiu","</p>\r\n<p class=\"$pclass\">",$text).'</p>';
		$text=preg_replace("/(?:[\r\n]+)?tgg111(.*?)tgg999(?:[\r\n]+)?/smiu","\r\n<p class=\"tags\">$1</p>",$text);
		$text=preg_replace("/\<p class=\"$pclass\"\>(?:[\r\n]+)(\<p class=\"tags\"\>)/smiu","$1",$text);
		$text = preg_replace_callback("/\\??#([-_ a-zа-яё0-9]+)([\),\<])/smiu",
		function($matches) {
        return "<a href=\"blog/tags=".rawurlencode(mb_strtolower($matches[1]))."\" class=\"tags\">#".$matches[1]."</a>".$matches[2];
		},$text);
		$text=preg_replace("/(\<a href=\")(blog\/tags=)/smiu","$1".Formatter::getPathStart()."$2",$text);
		
		// youtube
		//$text=preg_replace("/(?:\<br\>\r\n|\<p class=\"$pclass\"\>)vid111(?:www\.)?youtube\.com\/(?:v\/)?(?:watch\?v=)?([-_a-z0-9]{1,11})vid999(?:\<\/p\>|\<br\>)[\r\n]{0,10}/smiu","</p>\r\n<div class=\"youtubevideo1\"><iframe id=\"ytplayer\" type=\"text/html\" class=\"ytplayer\" src=\"https://www.youtube.com/embed/$1\" frameborder=\"0\" allowfullscreen></iframe></div>\r\n<p class=\"$pclass\">",$text);
		$text=preg_replace("/(?:\<br\>\r\n|\<p class=\"$pclass\"\>)vid111(?:www\.)?youtube\.com\/(?:v\/)?(?:watch\?v=)?([-_a-z0-9]{1,11})vid999(?:\<\/p\>|\<br\>)[\r\n]{0,10}/smiu","</p>\r\n<div class=\"youtubevideo1\">$1</div>\r\n<p class=\"$pclass\">",$text);
		
		// картинки
		$text=preg_replace("/\<br\>[\r\n]{0,10}((?:pic111(?:[1-9][0-9]{0,5}\.(?:jpg|gif|png))pic999)+)\<\/p\>/smiu","</p>\r\n$1",$text);
		$text=preg_replace("/pic111([1-9][0-9]{0,5}\.(?:jpg|gif|png))pic999/smiu","<div class=\"pic02\"><img src=\"".Formatter::getPathStart()."pics/0/$entryid/$1\" class=\"pictext\"></div>",$text);
		$text=preg_replace("/((?:\<div class=\"pic02\"\>\<img src=\"(\.\.\/){0,10}pics\/0\/[1-9][0-9]{0,10}\/[1-9][0-9]{0,5}\.(?:jpg|gif|png)\"\ class=\"pictext\">\<\/div\>)+)/smiu","<div class=\"pic01\">$1</div>",$text);
		
		// ссылки mailto
		$text=preg_replace("/lnk111mailto\:(.*?)lnk222(.*?)lnk333/smiu","<a href=\"mailto:$1\" class=\"textlink\">$2</a>",$text);
		
		// ссылки внешние
		$text=preg_replace("/lnk111(.*?)lnk222(.*?)lnk333/smiu","<a href=\"http://$1\" target=_blank class=\"textlink\">$2</a>",$text);
		
		// ссылки внутренние
		$text=preg_replace("/lnk444(.*?)lnk555(.*?)lnk666/smiu","<a href=\"".Formatter::getPathStart()."$1\" class=\"textlink\">$2</a>",$text);
		
		// ссылки на место на странице
		$text=preg_replace("/lnk777(.*?)lnk888(.*?)lnk999/smiu","<a href=\"$1\" class=\"textlink\">$2</a>",$text);
		
		// место на странице
		$text=preg_replace("/mst111(.*?)mst999/smiu","<span name=\"$1\" id=\"$1\"></span>",$text);
		
		// лишние абзацы
		$text=preg_replace("/\<p class=\"$pclass\"\>(\<span|hhh111)/smiu","$1",$text);
		$text=preg_replace("/(hhh999)\<\/p\>/smiu","$1",$text);
		$text=preg_replace("/(\<p class=\"$pclass\"\>)+/smiu","$1",$text);
		$text=preg_replace("/\<\/p\>(?:\r\n){0,10}\<\/p\>/smiu","</p>",$text);

		// h2
		$text=preg_replace("/hhh111(.*?)hhh999/smiu","<h2 class=\"h2text\">$1<div class=\"h2orangeline\"></div></h2>",$text);
		
		// i
		$text=preg_replace("/iii111(.*?)iii999/smiu","<i>$1</i>",$text);
		
		// strong
		$text=preg_replace("/bld111(.*?)bld999/smiu","<strong>$1</strong>",$text);
		
		// strike
		$text=preg_replace("/stk111(.*?)stk999/smiu","<strike>$1</strike>",$text);
		
		// подкаст
		$text=preg_replace("/[\s\t]{0,10}aud111(.*?)aud222(.*?)aud333\.?(\<\/p\>)?/smiu","</p>\r\n<div class=\"audio\"><audio controls><source src=\"".Formatter::getPathStart()."audio/$entryid/$1\" type=\"audio/mp3\">Воспроизведение аудио не поддерживается вашим браузером.<br><a href=\"".Formatter::getPathStart()."audio/$entryid/$1\" target=_blank class=\"textlink\">Скачайте запись</a>.</audio></div>",$text);
		
		return $text;
	}

    /**
     * Возвращает навигационное меню для блога "<-назад | далее->"
     *
     * @param array $params массив, содержащие опциональные ограничения на выдачу блога: год, теги, способ сортировки, поисковую строку
     * @param int $begin номер строки в выдаче БД, с которой начинается выдача записей на текущей странице
     * @param int $end номер строки в выдаче БД, на которой заканчивается выдача записей на текущей странице
     * @param int $kolstrok сколько всего записей в блоге есть в БД по данному запросу
     * @param int $kolstroktek сколько записей из блога выведено на текущую страницу
     * @param int $diff сколько записей должно выводиться на текущую страницу
     *
     * @return string
     */
    function getNavField($params, $begin, $end, $kolstrok, $kolstroktek, $diff)
    {
        $navfield = '';
        // разбираемся с полем перехода на другие страницы
        $nazadfield='';
        $daleefield='';
        $link='blog';
        if ($params['year']!='') {$link.='/'.$params['year'];}
        if ($params['tags']!='') {$link.='/tags='.$params['tags'];}
        if ($params['sort']!='') {$link.='/'.$params['sort'];}
        if ($params['search']!='') {$link.='/search='.$params['search'];}
        
        if ($begin>1)
        {
            $newbegin=$begin-$diff;
            if ($newbegin<=0) {$newbegin=1;}
            $newend=$begin-1;
            $nazadfield='<a href="'.Formatter::getPathStart().$link.'/'.$newbegin.'-'.$newend.'/" class="link1">Назад</a>';
        }
        if ($kolstrok-($begin-1)>$kolstroktek)
        {
            $newbegin=$end+1;
            $newend=$newbegin+$diff-1;
            if ($kolstrok<$newend) {$newend=$kolstrok;}
            $daleefield='<a href="'.Formatter::getPathStart().$link.'/'.$newbegin.'-'.$newend.'/" class="link1">Далее</a>';
        }
        $navfield = '<div class="navfield1">'.$nazadfield.'</div><div class="navfield2">'.$daleefield.'</div>';
        
        return $navfield;
    }

    /**
     * Возвращает слово с окончанием, соответствующим числительному перед словом
     *
     * @param int $kol числительное, стоящее перед обрабатываемым словом
     * @param string $word слово в именительном падеже с маленькой буквы
     *
     * @return string
     */
	function defineWordEnding($kol,$word)
	{
	if ($word=='знак') {
		$temp='знаков';
		if ((mb_substr($kol,mb_strlen($kol)-1,1)=='1')&&(($kol<11)||($kol>19))) {
            $temp='знак';
        }
		if (((mb_substr($kol,mb_strlen($kol)-1,1)=='2')||(mb_substr($kol,mb_strlen($kol)-1,1)=='3')||(mb_substr($kol,mb_strlen($kol)-1,1)=='4'))&&(($kol<11)||($kol>19))) {
            $temp='знака';
        }
        return $temp;
	}
	if ($word=='комментарий') {
		$temp='комментариев';
		if ((mb_substr($kol,mb_strlen($kol)-1,1)=='1')&&(($kol<11)||($kol>19))) {
            $temp='комментарий';
        }
		if (((mb_substr($kol,mb_strlen($kol)-1,1)=='2')||(mb_substr($kol,mb_strlen($kol)-1,1)=='3')||(mb_substr($kol,mb_strlen($kol)-1,1)=='4'))&&(($kol<11)||($kol>19))) {
            $temp='комментария';
        }
        return $temp;
	}
	
	return '';
	}

    /**
     * Возвращает транслитерированный текст
     *
     * @param string $text текст, который нужно транслитерировать
     *
     * @return string
     */
    function translit($text)
    {
        $text  =  trim($text); // убираем пробелы в начале и конце строки
        $text  =  strip_tags(mb_strtolower($text)); // убираем HTML-теги
        $text  =  preg_replace("/[\s\n\r\t]+/siu", ' ', $text); // удаляем повторяющие пробелы
        $text  =  preg_replace("/[^0-9a-za-я-_ ]/siu", "", $text); // очищаем строку от недопустимых символов
        $text  =  preg_replace("/([_ ]+)/siu", "_", $text);

        $pastletter = '';
        $arr = preg_split('/(?<!^)(?!$)/siu',$text);
        $k = 0;
        
        for ($i = 0; $i < count($arr); $i++) {
            if ($arr[$i] == 'а') {$newarr[$k] = 'a';}
            if ($arr[$i] == 'б') {$newarr[$k] = 'b';}
            if ($arr[$i] == 'в') {$newarr[$k] = 'v';}
            if ($arr[$i] == 'г') {$newarr[$k] = 'g';}
            if ($arr[$i] == 'д') {$newarr[$k] = 'd';}
            if ($arr[$i] == 'е') {$newarr[$k] = 'e';}
            if ($arr[$i] == 'ё') {$newarr[$k] = 'yo';}
            if ($arr[$i] == 'ж') {$newarr[$k] = 'zh';}
            if ($arr[$i] == 'з') {$newarr[$k] = 'z';}
            if ($arr[$i] == 'и') {$newarr[$k] = 'i';}
            if ($arr[$i] == 'й') {$newarr[$k] = 'j';}
            if ($arr[$i] == 'к') {$newarr[$k] = 'k';}
            if ($arr[$i] == 'л') {$newarr[$k] = 'l';}
            if ($arr[$i] == 'м') {$newarr[$k] = 'm';}
            if ($arr[$i] == 'н') {$newarr[$k] = 'n';}
            if ($arr[$i] == 'о') {$newarr[$k] = 'o';}
            if ($arr[$i] == 'п') {$newarr[$k] = 'p';}
            if ($arr[$i] == 'р') {$newarr[$k] = 'r';}
            if ($arr[$i] == 'с') {$newarr[$k] = 's';}
            if ($arr[$i] == 'т') {$newarr[$k] = 't';}
            if ($arr[$i] == 'у') {$newarr[$k] = 'u';}
            if ($arr[$i] == 'ф') {$newarr[$k] = 'f';}
            if ($arr[$i] == 'х') {
                if (($pastletter == 'c')||($pastletter == 's')||($pastletter == 'e')||($pastletter == 'h')) {
                    $newarr[$k] = 'kh';
                } else {
                    $newarr[$k] = 'h';
                }
            }
            if ($arr[$i] == 'ц') {$newarr[$k] = 'c';}
            if ($arr[$i] == 'ч') {$newarr[$k] = 'ch';}
            if ($arr[$i] == 'ш') {$newarr[$k] = 'sh';}
            if ($arr[$i] == 'щ') {$newarr[$k] = 'shch';}
            if ($arr[$i] == 'ъ') {$newarr[$k] = '';}
            if ($arr[$i] == 'ы') {$newarr[$k] = 'y';}
            if ($arr[$i] == 'ь') {$newarr[$k] = '';}
            if ($arr[$i] == 'э') {$newarr[$k] = 'eh';}
            if ($arr[$i] == 'ю') {$newarr[$k] = 'yu';}
            if ($arr[$i] == 'я') {$newarr[$k] = 'ya';}

            if (preg_match("/^[-_0-9a-z]$/",$arr[$i]) == 1) {
                $newarr[$k] = $arr[$i];
            }

            $pastletter = $newarr[$k];
            $k++;
        }
        
    $newtext = implode('', $newarr);
    $newtext  =  preg_replace("/(_\-_)+/iu", "_", $newtext);
    
    return $newtext;
}


}