<?php
namespace AntonPavlov\PersonalSite\Base;

use AntonPavlov\PersonalSite\Models\MailModel;

/**
 * Постановка письма в очередь рассылки (в БД)
 *
 * Форматирует текст, устанавливает headers и вставляет письмо в очередь рассылки
 *
 * @package AntonPavlov\PersonalSite
 *
 * @author Anton Pavlov <mail@antonpavlov.ru>
 *
 */
trait MailSender
{

    /**
     * Форматирует текст, устанавливает headers и вставляет письмо в очередь рассылки
     *
     * @param string $toWhom email получателя
     * @param string $subject тема письма
     * @param string $body текст письма
     *
     * @throws \Exception если не удалось вставить запись о письме к отправке в БД
     *
     * @return string
     */
	function putMailToQueue($toWhom, $subject, $body)
	{
        $body = preg_replace("/^\s+(.*)\s+$/miu", "$1", $body);
        $body = wordwrap($body,200);
		$headers = 'From: mail@antonpavlov.ru'.PHP_EOL.
		'Reply-To: mail@antonpavlov.ru'.PHP_EOL.
		'Content-Type: text/plain; charset=UTF-8'.PHP_EOL.
		'MIME-Version: 1.0'. PHP_EOL.
		'X-Mailer: PHP/' . phpversion();

        $result = true;

        try {
            $newMail = new MailModel();
            $result = $newMail->save(trim($toWhom),trim($subject),$body,$headers);
        } catch (\Exception $e) {
            throw new \Exception('Ошибка постановки задания на отправку письма в очередь - 2');
        }

	}
}