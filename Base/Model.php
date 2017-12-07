<?
namespace AntonPavlov\PersonalSite\Base;

use AntonPavlov\PersonalSite\ConfigDB;

class Model
{
    private $db;

    public function __construct()
    {
        $configDB = ConfigDB::get();
        $reg = Registry::init();
        $this->db = $reg->get('db');
        try {
            if (!isset($this->db)) {
                $this->db = new \PDO('mysql:host='.$configDB['host'].';dbname='.$configDB['dbName'], $configDB['dbUser'], $configDB['dbPassword']);
                $reg->set('db',$this->db);
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    
	public function initDB()
    {
        return $this->db;
    }
    
}