<?php
namespace AntonPavlov\PersonalSite\Controllers;

use AntonPavlov\PersonalSite\Base\Controller;
use AntonPavlov\PersonalSite\Base\Formatter;
use AntonPavlov\PersonalSite\Models\EntryModel;
use AntonPavlov\PersonalSite\Controllers\ErrorController;
use AntonPavlov\PersonalSite\Base\Validation;
use AntonPavlov\PersonalSite\Base\MailSender;
use AntonPavlov\PersonalSite\Exceptions\Page404Exception;
use AntonPavlov\PersonalSite\Exceptions\CommentNotFoundException;

/**
 * Контроллер, обрабатывающий запрос на загрузку одной записи и комментариев к ней
 *
 * @package AntonPavlov\PersonalSite
 *
 * @author Anton Pavlov <mail@antonpavlov.ru>
 *
 */
class EntryController extends Controller
{

    /**
     * Возвращает конфигурационный массив для добавления комментария
     *
     * Возвращает конфигурационный массив для каждого поля формы (нужен, если со страницы отправлен POST-запрос).
     * В массиве содержатся значения полей формы по умолчанию, правила валидации данных и сообщения об ошибках.
     * Для правил ($defaultFormValues[$n]['rules']) возможны следующие варианты: required, min:_, max:_, nohtml (указываются через запятую)
     * Также можно задать RegExp шаблон ($defaultFormValues[$n]['regExp']) для проверки на совпадение ($defaultFormValues[$n]['regExpContains'] = 1)
     * или несовпадение ($defaultFormValues[$n]['regExpContains'] = 0)
     *
     * @return array
     */
    private function getDefaultAddCommentFormValues()
    {
        $defaultFormValues = [
            'parentCommentsId' => [
                'name' => '',
                'value' => '0',
                'errors' => '',
                'rules' => 'required',
                'regExp' => '[0-9]{1,10}',
                'regExpContains' => 0,
                'regExpMessage' => 'может быть только числом'
            ],
            'commentsAuthor' => [
                'name' => 'имя',
                'value' => '',
                'errors' => '',
                'rules' => 'required|min:2|max:100|nohtml',
                'regExp' => '[`\|]+',
                'regExpContains' => 1,
                'regExpMessage' => 'не может содержать следующие символы: "`", "|"'
            ],
            'commentsText' => [
                'name' => 'комментарий',
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
        return $defaultFormValues;
    }

    /**
     * Возвращает конфигурационный массив для добавления записи
     *
     * Возвращает конфигурационный массив для каждого поля формы (нужен, если со страницы отправлен POST-запрос).
     * В массиве содержатся значения полей формы по умолчанию, правила валидации данных и сообщения об ошибках.
     * Для правил ($defaultFormValues[$n]['rules']) возможны следующие варианты: required, min:_, max:_, nohtml (указываются через запятую)
     * Также можно задать RegExp шаблон ($defaultFormValues[$n]['regExp']) для проверки на совпадение ($defaultFormValues[$n]['regExpContains'] = 1)
     * или несовпадение ($defaultFormValues[$n]['regExpContains'] = 0)
     *
     * @return array
     */
    private function getDefaultAddFormValues()
    {
        $defaultFormValues = [
            'heading' => [
                'name' => 'заголовок',
                'value' => '',
                'errors' => '',
                'rules' => 'required|min:2|max:250|nohtml',
                'regExp' => '[`\|]+',
                'regExpContains' => 1,
                'regExpMessage' => 'не может содержать следующие символы: "`", "|"'
            ],
            'epigraph' => [
                'name' => 'эпиграф',
                'value' => '',
                'errors' => '',
                'rules' => 'max:1000|nohtml',
                'regExp' => '[`\|]+',
                'regExpContains' => 1,
                'regExpMessage' => 'не может содержать следующие символы: "`", "|"'
            ],
            'text' => [
                'name' => 'текст',
                'value' => '',
                'errors' => '',
                'rules' => 'required|min:2|max:10000|nohtml',
                'regExp' => '[`\|]+',
                'regExpContains' => 1,
                'regExpMessage' => 'не может содержать следующие символы: "`", "|"'
            ],
            'tags' => [
                'name' => 'теги',
                'value' => '',
                'errors' => '',
                'rules' => 'required|min:2|max:1000|nohtml',
                'regExp' => '^([-_ 0-9a-zа-яё]{2,250}[\r\n]*)+$',
                'regExpContains' => 0,
                'regExpMessage' => 'могут быть только из букв, цифр, пробела и знаков тире и подчёркивания'
            ],
            'accessCode' => [
                'name' => 'код',
                'value' => '',
                'errors' => '',
                'rules' => 'required|access',
                'regExp' => '^[-_a-z0-9]{1,16}$',
                'regExpContains' => 0,
                'regExpMessage' => 'введён неправильно'
            ]
        ];
        return $defaultFormValues;
    }

    /**
     * Добавляет новую запись в БД
     *
     * Добавляет новую запись в блог.
     * Обрабатывает данные, отправленные из формы добавления записи.
     * Если пройдена валидация - сохраняет запись в БД.
     *
     * @return void
     */
	public function addEntry()
	{
        // значения по умолчанию
        $title = 'Новая запись';
        $descr = 'Публикация новой записи в блоге';
        
        // обрабатываем возможно присланную запись
        $defaultFormValues = $this->getDefaultAddFormValues();
        $formValues = $defaultFormValues;
        $status = '';
        $errors = '';
        $posted = false;
        if ((isset($_POST['heading']))&&(isset($_POST['text']))&&(isset($_POST['tags']))&&(isset($_POST['accessCode']))) {
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
            
            // записываем в БД
            try {
                
                // форматируем теги
                $formValues['tags']['value'] = preg_replace("/[\r\n]+/smiu", PHP_EOL, $formValues['tags']['value']);
                if ($formValues['tags']['value'] !== '') {
                    $tags = explode(PHP_EOL, $formValues['tags']['value']);
                    $formValues['tags']['value'] = '#'.implode(', #', $tags);
                }

                // транслитерируем заголовок
                $translittedHeading = Formatter::translit($formValues['heading']['value']);
                
                // записываем заголовок и теги в основной текст (для упрощения поиска)
                $formValues['text']['value'] = 'zag111'.$formValues['heading']['value'].'zag999'.PHP_EOL.PHP_EOL.$formValues['text']['value'].PHP_EOL.PHP_EOL.'tgg111'.$formValues['tags']['value'].'tgg999';
                
                // сохраняем
                $entry = new EntryModel();
                $saveResult = $entry->saveEntry
                    (
                        $translittedHeading,
                        $formValues['heading']['value'], 
                        $formValues['epigraph']['value'], 
                        $formValues['text']['value'], 
                        'Антон Павлов',
                        0,
                        1
                    );

                $status = 'Запись опубликована. Спасибо';
                $formValues = $defaultFormValues;
            } catch (\Exception $e) {
                $errors = 'Произошла ошибка во время записи в базу данных. Попробуйте добавить запись позже.';
            }
        }
        
		$this->view->includeViewFile
        (
            'addentry.php', // файл с контентом
            'mainTemplate.php', // шаблон
            '', // js-files
            '', // prefetch
            [
                'title' => $title, // здесь и далее - дополнительные данные
                'description' => $descr,
                'heading' => $formValues['heading']['value'],
                'text' => $formValues['text']['value'],
                'tags' => $formValues['tags']['value'],
                'accessCode' => $formValues['accessCode']['value'],
                'errors' => $errors,
                'status' => $status,
            ]
        );
	}

    /**
     * Выводит на экран запись и комментарии к ней
     *
     * Выводит на экран запись и комментарии к ней.
     * Обрабатывает данные, отправленные из формы добавления комментария.
     * Если пройдена валидация - сохраняет комментарий в БД и отправляет письмо с сообщением о новом комментарии.
     *
     * @return void
     */
	public function showEntry()
	{
        // значения по умолчанию
        $title = 'Запись в блоге';
        $descr = 'Содержание записи в блоге';

        // получаем транслитерированное название из строки запроса
        $nameTranslitted = preg_replace("/^\/(menedzher_internet-proektov|stroitel|pilot|trener|spasatel|specialist_po_fito-svetu|mebelshchik|chlen_pravleniya_tszh|kandidat_ehkonomicheskih_nauk)\/?$/siu","$1",$_SERVER['REQUEST_URI']);
        if ($nameTranslitted === $_SERVER['REQUEST_URI']) {
            $nameTranslitted = preg_replace("/^\/blog\/([-_0-9a-zA-Z]{2,255})\/?$/siu","$1",$_SERVER['REQUEST_URI']);
        }
        
        // получаем теги из всех записей
        try {
            $entry = new EntryModel();
            $entryArr = $entry->getMain($nameTranslitted);
        } catch (Page404Exception $e) {
            (new ErrorController())->showPage404();
        } catch (\Exception $e) {
            $this->view->includeViewFile
                (
                    'errorTemplate.php', // файл с контентом
                    'mainTemplate.php', // шаблон
                    '', // js-files
                    '', // prefetch
                    [
                        'title' => 'Ошибка', // здесь и далее - дополнительные данные
                        'description' => 'Во время загрузки страницы произошла ошибка',
                        'zag' => 'Ошибка',
                        'errors' => $e->getMessage()
                    ]
                );
            die;
        }
        
        $entryArr = $this->cleanEntryParts($entryArr);
        $additionalJStext = $this->preparePictureArrForJS($entryArr['entryid'],$entryArr['text']);

        $published = Date('j',strtotime($entryArr['published'])).' '.Formatter::getMonthInRussian(Date('m',strtotime($entryArr['published'])));
        $title = $entryArr['zag'];
        
        // обрабатываем возможно присланный комментарий
        $defaultFormValues = $this->getDefaultAddCommentFormValues();
        $formValues = $defaultFormValues;
        $status = '';
        $errors = '';
        $posted = false;
        if ((isset($_POST['parentCommentsId']))&&(isset($_POST['commentsAuthor']))&&(isset($_POST['commentsText']))&&(isset($_POST['cnghtdbspc']))&&(isset($_SESSION['icndhcak']))) {
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
            
            // вычисляем уровень вложенности
            $levelId = 0;
            if ($formValues['parentCommentsId']['value']!=0)
			{
                try {
                    $levelArr = $entry->getlevelId($entryArr['entryid'], $formValues['parentCommentsId']['value']);
                    $levelId = $levelArr['levelid'] + 1;
                } catch (\Exception $e) {
                    // не очень принципиально, оставляем дефолтный уровень вложенности = 0
                }
            }
            
            // записываем в БД
            try {
                $saveResult = $entry->saveComment
                    (
                        $entryArr['entryid'], 
                        $formValues['parentCommentsId']['value'], 
                        $levelId, 
                        $formValues['commentsAuthor']['value'], 
                        $formValues['commentsText']['value']
                    );
                
                // подгатавливаем текст письма
                $mailText = $formValues['commentsAuthor']['value'].' оставил(а) комментарий на странице'.PHP_EOL.PHP_EOL.'
                http://antonpavlov.ru/blog/'.$entryArr['translit'].'#comments:'.PHP_EOL.PHP_EOL.'
                '.$formValues['commentsText']['value'];

                // ставим письмо в очередь
                try {
                    $mailresult = MailSender::putMailToQueue
                    (
                        'mail@antonpavlov.ru',
                        'Новый коммент на AntonPavlov.ru',
                        $mailText
                    );
                } catch (\Exception $e) {
                    // не удалось отправить письмо, ничего не делаем
                }
                
                $status = 'Комментарий принят. Спасибо';
                $formValues = $defaultFormValues;
            } catch (\Exception $e) {
                $errors = 'Произошла ошибка во время записи в базу данных. Попробуйте добавить комментарий позже.';
            }
        }
        
        // дерево комментариев
        $comments = false;

        try {
            list($ammount, $commentsSerialized) = $this->getCommentsTree($entry, 0, $entryArr['entryid'], '', 0);
            $comments = explode('Hn2gxbdnm_15-jnJ',$commentsSerialized);
            array_shift($comments);
            for ($i = 0; $i < count($comments); $i++) {
                $comments[$i] = unserialize($comments[$i]);
            }
        } catch (CommentNotFoundException $e) {
            // комментариев нет
        } catch (\Exception $e) {
            // ошибки при запросах в БД
        }

		$this->view->includeViewFile
        (
            'entry.php', // файл с контентом
            'mainTemplate.php', // шаблон
            'comments.js', // js-files
            '', // prefetch
            [
                'title' => $title, // здесь и далее - дополнительные данные
                'description' => $descr,
                'entryid' => $entryArr['entryid'],
                'zag' => $entryArr['zag'],
                'epigraf' => $entryArr['epigraf'],
                'text' => $entryArr['text'],
                'published' => $published,
                'additionalJStext' => $additionalJStext,
                'epigraf' => $entryArr['epigraf'],
                'actionPage' => trim($_SERVER['REQUEST_URI']," \t\n\r\0\x0B\\\/"),
                'parentCommentsId' => $formValues['parentCommentsId']['value'],
                'commentsAuthor' => $formValues['commentsAuthor']['value'],
                'commentsText' => $formValues['commentsText']['value'],
                'errors' => $errors,
                'status' => $status,
                'comments' => $comments
            ]
        );
	}

    /**
     * Рекурсивно получает дерево комментариев
     *
     * Рекурсивно получает дерево комментариев. Возвращает массив, где его элементы:
     * [0] - счётчик обработанных комментариев,
     * [1] - сериализованная строка с уже полученными из БД комментариями
     *
     * @param resource $db ресурс для подключения к БД
     * @param int $parentCommentsId id родительского комментария
     * @param int $parentEntryId id родительской записи
     * @param string $serialized упакованные в строку массивы параметров каждого комментария (автор, текст и т.п.)
     * @param int $ammount счётчик обработанных комментариев
     *
     * @throws \Exception если не удалось получить данные из БД
     *
     * @return array
     */
	function getCommentsTree($db, $parentCommentsId, $parentEntryId, $serialized, $ammount)
	{
        // получаем комментарии текущего уровня вложенности
        try {
            $comments = $db->getComments($parentCommentsId, $parentEntryId);
            for ($i = 0; $i < count($comments); $i++) {
                // отступ
                $comments[$i]['margin'] = 20 * ($comments[$i]['levelid']-15*(floor($comments[$i]['levelid']/15)));
                
                // стиль
                if (fmod($ammount, 2) == 0) {
                    $comments[$i]['cssStyle'] = 'comment1';
                } else {
                    $comments[$i]['cssStyle'] = 'comment2';
                }
                
                
                // дата публикации
                $comments[$i]['published'] = Date('H:i, j',strtotime($comments[$i]['published'])).' '.Formatter::getMonthInRussian(Date('m',strtotime($comments[$i]['published']))).' '.Date('Y',strtotime($comments[$i]['published']));
                
                // счётчик комментариев
                $ammount++;

                $serialized .= 'Hn2gxbdnm_15-jnJ'.serialize($comments[$i]);
                
                // углубляемся...
                try {
                    list($ammount, $tempSerialized) = $this->getCommentsTree($db, $comments[$i]['parentCommentsId'], $parentEntryId, $comments, $ammount);
                    if ($tempSerialized) {
                        $serialized .= $tempSerialized;
                    }
                } catch (CommentNotFoundException $e) {
                    // ничего не делаем
                } catch (\Exception $e) {
                    // возвращаемся на уровень выше
                    throw new \Exception('Ошибка обработки запроса к БД - 4');
                }
            }
        } catch (CommentNotFoundException $e) {
            // ничего не делаем
        } catch (\Exception $e) {
            // возвращаемся на уровень выше
            throw new \Exception('Ошибка обработки запроса к БД - 3');
        }
        
        $arr[0] = $ammount;
        $arr[1] = $serialized;
        
        return $arr;
	}

    /**
     * Генерирует js-массив с данными обо всех картинках в записи
     *
     * Генерирует js-массив с данными обо всех картинках в записи,
     * а также другие переменные, необходимые для а) отображения крупных картинок
     * при щелчке по картинке в записи; б) навигации между крупными картинками
     *
     * @param int $entryId id родительской записи
     * @param string $text текст записи
     *
     * @return string
     */
    function preparePictureArrForJS($entryId,$text)
    {
        // загружаем данные о всех картинках в массив
        preg_match_all("/pics\/[01abcd]\/[0-9]+\/([0-9]+).(?:jpg|gif|png)\" class=\"pictext\"/smiu",$text,$pictures,PREG_SET_ORDER);
        $additionalJStext = 'var pics = new Array(';
        for ($o = 0; $o < count($pictures); $o++) {
            $additionalJStext.=', "'.$pictures[$o][1].'"';
        }
        unset($pictures);
        $additionalJStext = '<script language="javascript" type="text/javascript" charset="UTF-8">
		'.substr($additionalJStext, 0, 21).substr($additionalJStext, 23).');
		var beforepic = 0;
		var arrindex = 0;
		var tekpic = pics[arrindex];
		var afterpic = pics[1];
		var entryid = '.$entryId.';
		</script>
        ';
        
        return $additionalJStext;
    }

    /**
     * Обрабатывает заголовок, эпиграф и текст записи
     *
     * Обрабатывает заголовок, эпиграф и текст записи.
     * Чистит лишние переводы строк, обрамляет эпиграф div'ами,
     * конвертирует теги в тексте из внутренних в html-теги
     *
     * @param array $entryArr массив с данными записи - заголовком, эпиграфом, текстом и т.п.
     *
     * @return array
     */
    function cleanEntryParts($entryArr)
    {
        $entryArr['zag']=trim(strip_tags($entryArr['zag']));
        $entryArr['zag']=preg_replace("/[\r\n\t]+/smiu"," ",$entryArr['zag']);
        $entryArr['epigraf']=trim(strip_tags($entryArr['epigraf']));
        $entryArr['epigraf']=preg_replace("/\r\n\r\n(?:\r\n)+/smiu","\r\n\r\n",$entryArr['epigraf']);
        $entryArr['epigraf']=preg_replace("/\r\n/smiu","<br>\r\n",$entryArr['epigraf']);

        if ($entryArr['epigraf']!='')
        {
            $entryArr['epigraf']='<div class="epigraf1"></div><div class="epigraf2">'.$entryArr['epigraf'].'</div>';
        }
	
        $entryArr['text'] = Formatter::convertPlainTagsToHtmlTags('abzac1',$entryArr['text'],$entryArr['entryid']);
        $entryArr['text'] = preg_replace("/(([0-9]+)\/([0-9]+)\.(jpg|gif|png)\" class=\"pictext\")/smiu","$1 onClick=\"showpic($2,$3,'$4');\"",$entryArr['text']);

        return $entryArr;
    }

}
