<?
namespace AntonPavlov\PersonalSite\Models;

use AntonPavlov\PersonalSite\Base\Registry;
use AntonPavlov\PersonalSite\Base\DBStart;
use AntonPavlov\PersonalSite\Base\Model;

class PictureModel extends Model
{
    private $db;
    
    public function __construct()
    {
        $this->db = $this->initDB();
    }

    public function get($entryId, $picId)
    {
        $query = $this->db->prepare('select `picid`, `type` from `pics` where `entryid`='.$entryId.'&&`picid`=\''.sprintf("%03d",$picId).'\' limit 0,1');
        $result = $query->execute();
        if ($result) {
            $picture = $query->fetch(\PDO::FETCH_ASSOC);
        }
        
        if ((isset($picture))&&(!empty($picture))) {
            return $picture;
        }
        
        return false;
    }
    
}