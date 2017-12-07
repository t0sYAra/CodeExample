<?php
namespace AntonPavlov\PersonalSite\Models;

use AntonPavlov\PersonalSite\Base\Model;

class SearchModel extends Model
{

    public function get()
    {
        $query = $this->initDB()->prepare('select `text` as `tags` from `entries` limit 0,1000');
        $result = $query->execute();
       
        if ((!isset($result))||(!$result)) {
            throw new \Exception('Ошибка обработки запроса к БД');
        }

        $resultsArr = $query->fetchAll(\PDO::FETCH_NUM);

        if (empty($resultsArr)) {
            throw new \Exception('Ничего не найдено.');
        }
        
        return $resultsArr;
    }
    
}