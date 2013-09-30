<?php
class Contacts_model extends Model {

    function __construct($registry)
    {
        parent::__construct($registry);
		$this->language = $registry->get('language');

    }

	public function getItems($data=array(),$nolimit=false) {
		$fields = "c.*";		
		$from = "FROM " . DB_PREFIX . "contacts as c".		
			
		$where = "";
		$filter_where = array();
		$filter_where[] = 'c.user_id = '.$this->user->getId();
		
		$date_where = '';
		
		if (isset($data['filter']) && is_array($data['filter'])) foreach ($data['filter'] as $key=>$filter) {
			$field = '';
			switch ($key){
				case 'group_id':
				case 'name':
					$field = 'c.'.$key;					
					break;
				}
			if (!$field) continue;		
			$filter_where[] = $field." LIKE '%".$this->db->escape($filter)."%'";
			}		
		if (count($filter_where)) $where .= " WHERE ".implode(" AND ",$filter_where);	
						
		
		$order_by = '';
		if ($data['sort_order']) 
			switch ($data['sort_order']){
				case 'name':
					if ($data['sort_dir'] == 'asc') $order_by .= ' ORDER BY  c.name ASC';
						else $order_by .= ' ORDER BY  c.name DESC';
					break;		
				default: 
					$order_by .= ' ORDER BY  c.code DESC';
					break;					
			}		
		if (isset($data['start']) && isset($data['page_limit'])&&!$nolimit)$limit =  ' LIMIT '.$data['start'].','.$data['page_limit'];
			else $limit = '';
		
		$sql = "SELECT  ".$fields." ".$from.$where.$order_by.$limit;
		//echo '<pre>';print_r($sql);echo '</pre>';
		$res = $this->db->select($sql);
		//echo "<pre>";print_r($res);echo"</pre>";		
		return $res;
	}	
	
	public function getCount($data) {			
		$from = "FROM " . DB_PREFIX . "contacts as c ";
		$where = "";
		$filter_where = array();
		$filter_where[] = 'c.user_id = '.$this->user->getId();		
		foreach ($data['filter'] as $key=>$filter) {
			$field = '';
			switch ($key){
				case 'group_id':
				case 'name':
					$field = 'c.'.$key;					
					break;
				}
			if (!$field) continue;		
			$filter_where[] = $field." LIKE '%".$this->db->escape($filter)."%'";
			}		
		if (count($filter_where)) $where .= " WHERE ".implode(" AND ",$filter_where);
		$sql = "SELECT COUNT(*) as count ".$from.$where;
		//echo '<pre>';print_r($sql);echo '</pre>';
		$res = $this->db->select($sql);			
		return $res[0]['count'];		
		}
	public function getItem($item_id) {
		 
		$sql = "SELECT c.*, (SELECT name FROM ". DB_PREFIX . "contacts_group WHERE id = c.group_id) as group_name ".
			"FROM " . DB_PREFIX . "contacts as c ".	
			"WHERE c.user_id = ".$this->user->getId()." AND c.id = '" . $this->db->escape($item_id) . "' LIMIT 1";
		$res = $this->db->select($sql);			
		if (!count($res)) return false;
			return $res[0];
	}	
	public function getGroups() {
		$sql = "SELECT cg.*".
			"FROM " . DB_PREFIX . "contacts_group as cg ";
		$res = $this->db->select($sql);			
		if (!count($res)) return array();
			return $res;
	}	
	public function getItemByField($field,$value) {
		
		$sql = "SELECT c.*".
			"FROM " . DB_PREFIX . "contacts as c ".

			"WHERE  c.user_id = ".$this->user->getId()." AND c.".$field." = '" .$this->db->escape($value) . "' LIMIT 1";
		$res = $this->db->select($sql);			
		if (!count($res)) return false;
			return $res[0];
	}	
	
	public function getItemsByField($field,$value) {
		
		$sql = "SELECT c.*".
			"FROM " . DB_PREFIX . "contacts as c ".

			"WHERE  c.user_id = ".$this->user->getId()." AND c.".$field." = '" .$this->db->escape($value) . "'";
		$res = $this->db->select($sql);
		if (!count($res)) return false;
			return $res;
	}	
	

	public function getField($arg,$where = array()) {
	//, 'login' ,'profile_email'':
		switch ($arg){
			case 'name':
				$field = 'c.name';
				break;
			default:
				$field = $arg;				
				break;
			}
			
		$sql = "SELECT ".$field." FROM " . DB_PREFIX . "contacts as c";
		if (count($where)){
			$where_arr = array();
			foreach($where as $field=>$value) $where_arr[] =  $field." = '" .$this->db->escape($value) . "'";
			$sql.= " WHERE  c.user_id = ".$this->user->getId()." AND ".implode(" AND ",$where_arr);
			}		
		//echo '<pre>';print_r($sql);echo '</pre>';	
		$res = $this->db->select($sql);			
		if (!count($res)) return false;
			return $res;
	}	
	public function addItem($data) {
		$data['user_id'] = $this->user->getId();
		return $this->db->insert(DB_PREFIX .'contacts',$data);
	}	

	public function updateItem($data, $id) {
		return $this->db->update(DB_PREFIX .'contacts',$data, array('id'=>$id,'user_id'=>$this->user->getId()));
	}
		
	public function deleteItem($id) {
		return $this->db->delete(DB_PREFIX .'contacts', array('id' => $id,'user_id'=>$this->user->getId()));
	}		
}
?>
