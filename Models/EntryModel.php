<?
namespace AntonPavlov\PersonalSite\Models;

use AntonPavlov\PersonalSite\Base\Registry;
use AntonPavlov\PersonalSite\Base\DBStart;
use AntonPavlov\PersonalSite\Base\Model;

class EntryModel extends Model
{
    private $db;
    
    public function __construct()
    {
        $this->db = $this->initDB();
    }

    public function getMain($nameTranslitted)
    {
        $query = $this->db->prepare('select `entryid`, `zag`, `translit`, `epigraf`, `text`, `author`, `rating`, `published` from `entries` where `translit`=\''.$nameTranslitted.'\'&&`ifshow`=1 limit 0,1');
        $result = $query->execute();
        if ($result) {
            $resultsArr = $query->fetch(\PDO::FETCH_ASSOC);
        }
        
        if ((isset($resultsArr))&&(!empty($resultsArr))) {
            return $resultsArr;
        }
        
        return false;
    }
    
    public function getTranslit($entryId)
    {
        $query = $this->db->prepare('select `translit` from `entries` where `entryid`='.$entryId.' limit 0,1');
        $result = $query->execute();
        if ($result) {
            $resultsArr = $query->fetch(\PDO::FETCH_ASSOC);
        }
        
        if ((isset($resultsArr))&&(!empty($resultsArr))) {
            return $resultsArr;
        }
        
        return false;
    }
    
}