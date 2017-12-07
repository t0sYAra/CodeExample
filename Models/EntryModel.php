<?
namespace AntonPavlov\PersonalSite\Models;

use AntonPavlov\PersonalSite\Base\Model;
use AntonPavlov\PersonalSite\Exceptions\Page404Exception;
use AntonPavlov\PersonalSite\Exceptions\CommentNotFoundException;

class EntryModel extends Model
{

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
       
}