<?php
namespace AntonPavlov\PersonalSite\Models;

use AntonPavlov\PersonalSite\Base\Model;

class MailModel extends Model
{

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
    
}