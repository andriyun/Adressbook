<?php
class Index extends Controller
{
    function __construct($registry)
    {
        parent::__construct($registry);
		$this->user = $registry->get('user');

    }

    function index()
    {  		
		$this->container();	
		$this->view->index = 'index';	
		$this->view->container = 'index/container';			
	}
    function container(){
		$this->view->index = 'index/container';
	}
    
}

?>