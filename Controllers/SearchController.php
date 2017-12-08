<?php
namespace AntonPavlov\PersonalSite\Controllers;

use AntonPavlov\PersonalSite\Base\Controller;
use AntonPavlov\PersonalSite\Models\SearchModel;

/**
 * Контроллер, обрабатывающий запрос на загрузку страницы поиска
 *
 * @package AntonPavlov\PersonalSite
 *
 * @author Anton Pavlov <mail@antonpavlov.ru>
 *
 */
class SearchController extends Controller
{
    
    /**
     * Выводит страницу поиска с формой поискового запроса и облаком тегов
     *
     * @return void
     */
	function indexAction()
	{
        $finalTagsArr = Array ();
        // получаем теги из всех записей
        try {
            $tags = new SearchModel();
            $tagsArr = $tags->get();
            
            for ($i=0; $i<count($tagsArr); $i++) {
                $tagsArr[$i][0]=preg_replace("/(?:.*?)tgg111#(.*?)tgg999(?:.*)/smiu","$1",$tagsArr[$i][0]);
                $tempArr=explode(', #',$tagsArr[$i][0]);
                for ($j=0; $j<count($tempArr); $j++) {
                    $clearedTagsArr[]=$tempArr[$j];
                }
                unset($tempArr);
            }
        
            // формируем ассоциативный массив "тег" => "сколько таких тегов"
            if ((isset($clearedTagsArr))&&(count($clearedTagsArr)>0)) {
                // сортируем теги по алфавиту
            	sort($clearedTagsArr);
                
                // преобразуем в массив, в котором значениями будет количество вхождений
                $newTagsArr=array_count_values($clearedTagsArr);
                reset($newTagsArr); // указатель в начало

                // вычисляем промежуточные данные
                $max=max($newTagsArr);
                $min=min($newTagsArr);
                $minShrift=100;
                $maxShrift=300;
                $shriftDiff=$maxShrift-$minShrift+1;
                $tagsDiff=($max-$min+1)/$shriftDiff;
                
                // для каждого тега вычисляем размер шрифта
                foreach ($newTagsArr as $key => $value) {
                    $fontSize=$minShrift+floor(($value-$min)/$tagsDiff);
                    $finalTagsArr[] = [
                        'name' => htmlentities($key),
                        'url' => rawurlencode($key),
                        'fontSize' => $fontSize
                    ];
                }
            }
            
        } catch (\Exception $e) {
            // ошибка во время запроса к БД, ничего не делаем
        }

		$this->view->includeViewFile
        (
            'search.php', // файл с контентом
            'mainTemplate.php', // шаблон
            'search.js', // js-files
            '', // prefetch
            [
                'title' => 'Поиск по сайту', // здесь и далее - дополнительные данные
                'description' => 'Поиск по сайту Антона Павлова. Поиск по хештегам',
                'tagsArr' => $finalTagsArr
            ]
        );
	}
}
