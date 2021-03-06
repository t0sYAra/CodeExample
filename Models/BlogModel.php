<?php
namespace AntonPavlov\PersonalSite\Models;

use AntonPavlov\PersonalSite\Base\Registry;
use AntonPavlov\PersonalSite\Base\Model;
use AntonPavlov\PersonalSite\Exceptions\SearchStringNotFoundException;

/**
 * Модель для работы с блогом (множеством записей)
 *
 * Получает записи из блога, соответствующие им id картинок и количество комментариев
 *
 * @package AntonPavlov\PersonalSite
 *
 * @author Anton Pavlov <mail@antonpavlov.ru>
 *
 */
class BlogModel extends Model
{

    /**
     * Возвращает ассоциативный массив записей с параметрами (заголовок, текст и т.п.), удовлетворяющий условиям запроса
     *
     * @param int $begin номер строки в выдаче БД, с которой начинается выдача записей на текущей странице
     * @param int $end номер строки в выдаче БД, на которой заканчивается выдача записей на текущей странице
     * @param array $params массив, содержащие условия запроса: год, теги, способ сортировки, поисковую строку
     *
     * @throws \Exception если не удалось получить данные из БД
     * @throws \SearchStringNotFoundException если в БД нет записей, соответствующих поисковому запросу - 
     * нужно для передачи наверх содержания поискового запроса
     *
     * @return array
     */
    public function getMain($begin, $end, $params)
    {
        // формируем условие запроса
        $uslfield = '';
        $usl[] = '`ifshow`=1';
        $uslkol = 0;
        $yearDefined = false;
        $tagsDefined = false;
        $searchDefined = false;
        
        if ($params['year']!='') {
            $usl[]='(DATE_FORMAT(`published`, "%Y")=:year)';
            $uslkol++;
            $yearDefined = true;
        }
        if ($params['tags']!='') {
            $params['tags']=trim(strip_tags($params['tags']));
            $usl[]='`text` regexp :tags';
            $uslkol++;
            $tagsDefined = true;
        }
        if ($params['search']!='') {
            $params['search']=trim(strip_tags($params['search']));
            $usl[]='`text` regexp :search';
            $uslkol++;
            $searchDefined = true;
        }
        $uslfield=' where '.implode('&&', $usl);

        $orderusl=' order by `published` desc';
        if ($params['sort'] == 'best') {
            $orderusl=' order by `rating` desc, `published` desc';
        }

        
        $queryCount = $this->initDB()->prepare('select count(`entryid`) as `kol` from `entries`'.$uslfield.' limit 0,1');
        if ($yearDefined) {
            $queryCount->bindParam(':year', $params['year'], \PDO::PARAM_INT);
        }
        if ($tagsDefined) {
            $tags = '[^\(]#'.mb_strtolower(rawurldecode($params['tags'])).'';
            $queryCount->bindParam(':tags', $tags, \PDO::PARAM_STR);
        }
        if ($searchDefined) {
            $search = mb_strtolower(rawurldecode($params['search']));
            $queryCount->bindParam(':search', $search, \PDO::PARAM_STR);
        }
        $resultCount = $queryCount->execute();
        
        if ((!isset($resultCount))||(!$resultCount)) {
            throw new \Exception('Ошибка обработки запроса к БД - 1');
        }

        $resultsCountArr = $queryCount->fetch(\PDO::FETCH_ASSOC);

        if ((empty($resultsCountArr))||($resultsCountArr['kol'] == 0)) {
            if (!empty($params['search'])) {
                throw new SearchStringNotFoundException('Ничего не найдено.', rawurldecode($params['search']));
            }
            throw new \Exception('Ничего не найдено.');
        }
    
        $offset = $begin - 1;
        $diff = $end - $begin + 1;
        $queryEntries = $this->initDB()->prepare('select `entryid`, `zag`, `translit`, `text`, `author`, `published` from `entries`'.$uslfield.$orderusl.' limit :offset, :diff');
        $queryEntries->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $queryEntries->bindParam(':diff', $diff, \PDO::PARAM_INT);
        if ($yearDefined) {
            $queryEntries->bindParam(':year', $params['year'], \PDO::PARAM_INT);
        }
        if ($tagsDefined) {
            $queryEntries->bindParam(':tags', $tags, \PDO::PARAM_STR);
        }
        if ($searchDefined) {
            $queryEntries->bindParam(':search', $search, \PDO::PARAM_STR);
        }
        $resultEntries = $queryEntries->execute();

        if ((!isset($resultEntries))||(!$resultEntries)) {
            throw new \Exception('Ошибка обработки запроса к БД - 2');
        }

        $resultsEntriesArr = $queryEntries->fetchAll(\PDO::FETCH_ASSOC);

        if (empty($resultsEntriesArr)) {
            if (!empty($params['search'])) {
                throw new SearchStringNotFoundException('Ничего не найдено.', rawurldecode($params['search']));
            }
            throw new \Exception('Ничего не найдено.');
        }

        $resultsEntriesArr[0]['kolstrok'] = $resultsCountArr['kol'];
        
        return $resultsEntriesArr;
    }

    /**
     * Возвращает массив с данными о id картинки и её типе
     *
     * @param int $entryId id записи в блоге
     *
     * @throws \Exception если не удалось получить данные из БД
     *
     * @return array
     */
    public function getPicture($entryId)
    {
        $query = $this->initDB()->prepare('select `picid`, `type`, `ifmain` from `pics` where `entryid`=:entryId order by `ifmain` desc, `picid` asc limit 0,1');
        $query->bindParam(':entryId', $entryId, \PDO::PARAM_INT);
        $result = $query->execute();
        
        if ((!isset($result))||(!$result)) {
            throw new \Exception('Ошибка обработки запроса к БД');
        }

        $resultsArr = $query->fetch(\PDO::FETCH_ASSOC);

        if (empty($resultsArr)) {
            throw new \Exception('Ничего не найдено.');
        }
        
        return $resultsArr;
    }

    /**
     * Возвращает количество комментариев к исследуемой записи в блоге
     *
     * @param int $entryId id записи в блоге
     *
     * @throws \Exception если не удалось получить данные из БД
     *
     * @return array
     */
    public function getCommentsAmmount($entryId)
    {
        $query = $this->initDB()->prepare('select count(`id`) as `commentsAmmount` from `comments` where `parententryid`=:entryId limit 0,1');
        $query->bindParam(':entryId', $entryId, \PDO::PARAM_INT);
        $result = $query->execute();
        
        if ((!isset($result))||(!$result)) {
            throw new \Exception('Ошибка обработки запроса к БД');
        }

        $resultsArr = $query->fetch(\PDO::FETCH_ASSOC);

        if (empty($resultsArr)) {
            throw new \Exception('Ничего не найдено.');
        }
        
        return $resultsArr;
    }
    
}