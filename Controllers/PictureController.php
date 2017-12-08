<?php
namespace AntonPavlov\PersonalSite\Controllers;

use AntonPavlov\PersonalSite\Controllers\ErrorController;
use AntonPavlov\PersonalSite\Base\Controller;
use AntonPavlov\PersonalSite\Models\PictureModel;
use AntonPavlov\PersonalSite\Models\EntryModel;

/**
 * Контроллер, обрабатывающий запрос на загрузку картинки
 *
 * @package AntonPavlov\PersonalSite
 *
 * @author Anton Pavlov <mail@antonpavlov.ru>
 *
 */
class PictureController extends Controller
{
  
    /**
     * Загружает картинку
     *
     * @return void
     */  
	function showPic()
	{
        
        $entryId = 0;
        $picId = 0;
        $entryId = preg_replace("/^\/pics\/[01abcd]\/([1-9][0-9]{0,10})\/([1-9][0-9]{0,10}).(jpg|gif|png)\/?$/siu","$1",$_SERVER['REQUEST_URI']);
        $picId = preg_replace("/^\/pics\/[01abcd]\/([1-9][0-9]{0,10})\/([1-9][0-9]{0,10}).(jpg|gif|png)\/?$/siu","$2",$_SERVER['REQUEST_URI']);
        $pictureSize = preg_replace("/^\/pics\/([01abcd])\/([1-9][0-9]{0,10})\/([1-9][0-9]{0,10}).(jpg|gif|png)\/?$/siu","$1",$_SERVER['REQUEST_URI']);

        try {
            $pic = new PictureModel();
            $picture = $pic->get($entryId, $picId);
        } catch (\Exception $e) {
            // ошибка во время запроса к БД
            (new ErrorController())->showPicPage404();
        }

        // получаем транслитерированные данные из БД
        try {
            $entr = new EntryModel();
            $entry = $entr->getTranslit($entryId);
        } catch (\Exception $e) {
            // ошибка во время запроса к БД
            (new ErrorController())->showPicPage404();
        }

        if ($pictureSize == 1) {
            $path = __DIR__.'/../pics/'.sprintf("%05d",$entryId).'-'.substr($entry['translit'],0,50).'/';
        } else {
            $path = __DIR__.'/../pics/'.sprintf("%05d",$entryId).'-'.substr($entry['translit'],0,50).'/'.$pictureSize.'/';
        }
        
        $fileName = sprintf("%03d",$picId).'.'.$picture['type'];
        
        if (file_exists($path.$fileName)) {
            header('Content-Type: image/'.$picture['type']);
            readfile($path.$fileName);
            die;
        }
        
        (new ErrorController())->showPicPage404();
	}

	
}
