<?
namespace AntonPavlov\PersonalSite\Base;

class Controller
{
	protected $model;
	protected $view;
	
	function __construct()
	{
        session_start();
        $this->view = new View();
	}

}