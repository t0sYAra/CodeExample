<?
namespace AntonPavlov\PersonalSite\Models;

use AntonPavlov\PersonalSite\Base\Registry;
use AntonPavlov\PersonalSite\Base\DBStart;
use AntonPavlov\PersonalSite\Base\Model;

class MailModel extends Model
{
    private $db;
    
    public function __construct()
    {
        $this->db = $this->initDB();
    }

    public function save($toWhom, $subject, $body, $headers)
    {
        $query = $this->db->prepare('INSERT INTO `mailingtasks` values (null,\''.$toWhom.'\',\''.$subject.'\',\''.$body.'\',\''.$headers.'\',\''.Date('Y-m-d H:i:s').'\')');  
        $result = $query->execute();
        
        return $result;
    }
    
}