<?
namespace AntonPavlov\PersonalSite\Base;

trait Formatter
{
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

	function clearPlainTags($text)
	{
		// подчищаем текст, удаляем возможные html-теги и т.п.
		$text = trim(strip_tags($text));
		$text = preg_replace("/(zag|tgg|vid|pic|aud)(111|444|777)(.*?)(zag|tgg|vid|pic|aud)(333|666|999)/smiu", "", $text);
		$text = preg_replace("/\r\n\r\n(?:\r\n)+/smiu", "\r\n\r\n", $text);
		$text = preg_replace("/([^\r\n])\r\n([^\r\n])/smiu", "$1<br>\r\n$2", $text);

		return trim($text);
	}
	
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
        
	function convertPlainTagsToHtmlTags($pclass,$text,$entryid,$dir)
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
		$text=preg_replace("/(\<a href=\")(blog\/tags=)/smiu","$1".$dir."$2",$text);
		
		// youtube
		//$text=preg_replace("/(?:\<br\>\r\n|\<p class=\"$pclass\"\>)vid111(?:www\.)?youtube\.com\/(?:v\/)?(?:watch\?v=)?([-_a-z0-9]{1,11})vid999(?:\<\/p\>|\<br\>)[\r\n]{0,10}/smiu","</p>\r\n<div class=\"youtubevideo1\"><iframe id=\"ytplayer\" type=\"text/html\" class=\"ytplayer\" src=\"https://www.youtube.com/embed/$1\" frameborder=\"0\" allowfullscreen></iframe></div>\r\n<p class=\"$pclass\">",$text);
		$text=preg_replace("/(?:\<br\>\r\n|\<p class=\"$pclass\"\>)vid111(?:www\.)?youtube\.com\/(?:v\/)?(?:watch\?v=)?([-_a-z0-9]{1,11})vid999(?:\<\/p\>|\<br\>)[\r\n]{0,10}/smiu","</p>\r\n<div class=\"youtubevideo1\">$1</div>\r\n<p class=\"$pclass\">",$text);
		
		// картинки
		$text=preg_replace("/\<br\>[\r\n]{0,10}((?:pic111(?:[1-9][0-9]{0,5}\.(?:jpg|gif|png))pic999)+)\<\/p\>/smiu","</p>\r\n$1",$text);
		$text=preg_replace("/pic111([1-9][0-9]{0,5}\.(?:jpg|gif|png))pic999/smiu","<div class=\"pic02\"><img src=\"".$dir."pics/0/$entryid/$1\" class=\"pictext\"></div>",$text);
		$text=preg_replace("/((?:\<div class=\"pic02\"\>\<img src=\"(\.\.\/){0,10}pics\/0\/[1-9][0-9]{0,10}\/[1-9][0-9]{0,5}\.(?:jpg|gif|png)\"\ class=\"pictext\">\<\/div\>)+)/smiu","<div class=\"pic01\">$1</div>",$text);
		
		// ссылки mailto
		$text=preg_replace("/lnk111mailto\:(.*?)lnk222(.*?)lnk333/smiu","<a href=\"mailto:$1\" class=\"textlink\">$2</a>",$text);
		
		// ссылки внешние
		$text=preg_replace("/lnk111(.*?)lnk222(.*?)lnk333/smiu","<a href=\"http://$1\" target=_blank class=\"textlink\">$2</a>",$text);
		
		// ссылки внутренние
		$text=preg_replace("/lnk444(.*?)lnk555(.*?)lnk666/smiu","<a href=\"".$dir."$1\" class=\"textlink\">$2</a>",$text);
		
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
		$text=preg_replace("/[\s\t]{0,10}aud111(.*?)aud222(.*?)aud333\.?(\<\/p\>)?/smiu","</p>\r\n<div class=\"audio\"><audio controls><source src=\"".$dir."audio/$entryid/$1\" type=\"audio/mp3\">Воспроизведение аудио не поддерживается вашим браузером.<br><a href=\"".$dir."audio/$entryid/$1\" target=_blank class=\"textlink\">Скачайте запись</a>.</audio></div>",$text);
		
		return $text;
	}
	
    function getNavField($params, $begin, $end, $kolstrok, $kolstroktek, $diff, $pathStart)
    {
        $navfield = '';
        // разбираемся с полем перехода на другие страницы
        $nazadfield='';
        $daleefield='';
        $link='blog';
        if ($params['year']!='') {$link.='/'.rawurlencode($params['year']);}
        if ($params['tags']!='') {$link.='/tags='.rawurlencode($params['tags']);}
        if ($params['sort']!='') {$link.='/'.rawurlencode($params['sort']);}
        if ($params['search']!='') {$link.='/search='.rawurlencode($params['search']);}
        
        if ($begin>1)
        {
            $newbegin=$begin-$diff;
            if ($newbegin<=0) {$newbegin=1;}
            $newend=$begin-1;
            $nazadfield='<a href="'.$pathStart.$link.'/'.$newbegin.'-'.$newend.'/" class="link1">Назад</a>';
        }
        if ($kolstrok-($begin-1)>$kolstroktek)
        {
            $newbegin=$end+1;
            $newend=$newbegin+$diff-1;
            if ($kolstrok<$newend) {$newend=$kolstrok;}
            $daleefield='<a href="'.$pathStart.$link.'/'.$newbegin.'-'.$newend.'/" class="link1">Далее</a>';
        }
        $navfield = '<div class="navfield1">'.$nazadfield.'</div><div class="navfield2">'.$daleefield.'</div>';
        
        return $navfield;
    }
}