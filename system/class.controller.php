<?php
/**
 * Класс контролер.
 * Реализует всю логику приложения.
 * Здесь создаются объекты разных классов, например моделей.
 * Класс абстрактный, поэтому он наследуется.
 */ 
abstract class Controller extends Load
{
    protected $view;
    protected $db;
	protected $registry;	
    
    function __construct($registry)
    {		
		parent::__construct($registry);
		
		$this->view = $registry->get('view');
        $this->language = $registry->get('language');
        $this->db = $registry->get('db');
		$this->session = $registry->get('session');
		$this->request = $registry->get('request');		
		$this->document = $registry->get('document');		
    }
	
	
  	public function children($children_route_list) {			
		foreach ($children_route_list as $key=>$children_route) {		
		$action = new Action($this->registry,$children_route);
			while ($action) {
				$file   = $action->getFile();
				$class  = $action->getClass();
				$method = $action->getMethod();
				$args   = $action->getArgs();
				$action = '';
				if (file_exists($file)) {
					 if (!$this->load_controller($class, $method, $args)) $action = new Action($this->registry,$children_route);ERROR_ROUTE . DIRECTORY_SEPARATOR . 'children';
				} else $action = new Action($this->registry,ERROR_ROUTE . DIRECTORY_SEPARATOR . 'children_notfound/'.$key);
			}
		}
  	}

  	public function error404() {				
		$action = new Action($this->registry,ERROR_ROUTE);
		$file   = $action->getFile();
		$class  = $action->getClass();
		$method = $action->getMethod();
		$args   = $action->getArgs();
		$this->load_controller($class, $method, $args);	
  	}
	
	function redirect($url, $status = 302) {
		header('Status: ' . $status);
		header('Location: ' . str_replace('&amp;', '&', $url));
		exit();
	}

	function js_redirect($url,$selector = false) {
		$ajaxloadurl = $url;
		if($ajaxloadurl == SITE_URL) $ajaxloadurl .= '/'.DEFAULT_AJAX_ROUTE;
		if (DEVEL) echo	'<script type="text/javascript">alert("Redirect to '.$url.'");</script>';
		echo '<script type="text/javascript">
			if (document.getElementById("main-container")) {
				_ajax.load("'.$ajaxloadurl.'"'.($selector?(',\''.$selector.'\''):'').'); }
				else {
					window.location.href ="'.$url.'"
					};
			</script>';
		exit();
	}	
}
