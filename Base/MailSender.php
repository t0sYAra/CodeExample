<?
namespace AntonPavlov\PersonalSite\Base;

use AntonPavlov\PersonalSite\Models\MailModel;

trait MailSender
{

	function putMailToQueue($toWhom, $subject, $body)
	{
		$body=wordwrap($body,200);
		$headers='From: mail@antonpavlov.ru'.PHP_EOL.
		'Reply-To: mail@antonpavlov.ru'.PHP_EOL.
		'Content-Type: text/plain; charset=UTF-8'.PHP_EOL.
		'MIME-Version: 1.0'. PHP_EOL.
		'X-Mailer: PHP/' . phpversion();

        $result = true;
        
        $newMail = new MailModel();
        $result = $newMail->save($toWhom,$subject,$body,$headers);

        return $result;
	}
}