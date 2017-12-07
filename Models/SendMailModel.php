<?php
namespace AntonPavlov\PersonalSite\Models;

use AntonPavlov\PersonalSite\Base\Registry;
use AntonPavlov\PersonalSite\Base\Model;

class SendMailModel extends Model
{

    public function get($mailsPerTime)
    {
        $query = $this->initDB()->prepare('select `id`, `poluchateli`, `tema`, `telopisma`, `headers` from `mailingtasks` where `tosend`<=:toSendDate order by `tosend` asc limit 0, :mailsPerTime');
        $query->bindParam(':toSendDate', date('YmdHis'), \PDO::PARAM_STR);
        $query->bindParam(':mailsPerTime', $mailsPerTime, \PDO::PARAM_INT);
        $result = $query->execute();

        if ((!isset($result))||(!$result)) {
            throw new \Exception('Ошибка обработки запроса к БД');
        }

        $resultsArr = $query->fetchAll(\PDO::FETCH_ASSOC);

        if (empty($resultsArr)) {
            throw new \Exception('Ничего не найдено.');
        }
        
        return $resultsArr;
    }
   
    public function deleteOneMail($mailId)
    {
        $query = $this->initDB()->prepare('delete from `mailingtasks` where `id`=:mailId');
        $query->bindParam(':mailId', $mailId, \PDO::PARAM_INT);
        $result = $query->execute();

        if ((!isset($result))||(!$result)) {
            throw new \Exception('Ошибка обработки запроса к БД');
        }
        
        return true;
    }
   
}