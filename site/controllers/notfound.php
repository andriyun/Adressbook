<?php
class Notfound extends Controller
{
    function __construct($registry)
    {
        parent::__construct($registry);		
    }

    function index()
    {  
		header("HTTP/1.0 404 Not Found");
		$this->view->heading_title = $this->language->text_notfound_header;
		$this->view->main_view = 'notfound/index';  
	}
    
    function children_notfound($template_var)
    {  
		$this->view->append_data(array($template_var => 'notfound/children'));  
	}
    
}

?>