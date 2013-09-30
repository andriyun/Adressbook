<?php
class Contacts extends Controller
{
    function __construct($registry)
    {
        parent::__construct($registry);
		$this->load_library('json');		
		$this->user = $registry->get('user');		
		$this->load_model('contacts_model');		
		if (!$this->user->isLogged()) $this->js_redirect(SITE_URL);
		$contact_groups = $this->contacts_model->getGroups();
		$this->view->contact_groups = $contact_groups;		
    }
	
    function index()
    {
		$this->view->heading_title = 'Welcome!';	
		$this->view->main_view = 'contacts/index';	
	}
    function left_sidebar()
    {	

		$this->view->index = 'contacts/left_sidebar';	
	}
    function _list()
    {	
		
		
		$parameters = array();	
		$fields = array('name' ,'group_id' );	
		
		$data = array();
		
		$url_arr = array();
		
		if (isset($this->request->get['sort_order'])) {
			$data['sort_order'] = $this->request->get['sort_order'];
			$url_arr[] = 'sort_order='.$data['sort_order'];
			}
			else $data['sort_order'] = 'name';
			
		if (isset($this->request->get['sort_dir'])){
			$data['sort_dir']= $this->request->get['sort_dir'];
			$url_arr[] = 'sort_dir='.$data['sort_dir'];
			}
			else {
				$data['sort_dir']= 'desc';
				$url_arr[] = 'sort_dir='.$data['sort_dir'];				
				}
		
		$data['filter']=array();		
		$filter = isset($this->request->post['filter'])?$this->request->post['filter']:(isset($this->request->get['filter'])?$this->request->get['filter']:array());
		if (is_array($filter)) {
			foreach($filter as $key=>$field) {
				if (in_array($key,$fields) && $field) {
					$parameters[$key] = stripslashes(trim($field));
					$data['filter'][$key]=stripslashes(trim($field));
					}
				}
			}
		
		$default_filter = array(
			);
		
		$data_request = $data;
		foreach($default_filter as $key=>$val) if (!isset($data['filter'][$key])) $data_request['filter'][$key] = $val;
		$count = $this->contacts_model->getCount($data_request);	
		
		if ($count) $items = $this->contacts_model->getItems($data_request);
			else  $items = array();	

		foreach($fields as $field){ $data[$field] = $this->contacts_model->getField($field,array());}	
		
		foreach($parameters as $key=>$val) $url_arr[]='filter['.$key.']='.urlencode($val);

		$data['full_page_url'] = $this->document->page_url.($data['sort_order']?'?sort_order='.$data['sort_order'].'&':'?').(count($url_arr)?(implode('&',$url_arr)).'&':'');
		
		$parameters = array();	
		$filter = isset($this->request->post['filter'])?$this->request->post['filter']:(isset($this->request->get['filter'])?$this->request->get['filter']:array());
		
		if (is_array($filter)) {
			foreach($filter as $key=>$field) {
				if (in_array($key,$fields) && $field) {
					$parameters[$key] = stripslashes(trim($field));
					}
				}
			}

		$url_arr = array();			
		
		if ($data['sort_dir'] == 'asc') $url_arr[] = 'sort_dir=desc';
			else $url_arr[] = 'sort_dir=asc';
		
		foreach($parameters as $key=>$val) $url_arr[]='filter['.$key.']='.urlencode($val);
		foreach($fields as $field) $data['sort_'.$field.'_href'] = $this->document->page_url. '?sort_order='.$field.(count($url_arr)?('&'.implode('&',$url_arr)):'');
		
		$this->view->append_data($data);	
		$this->view->default_filter = $default_filter;
		$this->view->items = $items;
		$this->view->timer_show = true;
		$this->view->index = 'contacts/list';
	}

	
	function row($id){	
		$item = $this->contacts_model->getItem($id);	
		$this->view->item = $item;
		$this->view->index = 'contacts/row';
		}	
	
    function edit($id = false)
    {
		$id = intval($id);
						
		if (($this->request->server['REQUEST_METHOD'] == 'POST')){
			

			if (!$this->validateForm()) return;
			
			if (isset($this->request->post['item'])) {
				
				$p=$this->request->post['item'];
				if ($this->contacts_model->updateItem($p, $this->request->post['id'])){				
    				 $this->js_redirect(SITE_URL.'/contacts/view/'.$this->request->post['id'],'#main');	
					} else $this->view->setHeader('error', $this->json->encode('Error save'));
					$this->view->index = 'ajax';	
					return;
				}			
			}
		$this->getForm($id);
	}
	
    function add()
    {
						
		if (($this->request->server['REQUEST_METHOD'] == 'POST')){
			
			//echo '<pre>';print_r($this->request->post);echo '</pre>';exit;

			if (!$this->validateForm()) return;
			
			if (isset($this->request->post['item'])) {
				if ($id = $this->contacts_model->addItem($this->request->post['item'])){				
    				 $this->js_redirect(SITE_URL.'/contacts/view/'.$id,'#main');	
					} else $this->view->setHeader('error', $this->json->encode($this->language->text_error_saving));	
					$this->view->index = 'ajax';	
					return;			
				}
			}
		if ($this->request->get['group_id']) $this->view->group_id = $this->request->get['group_id'];	
		$this->getForm($id);		
	}
	
	
	function view($id){
		$item = $this->contacts_model->getItem($id);	
		
		$contact_groups = $this->contacts_model->getGroups();
		$this->view->contact_groups = $contact_groups;				
		$this->view->item = $item;	
		$this->view->heading_title = 'View contact: '.$item['name'];	
		$this->view->index = 'index/main_block';		
		$this->view->main_view = 'contacts/view';		
		}
		
	function getForm($id = false){
		if (isset($this->request->post['item'])) {
			$item = $this->request->post['item'];
			$item['id'] = $this->request->post['id'];
			} else $item = $this->contacts_model->getItem($id);	
		
		$contact_groups = $this->contacts_model->getGroups();
		$this->view->contact_groups = $contact_groups;
				
		$this->view->item = $item;	
		
		if ($id)  $this->view->heading_title = 'Edit contact: '.$item['name'];
			else $this->view->heading_title = 'Adding new contact';

		$this->view->index = 'index/main_block';		
		$this->view->main_view = 'contacts/edit';		
		}
		
	function delete(){
		if (($this->request->server['REQUEST_METHOD'] == 'POST')&& isset($this->request->post['selected'])){
			if (!$this->validateForm()) return;
			$ids = explode(',',$this->request->post['selected']);
			foreach ($ids as $id)  $this->contacts_model->deleteItem($id);
			$this->view->ajax = 'Contacts deleted';
			} else  $this->view->setHeader('error',$this->json->encode('Error delete'));	
		$this->view->index = 'ajax';	
		}
		
	function validateForm(){
		$errors = array();
		if (isset($this->request->post['item'])){
			if ($this->request->post['item']['name'] == '') $errors[] = 'Name is empty';
			if ($this->request->post['item']['phone'] == '') $errors[] = 'Phone is empty';			
			if ($this->request->post['item']['group_id'] == '') $errors[] = 'Group not selected';			
			}
						
		if (isset($this->request->post['selected'])){
			$ids = explode(',',$this->request->post['selected']);
			if (!count($ids)) $errors[] = 'No selected contacts';
			}
						
		if (isset($this->request->post['action'])){
			if ($this->request->post['action'] == '') $errors[] =  'Action not specified';
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