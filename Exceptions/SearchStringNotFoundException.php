<?php
namespace AntonPavlov\PersonalSite\Exceptions;

class SearchStringNotFoundException extends \Exception
{
    private $searchString = '';
    
    public function __construct($message, $searchString)
    {
        $this->searchString = $searchString;
        parent::__construct($message);
    }
    
    public function getSearchString()
    {
        return $this->searchString;
    }
    
}
