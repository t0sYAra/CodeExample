<?php
namespace AntonPavlov\PersonalSite\Base;

use AntonPavlov\PersonalSite\Base\Registry;

trait DBStart
{

    public function init()
    {
        $reg = Registry::init();
        $db = $reg->get('db');
        try {
            if (!isset($db)) {
                $db = new \PDO('mysql:host=localhost;dbname=ap', 'user', 'pass');
                $reg->set('db',$db);
            }
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    }

}