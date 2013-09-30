<?php
class Login extends Controller
{
    function __construct($registry)
    {
        parent::__construct($registry);
		$this->user = $registry->get('user');
		$this->session = $registry->get('session');
		$this->load_library('json');		
		
    }

    function index()
    {
		if ($this->user->isLogged()) $this->js_redirect(SITE_URL);		
		if (isset($this->request->post['submit'])){
			if (!$this->validateForm()) return;
			$login = $this->request->post['login'];
			$password = $this->request->post['password'];
			if (!$this->user->login($login,$password)) {
				$this->view->setHeader('error',$this->json->encode('Authorization error'));
				$this->view->index = 'ajax';
				return;
				}
			return $this->js_redirect(SITE_URL);	
		}
		$this->js_redirect(SITE_URL);		
	}
	
    function logout()
    {
		$this->user->logout();
		$this->js_redirect(SITE_URL);
	}
	
    function logged_block(){
		$this->view->index = 'login/logged_block';
	}
	
	function validateForm(){
		$errors = array();

		if (!$this->request->post['login']) {
			$errors[] = 'Enter login';
			}
			
		if (!$this->request->post['password']){
			$errors[] = 'Enter password';
			}				
				
		if (count($errors)) {
			$message = implode("\n",$errors);
			$this->view->setHeader('error',$this->json->encode($message));
			$this->view->index = 'ajax';
			return false;
			}
		return true;
		}	
  
}

?>