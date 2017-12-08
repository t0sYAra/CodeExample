<?php
namespace AntonPavlov\PersonalSite\Exceptions;

/**
 * Исключение на случай не найденное записи (по поисковому запросу)
 *
 * @package AntonPavlov\PersonalSite
 *
 * @author Anton Pavlov <mail@antonpavlov.ru>
 *
 */
class SearchStringNotFoundException extends \Exception
{
    /**
     * @var string $searchString строка, содержащая поисковый запрос
     */
    private $searchString = '';

    
    /**
     * Конструктор класса, сохраняющий в свойтсве класса поисковый запрос
     *
     * @param string $message сообщение об ошибке
     * @param string $searchString поисковый запрос
     *
     * @return void
     */
    public function __construct($message, $searchString)
    {
        $this->searchString = $searchString;
        parent::__construct($message);
    }
    
    /**
     * Возвращает поисковый запрос
     *
     *
     * @return string
     */
    public function getSearchString()
    {
        return $this->searchString;
    }
    
}
