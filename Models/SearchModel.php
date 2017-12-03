<?
namespace AntonPavlov\PersonalSite\Models;

use AntonPavlov\PersonalSite\Base\Registry;
use AntonPavlov\PersonalSite\Base\DBStart;
use AntonPavlov\PersonalSite\Base\Model;

class SearchModel extends Model
{
    private $db;
    
    public function __construct()
    {
        $this->db = $this->initDB();
    }

    public function get()
    {
        $query = $this->db->prepare('select `text` as `tags` from `entries` limit 0,10000');
        $result = $query->execute();
        if ($result) {
            $textArr = $query->fetchAll(\PDO::FETCH_NUM);
        }
        
        return $textArr;
    }
    
}