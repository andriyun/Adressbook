<?php
final class User {
	private $user_id;
	private $user_group_id;
	private $user_email;
	private $user_name;
  	private $permission = array();

  	public function __construct($registry) {
		
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');
		
    	if (isset($this->session->data['user_id'])) {
			$user_query = $this->db->select("SELECT * FROM " . DB_PREFIX . "users WHERE id = '" . (int)$this->session->data['user_id'] . "'");			
			if (count($user_query)) {
				$this->user_id = $user_query[0]['id'];
				$this->user_email = $user_query[0]['user_email'];
				$this->login = $user_query[0]['login'];	
				$this->user_name = $user_query[0]['user_name'];	
				$this->user_group_id = $user_query[0]['user_group_id'];		

      			$this->db->update(DB_PREFIX . "users",array('users_ip' => $this->request->server['REMOTE_ADDR'],'user_last_visit'=>date('Y-m-d H:i:s')),array('id' => (int)$this->session->data['user_id']));
      			$user_group_query = $this->db->select("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query[0]['user_group_id'] . "'");
	  			$permissions = unserialize($user_group_query[0]['permission']);

      			$user_add_groups = $this->db->select("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id IN (SELECT user_group_id FROM " . DB_PREFIX . "user_to_group WHERE  user_id =". (int)(int)$this->session->data['user_id'].")");
				foreach($user_add_groups as $user_group) {
					$group_permission = unserialize($user_group['permission']);
					if (isset($permissions['access'])) $current_permission = $permissions['access']; else $current_permission = array();
					if (isset($group_permission['access'])) $new_permission = $group_permission['access']; else $new_permission = array();
					
					$permissions['access'] = array_unique(array_merge($current_permission ,$new_permission));
					}
				array_unique($permissions);
				
				if (is_array($permissions)) {
	  				foreach ($permissions as $key => $value) {
	    				$this->permission[$key] = $value;
	  				}
				}
			} else {
				$this->logout();
			}
    	}
  	}
		
  	public function login($login, $password) {
    	$user_query = $this->db->select("SELECT * FROM " . DB_PREFIX . "users WHERE LOWER(login) = '" . strtolower($login) . "' AND password = '" . md5($password) . "' LIMIT 1");
    	if (count($user_query)) {
			$this->session->data['user_id'] = $user_query[0]['id'];
			$this->user_id = $user_query[0]['id'];
			$this->user_email = $user_query[0]['user_email'];			
			$this->login = $user_query[0]['login'];		
			$this->user_name = $user_query[0]['user_name'];		
			$this->user_group_id = $user_query[0]['user_group_id'];		
			
			$language_view = array();
			$languages = $this->db->select("SELECT * FROM " . DB_PREFIX . "language WHERE status  = 1"); 
			foreach($languages as $language) {
				$language_view[$language['language_id']] = $user_query[0]['lang_'.$language['language_id']]?$language['language_id']:0;
				if ($language['language_id'] == $user_query[0]['user_language']) $this->session->data['language'] = $language['code'];
			}			

			$this->session->data['language_view'] = $language_view;				
			$user_group_query = $this->db->select("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query[0]['user_group_id'] . "'");
	  		$permissions = unserialize($user_group_query[0]['permission']);

			if (is_array($permissions)) {
				foreach ($permissions as $key => $value) {
					$this->permission[$key] = $value;
				}
			}
      		return TRUE;
    	} else {
      		return FALSE;
    	}
  	}

  	public function logout() {
		unset($this->session->data['user_id']);
	
		$this->user_id = '';
		$this->user_email = '';
		$this->user_name = '';
  	}

  	public function hasPermission($key = 'access' , $route = false) {
		
		if (!$route) $route = $this->request->get['q'];
		
		$part = explode(DIRECTORY_SEPARATOR , $route);
		
		$value = $part[0];
		
		if (!$value) $value = 'index';
    	if (isset($this->permission[$key])) {
	  		return in_array($value, $this->permission[$key]);
		} else {
	  		return FALSE;
		}
  	}
  
  	public function isLogged() {
    	return $this->user_id;
  	}
  
  	public function getId() {
    	return $this->user_id;
  	}
	
  	public function getPermission() {
    	return $this->permission;
  	}
	
  	public function getUserEmail() {
    	return $this->user_email;
  	}	
  	public function getUserName() {
    	return $this->user_name;
  	}	
  	public function getUserGroupId() {
    	return $this->user_group_id;
  	}	
}
?>