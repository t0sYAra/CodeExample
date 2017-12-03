<?
namespace AntonPavlov\PersonalSite\Base;

class Model
{
    private $db;
    
    public function __construct()
    {
        $this->db = null;
    }
    
	public function initDB()
    {
        DBStart::init();
        $reg = Registry::init();
        $this->db = $reg->get('db');
        return $this->db;
    }
    
}