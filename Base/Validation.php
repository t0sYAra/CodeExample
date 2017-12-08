<?php
namespace AntonPavlov\PersonalSite\Base;

use AntonPavlov\PersonalSite\Base\Formatter;
use AntonPavlov\PersonalSite\Base\RandomHelper;

/**
 * Валидация даных, присланных из формы
 *
 * Получает данные из формы и:
 * 1. Очищает их от концевых и множественных пробельных символов
 * 2. Проверяет на соответствие правилам валидации, заданным в соответствующем контроллере - обработчике запроса
 * 3. Чистит html-теги
 * 4. Применяет функцию htmlentities
 *
 * @package AntonPavlov\PersonalSite
 *
 * @author Anton Pavlov <mail@antonpavlov.ru>
 *
 */
trait Validation
{

    /**
     * Основной метод, принимающий данные POST запроса, чистящий и валидирующий их
     *
     * @param array $formValues ассоциативный массив, задаваемый в соответствующем контроллере - обработчике запроса например:
     * $defaultFormValues = ['name' => ['name' => 'имя','value' => '','errors' => '','rules' => 'required|min:2|max:100|nohtml',
     * 'regExp' => '[\|]+','regExpContains' => 1,'regExpMessage' => 'не может содержать следующие символы: "`", "|"']];
     * 1. Очищает их от концевых и множественных пробельных символов
     * 2. Проверяет на соответствие правилам валидации, заданным в соответствующем контроллере - обработчике запроса
     * 3. Чистит html-теги
     * 4. Применяет функцию htmlentities
     *
     * @return array
     */
    public function cleanAndValidate($formValues)
    {
        foreach ($formValues as $firstKey => $secondKey) {
            $formValues[$firstKey]['value'] = $_POST[$firstKey];
        
            // первичная очистка
            $formValues[$firstKey]['value'] = self::cleanBefore($formValues[$firstKey]['value']);

            // валидация
            $formValues[$firstKey]['errors'] = self::validate($formValues[$firstKey]);

            // дополнительная очистка
            $formValues[$firstKey]['value'] = self::cleanAfter($formValues[$firstKey]['value']);
        
            // подготовка
            $formValues[$firstKey]['value'] = self::prepareToPublishing($formValues[$firstKey]['value']);
        }
        
        return $formValues;
    }

    /**
     * Сравнивает входные данные с правилами валидации, возвращает текстовые сообщения об ошибках (если что-то не так)
     *
     * @param array $valueArr ассоциативный массив, задаваемый в соответствующем контроллере - обработчике запроса (для каждого поля формы - свой)
     *
     * @return string
     */
    private function validate($valueArr)
    {
        $errorText = '';

        // проверяем обязательность заполнения
        if ((mb_strpos('required',$valueArr['rules'])!==0)&&($valueArr['value']=='')) {
            $error[] = 'Необходимо заполнить поле "'.$valueArr['name'].'"';
        }

        // проверяем минимальное значение
        if (preg_match('/min\:/siu',$valueArr['rules'])===1) {
            $howMuch = 0;
            $howMuch = preg_replace("/^(.*)min:([1-9][0-9]{0,10})(.*)$/siu","$2",$valueArr['rules']);
            if (mb_strlen($valueArr['value'])<$howMuch) {
                $error[] = 'Минимальная длина поля "'.$valueArr['name'].'": '.$howMuch.' '.Formatter::defineWordEnding($howMuch,'знак');
            }
        }
        
        // проверяем максимальное значение
        if (preg_match('/max\:/siu',$valueArr['rules'])===1) {
            $howMuch = 0;
            $howMuch = preg_replace("/^(.*)max:([1-9][0-9]{0,10})(.*)$/siu","$2",$valueArr['rules']);
            if (mb_strlen($valueArr['value'])>$howMuch) {
                $error[] = 'Максимальная длина поля "'.$valueArr['name'].'": '.$howMuch.' '.Formatter::defineWordEnding($howMuch,'знак');
            }
        }
       
        // проверяем обязательность заполнения
        if (($valueArr['value']!='')&&(preg_match('/nohtml/siu',$valueArr['rules'])===1)&&(strip_tags($valueArr['value'])!=$valueArr['value'])) {
            $error[] = 'Использовать html-теги в поле "'.$valueArr['name'].'" нельзя';
        }
        
        // проверяем соответствие регулярному выражению
        if (preg_match('/'.$valueArr['regExp'].'/siu',$valueArr['value'])==$valueArr['regExpContains']) {
            $error[] = 'Поле "'.$valueArr['name'].'" '.$valueArr['regExpMessage'];
        }
        
        // проверяем капчу
        if (preg_match('/captcha/siu',$valueArr['rules'])===1) {
            if (!isset($_SESSION['icndhcak'])) {
                $_SESSION['icndhcak'] = RandomHelper::getRandomNum(1000, 9999).'a';
            }
            if ($valueArr['value']!==$_SESSION['icndhcak']) {
                $error[] = 'Введите правильный код с картинки';
            }
            unset($_SESSION['icndhcak']);
        }

        
        if (isset($error)) {
            $errorText = implode(PHP_EOL,$error);
        }
        
        return $errorText;
    }

    /**
     * Удаляет концевые и множественные пробельные символы
     *
     * @param string $value обрабатываемые данные
     *
     * @return string
     */
    private function cleanBefore($value)
    {
        $value = self::stripTabs(trim($value));
        return $value;
    }

    /**
     * Удаляет html-теги
     *
     * @param string $value обрабатываемые данные
     *
     * @return string
     */
    private function cleanAfter($value)
    {
        $value = strip_tags($value);
        return $value;
    }

    /**
     * Применяет функцию htmlentities() к входным данным
     *
     * @param string $value обрабатываемые данные
     *
     * @return string
     */
    private function prepareToPublishing($value)
    {
        $value = htmlentities($value);
        return $value;
    }

    /**
     * Удаляет концевые и множественные пробельные символы
     *
     * @param string $text обрабатываемые данные
     *
     * @return string
     */
    private function stripTabs($text)
	{
		$text=preg_replace("/([^\t\f ]*)[\t\f ]+([^\t\f ]*)/smi","$1 $2",$text);
		$text=preg_replace("/([^ ]+)[ ]+([.,:!?;]+)/smi","$1$2",$text);
		$text=preg_replace("/([!;]+)([^ ]+)/smi","$1 $2",$text);
		$text=preg_replace("/(?:\r\n)(?:\r\n)(?:\r\n)+/smi","\r\n\r\n",$text);
		return $text;
	}

}