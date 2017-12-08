<?php
namespace AntonPavlov\PersonalSite\Models;

use AntonPavlov\PersonalSite\Base\Model;

/**
 * Модель для работы с тегами в записях
 *
 * Предоставляет данные обо всех существующих тегах
 *
 * @package AntonPavlov\PersonalSite
 *
 * @author Anton Pavlov <mail@antonpavlov.ru>
 *
 */
class SearchModel extends Model
{

    /**
     * Возвращает все существующие теги
     *
     *
     * @throws \Exception если не удалось получить данные из БД
     *
     * @return array
     */
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