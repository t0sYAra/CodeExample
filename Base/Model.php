<?php
namespace AntonPavlov\PersonalSite\Base;

use AntonPavlov\PersonalSite\ConfigDB;

/**
 * Класс-предок для всех моделей
 *
 * Класс-предок для всех моделей. Во время создания экземпляра класса получает данные для подключения к БД из конфига,
 * инициализирует реестр, проверяет, есть ли там ресурс для подключения к БД.
 * Если нет - создаёт и записывает его в реестр
 *
 * @package AntonPavlov\PersonalSite
 *
 * @author Anton Pavlov <mail@antonpavlov.ru>
 *
 */
class Model
{
        
    /**
     * @var resource $db ресурс для подключения к БД
     */
    private $db;

    /**
     * Конструктор класса
     *
     * Инициализирует подключение к БД
     *
     * @throws \PDOException если подключение к базе данных не удалось
     *
     * @return void
     */
    public function __construct()
    {
        $reg = Registry::init();
        try {
            $this->db = $reg->get('db');
        } catch (\Exception $e) {
            try {
                $configDB = ConfigDB::get();
                $this->db = new \PDO('mysql:host='.$configDB['host'].';dbname='.$configDB['dbName'], $configDB['dbUser'], $configDB['dbPassword']);
                $reg->set('db',$this->db);
            } catch (\PDOException $e) {
                throw new \PDOException('Во время подключения к базе данных возникла ошибка. Попробуйте повторить позже.');
            }
        }
    }

    /**
     * Возвращает ресурс для подключения к БД
     *
     * @return resource
     */
	public function initDB()
    {
        return $this->db;
    }
    
}