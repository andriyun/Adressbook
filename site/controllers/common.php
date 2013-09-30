<?php
class Common extends Controller
{
    function __construct($registry)
    {
        parent::__construct($registry);
		$this->cache = $registry->get('cache');
		$this->user = $registry->get('user');
		$this->document = $registry->get('document');
		$this->document->page_url = SITE_URL.'/'.$this->request->get['q'];
        $this->load_model('language_model');
		}

    function index()
    {
		$data = array();
		if ($this->user->isLogged()) {
			$data['isLogged'] = $this->user->isLogged();	
			$data['user'] = $this->user;
			$this->children(array('main_view' => 'contacts/index'));	
			$data['logged_block'] = 'login/logged_block';
			$data['left_sidebar'] = 'contacts/left_sidebar';
			} else  $data['main_view'] = 'login/login_form';  	

			
		$languages = $this->language_model->getLanguages();
		$this->view->languages = $languages;	
										
		if (!isset($this->session->data['errors'])) $this->session->data['errors'] = array();
		if (count($this->session->data['errors'])) {
			foreach($this->session->data['errors'] as $key=>$error) {
				$this->document->addMessage($error, 'error');
				unset($this->session->data['errors'][$key]);				
				}
			}
			
		if (!isset($this->session->data['messages'])) $this->session->data['messages'] = array();			
		if (count($this->session->data['messages'])) {
			foreach($this->session->data['messages'] as $key=>$message) {
				$this->document->addMessage($message);
				unset($this->session->data['messages'][$key]);				
				}

			}					
		
		$this->document->title_suffix = SITE_NAME;
		$this->document->keywords ='default keyword list';
		$this->document->description ='default description text';

		$this->document->addStyle('/css/bootstrap/css/bootstrap.min.css');		
		$this->document->addStyle('/css/style.css');
		
		
		$this->document->addScript('/js/jquery.js');	
		$this->document->addScript('/css/bootstrap/js/bootstrap.js');		
		$this->document->addScript('/js/common.js');	
		$this->document->addScript('/js/jquery.maskedinput.js');	
		
		$this->view->append_data($data);		
	}

	
	function login(){	
		$ignored = array(
			'index',
			'login',
			'login/forgot',
			);
			
		$q = isset($this->request->get['q'])?$this->request->get['q']:'index';
			$part = explode('/', $q);
			$q ='';
			if (isset($part[0])) if ($part[0]) $q .= $part[0];
			if (isset($part[1])) if ($part[1])  $q .= '/' . $part[1];
			if (!$this->user->isLogged()) {			
			if (!in_array($q,$ignored)){
				$this->js_redirect(SITE_URL.'/login');
				}
			}
		}
		
}

?>