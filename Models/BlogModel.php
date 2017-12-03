<?
namespace AntonPavlov\PersonalSite\Models;

use AntonPavlov\PersonalSite\Base\Registry;
use AntonPavlov\PersonalSite\Base\DBStart;
use AntonPavlov\PersonalSite\Base\Model;

class BlogModel extends Model
{
    private $db;
    
    public function __construct()
    {
        $this->db = $this->initDB();
    }

    public function getMain($begin,$end,$params)
    {
        // формируем условие запроса
        $uslfield='';
        $usl[]='`ifshow`=1';
        $uslkol=0;
        
        if ($params['year']!='') {
            $usl[]='(DATE_FORMAT(`published`, "%Y")='.$params['year'].')';
            $uslkol++;
        }
        if ($params['tags']!='') {
            $params['tags']=trim(strip_tags($params['tags']));
            $usl[]='`text` regexp "[^\(]#'.mb_strtolower(rawurldecode($params['tags'])).'"';
            $uslkol++;
        }
        if ($params['search']!='') {
            $params['search']=trim(strip_tags($params['search']));
            $usl[]='`text` regexp "'.mb_strtolower(rawurldecode($params['search'])).'"';
            $uslkol++;
        }
        $uslfield=' where '.implode('&&', $usl);

        $orderusl=' order by `published` desc';
        if ($params['sort'] == 'best') {
            $orderusl=' order by `rating` desc, `published` desc';
        }

        
        $queryCount = $this->db->prepare('select count(`entryid`) as `kol` from `entries`'.$uslfield.' limit 0,1');
        $resultCount = $queryCount->execute();
        if ($resultCount) {
            $resultsCountArr = $queryCount->fetch(\PDO::FETCH_ASSOC);
        }
        
        if ((!isset($resultsCountArr))||(empty($resultsCountArr))) {
            return $false;
        }
            
        $queryEntries = $this->db->prepare('select `entryid`, `zag`, `translit`, `text`, `author`, `published` from `entries`'.$uslfield.$orderusl.' limit '.($begin-1).','.($end-$begin+1));
        $resultEntries = $queryEntries->execute();
        if ($resultEntries) {
            $resultsEntriesArr = $queryEntries->fetchAll(\PDO::FETCH_ASSOC);
        }
        
        if ((!isset($resultsEntriesArr))||(empty($resultsEntriesArr))) {
            return $false;
        }
        
        $resultsEntriesArr[0]['kolstrok'] = $resultsCountArr['kol'];
        
        return $resultsEntriesArr;
    }
        
    public function getPicture($entryId)
    {
        $query = $this->db->prepare('select `picid`, `type`, `ifmain` from `pics` where `entryid`='.$entryId.' order by `ifmain` desc, `picid` asc limit 0,1');
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