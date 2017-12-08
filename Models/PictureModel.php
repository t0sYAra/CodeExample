<?php
namespace AntonPavlov\PersonalSite\Models;

use AntonPavlov\PersonalSite\Base\Model;

/**
 * Модель для работы с картинками
 *
 * Предоставляет данные о картинке
 *
 * @package AntonPavlov\PersonalSite
 *
 * @author Anton Pavlov <mail@antonpavlov.ru>
 *
 */
class PictureModel extends Model
{

    /**
     * Возвращает id и тип картинки (png/jpg/gif)
     *
     * @param int $entryId id записи, к которой прикреплена картинка
     * @param int $picId id картинки без ведущих нулей
     *
     * @throws \Exception если не удалось получить данные из БД
     *
     * @return array
     */
    public function get($entryId, $picId)
    {
        $query = $this->initDB()->prepare('select `picid`, `type` from `pics` where `entryid`=:entryId&&`picid`=:picId limit 0,1');
        $query->bindParam(':entryId', $entryId, \PDO::PARAM_INT);
        $query->bindParam(':picId', sprintf("%03d",$picId), \PDO::PARAM_STR);
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