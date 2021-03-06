<?php
namespace AntonPavlov\PersonalSite\Controllers;

use AntonPavlov\PersonalSite\Controllers\ErrorController;
use AntonPavlov\PersonalSite\Base\Controller;
use AntonPavlov\PersonalSite\Models\AudioModel;
use AntonPavlov\PersonalSite\Models\EntryModel;

/**
 * Контроллер, обрабатывающий запрос на загрузку аудио-файла
 *
 * @package AntonPavlov\PersonalSite
 *
 * @author Anton Pavlov <mail@antonpavlov.ru>
 *
 */
class AudioController extends Controller
{

    /**
     * Загружает аудио-файл
     *
     * @return void
     */
	function loadAudioFile()
	{
        
        $entryId = 0;
        $entryId = preg_replace("/^\/audio\/([1-9][0-9]{0,10})\/[1-9][0-9]{0,10}.mp3\/?$/siu","$1",$_SERVER['REQUEST_URI']);

        // получаем транслитерированные данные из БД
        try {
            $entr = new EntryModel();
            $entry = $entr->getTranslit($entryId);
        } catch (\Exception $e) {
            // ошибка во время запроса к БД
            (new ErrorController())->showAudPage404();
        }

        $path=__DIR__.'/../audio/'.sprintf("%05d",$entryId).'-'.substr($entry['translit'],0,50).'/';
        $fileName='001.mp3';
			
		if (file_exists($path.$fileName))
		{
			header('Content-Type: audio/mp3');
			readfile($path.$fileName);
            die;
		}
        
        (new ErrorController())->showAudPage404();
	}

	
}
