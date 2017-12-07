<?php
namespace AntonPavlov\PersonalSite\cron;

use AntonPavlov\PersonalSite\Models\SendMailModel;

function autoLoader($name)
{
    $name = preg_replace('/^AntonPavlov\\\\PersonalSite\\\\(.*)/siu', "$1", $name);
    $name = preg_replace('/\\\\/siu', "/", $name);
    $name = preg_replace('/(\/)+/siu', "/", $name);
    require_once __DIR__.'/../'.$name.'.php';
}

spl_autoload_extensions('.php');
spl_autoload_register('AntonPavlov\PersonalSite\cron\autoLoader');

mb_internal_encoding("UTF-8");

$referrer = '';
if (isset($_SERVER['REQUEST_URI'])) {
    $referrer = $_SERVER['REQUEST_URI'];
}

if ($referrer != '') {
    die;
}

$mailsPerTime = 10; // сколько писем за раз может отослать скрипт
$mailDB = new SendMailModel();
try {
    $mailsToSend = $mailDB->get($mailsPerTime);
    
    for ($i=0; $i < count($mailsToSend); $i++) {
        $mailsToSend[$i]['poluchateli'] = stripslashes($mailsToSend[$i]['poluchateli']);
        $mailsToSend[$i]['tema'] = stripslashes($mailsToSend[$i]['tema']);
        $mailsToSend[$i]['telopisma'] = stripslashes($mailsToSend[$i]['telopisma']);
        $mailsToSend[$i]['headers'] = stripslashes($mailsToSend[$i]['headers']);
        $mailsToSend[$i]['tema'] = '=?utf-8?B?'.base64_encode($mailsToSend[$i]['tema']).'?=';
		$mailAccepted = mail
            (
                $mailsToSend[$i]['poluchateli'],
                $mailsToSend[$i]['tema'],
                $mailsToSend[$i]['telopisma'],
                $mailsToSend[$i]['headers']
            );
        if ($mailAccepted) {
            try {
                $mailDB->deleteOneMail($mailsToSend[$i]['id']);
            } catch (\Exception $e) {
                // ничего не делаем
            }
        }
    }

} catch (\Exception $e) {
    // ничего не делаем
}
