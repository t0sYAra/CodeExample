<?
namespace AntonPavlov\PersonalSite\Base;

use AntonPavlov\PersonalSite\Models\MailModel;

trait MailSender
{

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
        
        $newMail = new MailModel();
        try {
            $result = $newMail->save(trim($toWhom),trim($subject),$body,$headers);
        } catch (\Exception $e) {
            throw new \Exception('Ошибка постановки задания на отправку письма в очередь - 2');
        }

	}
}