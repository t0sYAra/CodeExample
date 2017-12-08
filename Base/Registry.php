<?php
namespace AntonPavlov\PersonalSite\Base;

/**
 * Класс реестра
 *
 * Класс реестра. Хранит все нужные данные на протяжении выполнения скрипта.
 * На основе шаблона Singletone
 *
 * @package AntonPavlov\PersonalSite
 *
 * @author Anton Pavlov <mail@antonpavlov.ru>
 *
 */
class Registry
{
            
    /**
     * @var object $instance экземпляр класса
     */
    private static $instance = null;
    
    /**
     * @var array $registry массив для хранения данных
     */
    private $registry = array(); 
 
    /**
     * Конструктор класса
     *
     * запрещает создание объектов
     *
     * @return void
     */
    private function __construct()
    {
    }
 
    /**
     * Инициализация единственного объекта класса
     *
     * @return void
     */
    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }
 
    /**
     * Записывает свойства
     *
     * @param string $key ключ
     * @param string $value значение
     *
     * @return void
     */
    public static function set($key, $value) {
        self::init()->registry[$key] = $value;
    }
  
    /**
     * Получает свойство
     *
     * @param string $key ключ
     *
     * @throws \Exception если ключ не был установлен ранее
     *
     * @return mixed
     */
    public static function get($key) {
        if (!isset(self::init()->registry[$key])) {
            throw new \Exception('В реестре нет записей с данным ключом');
        }
        return self::init()->registry[$key];
    }
 
    /**
     * Запрещает wakeup
     *
     * @return void
     */
    private function __wakeup()
    {
    }
 
    /**
     * Запрещает clone
     *
     * @return void
     */
    private function __clone()
    {
    }

}