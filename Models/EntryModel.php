<?php
namespace AntonPavlov\PersonalSite\Models;

use AntonPavlov\PersonalSite\Base\Model;
use AntonPavlov\PersonalSite\Exceptions\Page404Exception;
use AntonPavlov\PersonalSite\Exceptions\CommentNotFoundException;

/**
 * Модель для работы с единичной записью и комментариями к ней
 *
 * Получает записи из блога, соответствующие им id картинок и количество комментариев
 *
 * @package AntonPavlov\PersonalSite
 *
 * @author Anton Pavlov <mail@antonpavlov.ru>
 *
 */
class EntryModel extends Model
{

    /**
     * Возвращает массив с параметрами записи(заголовок, текст и т.п.) по транслитерированному заголовку записи
     *
     * @param string $nameTranslitted транслитерированный заголовок записи
     *
     * @throws \Exception если не удалось получить данные из БД
     * @throws \Page404Exception если в БД отсутствует искомая запись
     *
     * @return array
     */
    public function getMain($nameTranslitted)
    {
        $query = $this->initDB()->prepare('select `entryid`, `zag`, `translit`, `epigraf`, `text`, `author`, `rating`, `published` from `entries` where `translit`=?&&`ifshow`=1 limit 0,1');
        $query->bindParam(1, $nameTranslitted);
        $result = $query->execute();
        
        if ((!isset($result))||(!$result)) {
            throw new \Exception('Ошибка обработки запроса к БД');
        }

        $resultsArr = $query->fetch(\PDO::FETCH_ASSOC);
        
        if (empty($resultsArr)) {
            throw new Page404Exception('Страница не найдена');
        }

        return $resultsArr;
    }

    /**
     * Возвращает транслитерированный заголовок записи
     *
     * @param int $entryId id записи
     *
     * @throws \Exception если не удалось получить данные из БД
     *
     * @return array
     */
    public function getTranslit($entryId)
    {
        $query = $this->initDB()->prepare('select `translit` from `entries` where `entryid`=? limit 0,1');
        $query->bindParam(1, $entryId);
        $result = $query->execute();
                
        if ((!isset($result))||(!$result)) {
            throw new \Exception('Ошибка обработки запроса к БД');
        }

        $resultsArr = $query->fetch(\PDO::FETCH_ASSOC);
        
        if (empty($resultsArr)) {
            throw new \Exception('Ошибка обработки запроса к БД');
        }

        return $resultsArr;
    }
    
    /**
     * Возвращает уровень вложенности исследуемого комментария
     *
     * @param int $entryId id родительской записи
     * @param int $parentCommentsId id исследуемого комментария
     *
     * @throws \Exception если не удалось получить данные из БД
     *
     * @return array
     */
    public function getlevelId($entryId, $parentCommentsId)
    {
        $query = $this->initDB()->prepare('select `levelid` from `comments` where `id`=?&&`parententryid`=? limit 0,1');
        $query->bindParam(1, $parentCommentsId);
        $query->bindParam(2, $entryId);
        $result = $query->execute();
                
        if ((!isset($result))||(!$result)) {
            throw new \Exception('Ошибка обработки запроса к БД');
        }

        $resultsArr = $query->fetch(\PDO::FETCH_ASSOC);
        
        if (empty($resultsArr)) {
            throw new \Exception('Ошибка обработки запроса к БД');
        }

        return $resultsArr;
    }
    
    /**
     * Сохраняет новый комментарий в БД
     *
     * @param int $entryId id родительской записи
     * @param int $parentCommentsId id родительского комментария, при отсутствии - 0
     * @param int $levelId уровень вложенности нового комментария
     * @param string $commentsAuthor имя автора комментария
     * @param string $commentsText текст комментария
     *
     * @throws \Exception если не удалось записать комментарий в БД
     *
     * @return void
     */
    public function saveComment($entryId, $parentCommentsId, $levelId, $commentsAuthor, $commentsText)
    {
        $query = $this->initDB()->prepare('INSERT INTO `comments` values (null,?,?,?,?,?,?,?,1)');  
        $query->bindParam(1, $entryId);
        $query->bindParam(2, $parentCommentsId);
        $query->bindParam(3, $levelId);
        $query->bindParam(4, $commentsAuthor);
        $query->bindParam(5, $commentsText);
        $query->bindParam(6, Date('Y-m-d H:i:s'));
        $query->bindParam(7, $_SERVER['REMOTE_ADDR']);
        $result = $query->execute();
        
        if (!$result) {
            throw new \Exception('Ошибка сохранения комментария');
        }
    }
    
    /**
     * Получает комментарии из БД
     *
     * @param int $parentCommentsId id родительского комментария, при отсутствии - 0
     * @param int $parentEntryId id родительской записи
     *
     * @throws \Exception если не удалось записать комментарий в БД
     * @throws \CommentNotFoundException если не удалось найти комментарий, соответствующий условиям поиска
     *
     * @return array
     */
    public function getComments($parentCommentsId, $parentEntryId)
    {
        $query = $this->initDB()->prepare('select `id` as `parentCommentsId`, `levelid`, `author`, `text`, `published` from `comments` where `parentcommentsid`=?&&`parententryid`=?&&`ifshow`=1 order by `published` asc');
        $query->bindParam(1, $parentCommentsId);
        $query->bindParam(2, $parentEntryId);
        $result = $query->execute();
                
        if ((!isset($result))||(!$result)) {
            throw new \Exception('Ошибка обработки запроса к БД - 1');
        }

        $resultsArr = $query->fetchAll(\PDO::FETCH_ASSOC);
        
        if (empty($resultsArr)) {
            throw new CommentNotFoundException('Комментариев не найдено - 1');
        }

        return $resultsArr;
    }
    
    /**
     * Сохраняет новую запись в БД
     *
     * @param string $translittedHeading транслитерированный заголовок записи
     * @param string $heading заголовок записи
     * @param string $epigraph эпиграф
     * @param string $text текст записи
     * @param string $author имя автора
     * @param int $rating рейтинг записи от 0 и выше
     * @param int $ifShow 1 - показывать, 0 - нет
     *
     * @throws \Exception если не удалось записать комментарий в БД
     *
     * @return void
     */
    public function saveEntry($translittedHeading, $heading, $epigraph, $text, $author, $rating, $ifShow)
    {
        $published = Date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $query = $this->initDB()->prepare('INSERT INTO `entries` values (null,?,?,?,?,?,?,?,?,?,?,?)');  
        $query->bindParam(1, $translittedHeading);
        $query->bindParam(2, $heading);
        $query->bindParam(3, $epigraph);
        $query->bindParam(4, $text);
        $query->bindParam(5, $author);
        $query->bindParam(6, $rating);
        $query->bindParam(7, $ifShow);
        $query->bindParam(8, $published);
        $query->bindParam(9, $ip);
        $query->bindParam(10, $published);
        $query->bindParam(11, $ip);
        $result = $query->execute();
        
        if (!$result) {
            throw new \Exception('Ошибка публикации записи');
        }
    }
      
}