<?php
namespace AntonPavlov\PersonalSite\Controllers;

use AntonPavlov\PersonalSite\Base\Controller;
use AntonPavlov\PersonalSite\Base\Validation;
use AntonPavlov\PersonalSite\Base\MailSender;

class ContactsController extends Controller
{

	function indexAction()
	{	
        $defaultFormValues = [
            'name' => [
                'name' => 'имя',
                'value' => '',
                'errors' => '',
                'rules' => 'required|min:2|max:100|nohtml',
                'regExp' => '[`\|]+',
                'regExpContains' => 1,
                'regExpMessage' => 'не может содержать следующие символы: "`", "|"'
            ],
            'email' => [
                'name' => 'email',
                'value' => '',
                'errors' => '',
                'rules' => 'required|min:6|max:200|nohtml',
                'regExp' => '^[a-zA-Z0-9][-_.a-zA-Z0-9]*@[a-zA-Z0-9][-_.a-zA-Z0-9]*\.(?:ac|ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cat|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|info|int|io|iq|ir|is|it|je|jm|jo|jobs|jp|ke|kg|kh|ki|km|kn|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mobi|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tel|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|travel|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)?$',
                'regExpContains' => 0,
                'regExpMessage' => 'может состоять только из латинских букв, цифр, точек, тире, собаки (@) и знака нижнего подчёркивания'
            ],
            'text' => [
                'name' => 'сообщение',
                'value' => '',
                'errors' => '',
                'rules' => 'required|min:2|max:10000|nohtml',
                'regExp' => '[`\|]+',
                'regExpContains' => 1,
                'regExpMessage' => 'не может содержать следующие символы: "`", "|"'
            ],
            'cnghtdbspc' => [
                'name' => 'код',
                'value' => '',
                'errors' => '',
                'rules' => 'required|captcha',
                'regExp' => '[0-9]{5}',
                'regExpContains' => 0,
                'regExpMessage' => 'может состоять только из 5 цифр'
            ]
        ];


        // принимаем данные из формы, если они были посланы
        $status = '';
        $errors = '';
        $posted = false;
        if ((isset($_POST['name']))&&(isset($_POST['email']))&&(isset($_POST['text']))&&(isset($_POST['cnghtdbspc']))&&(isset($_SESSION['icndhcak']))) {
            $posted = true;
            
            // проверяем и очищаем данные
            $formValues = Validation::cleanAndValidate($defaultFormValues);
            
            // склеиваем ошибки
            foreach ($formValues as $firstKey => $secondKey) {
                $errors .= PHP_EOL.$formValues[$firstKey]['errors'];
            }
            $errors = nl2br(trim(preg_replace('/['.PHP_EOL.']+/miu',PHP_EOL,$errors)));
        }
        
        if (($posted)&&($errors==='')) {
            // посланные данные в порядке, сохраняем в БД
            
            // подгатавливаем текст письма
            $mailText = $formValues['name']['value'].' ('.$formValues['email']['value'].') оставил(а) Вам сообщение:'.PHP_EOL.PHP_EOL.$formValues['text']['value'];
            
            // ставим письмо в очередь
            $mailresult = MailSender::putMailToQueue
            (
                'mail@antonpavlov.ru',
                'Новое сообщение с сайта AntonPavlov.ru',
                $mailText
            );
            
            if ($mailresult) {
                // обнуляем исходные данные
                $formValues = $defaultFormValues;
                $status = 'Сообщение отправлено. Спасибо';
            }
            else {
                $errors = 'Произошла техническая ошибка. Попробуйте отправить сообщение позже';
            }
            
        }
        
    
    
		$this->view->includeViewFile
        (
            'contacts.php', // файл с контентом
            'mainTemplate.php', // шаблон
            '', // js-files
            '', // prefetch
            [
                'title' => 'Контакты. Как связаться с Антоном Павловым', // здесь и далее - дополнительные данные
                'description' => 'Форма для отправки сообщения Антону Павлову. Контакты - email',
                'name' => $formValues['name']['value'],
                'email' => $formValues['email']['value'],
                'text' => $formValues['text']['value'],
                'errors' => $errors,
                'status' => $status
            ]
        );
	}
}
