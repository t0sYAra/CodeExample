<?php
namespace AntonPavlov\PersonalSite\Models;

use AntonPavlov\PersonalSite\Base\Model;

/**
 * Модель для работы с очередью писем
 *
 * Получает, вставляет или удаляет запись в очередь писем
 *
 * @package AntonPavlov\PersonalSite
 *
 * @author Anton Pavlov <mail@antonpavlov.ru>
 *
 */
class MailModel extends Model
{

    /**
     * Сохраняет задание на отправку письма в БД
     *
     * @param string $toWhom email получателя
     * @param string $subject тема письма base64-encoded
     * @param string $body текст письма
     * @param string $headers headers (заголовки письма)
     *
     * @throws \Exception если не удалось записать данные в БД
     *
     * @return void
     */
    public function save($toWhom, $subject, $body, $headers)
    {
        $query = $this->initDB()->prepare
            ('INSERT INTO `mailingtasks` values 
                (
                    null,
                    :toWhom,
                    :subject,
                    :body,
                    :headers,
                    :toSend
                )'
            );  
        $query->bindParam(':toWhom', $toWhom, \PDO::PARAM_STR);
        $query->bindParam(':subject', $subject, \PDO::PARAM_STR);
        $query->bindParam(':body', $body, \PDO::PARAM_STR);
        $query->bindParam(':headers', $headers, \PDO::PARAM_STR);
        $query->bindParam(':toSend', Date('Y-m-d H:i:s'), \PDO::PARAM_STR);
        $result = $query->execute();

        if (!$result) {
            throw new \Exception('Ошибка постановки задания на отправку письма в очередь - 1');
        }
    }

    /**
     * Получает задания на отправку письма из БД
     *
     * @param int $mailsPerTime лимит отправки писем за один раз
     *
     * @throws \Exception если не удалось получить данные из БД
     *
     * @return array
     */
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

    /**
     * Удаляет запись из очереди писем
     *
     * @param int $mailId id записи-письма
     *
     * @throws \Exception если не удалось удалить данные из БД
     *
     * @return bool
     */
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