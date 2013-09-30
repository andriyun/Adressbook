<?php
abstract class Model extends Load
{
    protected $db;
	protected $registry;
	protected $session;
	protected $table;
	protected $additional_field; 
	protected $default_sort;
	protected $default_sort_dir;
	protected $weight_enable;
	protected $field_lenght;
	protected $primary_key = 'id';
	protected $parent_key = 'p_id';
	protected $items = array();
    
    function __construct($registry)
    {
        parent::__construct($registry);
        $this->registry = $registry;
		
        $this->db = $this->registry->get("db");
        $this->session = $this->registry->get("session");
    }
	
	public function __get($key) {
		return $this->registry->get($key);
	}
	
	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}	
	
	public function delete_item($id){		
		return $this->db->delete(DB_PREFIX .$this->table,array($this->primary_key => $id));
		}		
		
	public function add_item($data){		
		return $this->db->insert(DB_PREFIX .$this->table,$data);
		}		
		
	public function move($id, $direction,$additional_id = false) {
		if (!$this->weight_enable) return false;
		$dir = ($direction == 'down') ? true : false;
		$res = $this->get_items($id,$additional_id);
		$weight = $res[0]['weight'];
		$sql = "SELECT ".$this->primary_key.", weight FROM ".DB_PREFIX .$this->table." WHERE";
		if ($additional_id) $sql .=" ".$this->additional_field."=" . $additional_id. " AND";
		$sql .=" weight" . ($dir ? ">" : "<") . $weight;
		$sql .=" ORDER BY weight " . ($dir ? "ASC" : "DESC");
		$res = $this->db->select($sql); 

		if ($res) {
			$newid = $res[0][$this->primary_key];
			$newweight = $res[0]['weight'];
			$this->db->update(DB_PREFIX .$this->table, array('weight' => $weight), array($this->primary_key => $newid));
			$this->db->update(DB_PREFIX .$this->table, array('weight' => $newweight), array($this->primary_key => $id));
			return $id;
		}
		return 'move';
	}

	public function get_items($id = false,$additional_id = false){
		$fields = " * ";
		$from = "FROM " . DB_PREFIX . $this->table;
		$where = "";
		if (!(count($this->items) && $additional_id && $id)) {
			if ($additional_id || $id) $where .= " WHERE";
			if ($additional_id) $where .= " ".$this->additional_field." = ".$additional_id;
			if ($id) {
				if ($additional_id) $where .= " AND ";
				$where .= " ".$this->primary_key."=".$id;
				}	
			if ($this->weight_enable) $order_by .= " ORDER BY weight";
				else if ($this->default_sort) {
					$order_by .= " ORDER BY ".$this->default_sort.' '.($this->default_sort_dir?$this->default_sort_dir:'ASC');
					}
			$limit = "";		
			$sql = "SELECT ".$fields.$from.$where.$limit.$order_by;
			//echo '<pre>';print_r($sql);echo '</pre>';		exit;
			$this->items = $this->db->select($sql);
			}	
		return $this->items;
		}

	public function get_items_id_key($additional_id = false){
		$items = array();
		foreach ($this->get_items(false, $additional_id) as $key=>$val) $items[$val[$this->primary_key]] = $val;
		return $items;
		}	
	
	public function get_max_weight($additional_field = false){
		$sql = "SELECT max(weight) as max_weight FROM " . DB_PREFIX . $this->table;
		if ($additional_field) $sql .= " WHERE ".$this->additional_field." = '".$this->db->escape($additional_field)."'";
		$res = $this->db->select($sql);
		if (isset($res[0]['max_weight'])) $max_weight = $res[0]['max_weight'];
			else $max_weight = 0;
		return $max_weight;
		}

	public function prepare_data($data_arr,$keys = array()){
		if (count($data_arr)) foreach ($data_arr as $key=>$val) {
			$data_arr[$key]= stripslashes($data_arr[$key]);
			if (in_array($key, $keys)) $data_arr[$key] = htmlspecialchars($data_arr[$key],ENT_QUOTES);
			}
		return $data_arr;
		}			
		
	public function get_items_list($additional_id = false) {
		return $this->get_items(false, $additional_id);
		}	

	public function get_item_by_id($id,$additional_id = false) {
		$res = $this->get_items($id,$additional_id);
		return $res[0];
		}

	public function check_overflow_string($data) {	
		foreach ($this->field_lenght as $key=>$val)  if (isset($data[$key])){
			if (is_numeric($data[$key])) $lenght = $data[$key]; 
				else $lenght = mb_strlen(strip_tags(html_entity_decode($data[$key])));	
			if ($lenght > $val) return true;
			}
		return false;	
		}	
	}
	

abstract class Tree_model extends Model
{
	protected $maxlevel;    
    function __construct($registry)
    {
        parent::__construct($registry);
		$this->maxlevel = 2;
        $this->timer = $registry->get('timer');
    }		
		
	public function level(&$array, $level){
		foreach ($array as $key=>$content) {
			$array[$key]['level'] = $level;
			if ($level > $this->maxlevel) {
				unset($array[$key]);
				continue;
				}
			if (isset($array[$key]['subcontent'])) $this->level($array[$key]['subcontent'],$level+1);
			}
		return $array;
		}		
		

	public function first_children($array, $p_id){
		foreach ($array as $key_main=>$val_main) {
			if ($val_main[$this->parent_key] == $p_id) {
				foreach ($array as $key=>$val){
					if ($val[$this->parent_key] == $array[$key_main][$this->primary_key]) {
						return true;
						}
					}
				}
			}	
		return false;			
		}	
		
	public function parents($array, $pid){
		foreach ($array as $key=>$val) if ($val[$this->parent_key] != $pid) return false;
		return true;
		}
	
	public function get_items_id_key($additional_id = false, $full_names = false)
    {
		$items = array();
		foreach ($this->get_items(false, $additional_id) as $key=>$val) $items[$val[$this->primary_key]] = $val;
		if ($full_names) $items = $this->get_full_names($items);
		return $items;
	}	
		
	public function get_full_names($items)
    {
		foreach ($items as $key=>$val) {
			$items[$key]['full_name'] = $val['name'];
			$p_id = $val[$this->parent_key];
			while ($items[$p_id] != 0) {
				$items[$key]['full_name'] = $items[$p_id]['name'].' <span class="tree_separator">&raquo;</span> '.$val['name'];
				$p_id = $items[$p_id][$this->parent_key];
				}
		}
		return $items;
	}	
	public function get_parents($id,$additional_id = false){
		$parents = array();
		$items = $this->get_items_id_key($additional_id);
		$key = $id;
		while($items[$key][$this->parent_key]!= 0){
			$parents[] = $items[$key][$this->parent_key];
			$key = $items[$key][$this->parent_key];
			}
		if (isset($parents[0]))return $parents;
			else return false;
		}		
		
	public function get_level($id,$additional_id = false){
		$level = 1;
		$items = $this->get_items_id_key($additional_id);
		while($items[$id][$this->parent_key] != 0) {
			$level++;
			$id = $items[$id][$this->parent_key];
			}
		return $level;
		}		
		
	public function get_items_tree($additional_id = false,$items = false)
    {
		//$this->timer->repTime('startTree');
		if (!$items) $items = $this->get_items_id_key($additional_id);
		do{
		foreach ($items as $key=>$val) if (!$this->first_children($items,$val[$this->parent_key]) && $val[$this->parent_key] != 0) {
			$items[$val[$this->parent_key]]['subitem'][$key] = $items[$key];			
			unset($items[$key]);
			//$this->timer->repTime($key);
			
			break;
			//echo '<b>step</b><br />';
			//echo '<pre>'; print_r($items);echo '</pre>';
			}
		}
		while (!$this->parents($items, 0));	
		//echo '<pre>';print_r($items);echo '</pre>';
		//$this->timer->repTime('returnTree');
		//echo $i;
		return $items;
    }	
	public function get_items_tree2($parent_id = 0)
    {
		$fields = "*";
		$from = " FROM " . DB_PREFIX . $this->table;
		$where = " WHERE ".$this->parent_key."=".$parent_id;
		if ($this->weight_enable) $order_by .= " ORDER BY weight";
			else if ($this->default_sort) {
				$order_by .= " ORDER BY ".$this->default_sort.' '.($this->default_sort_dir?$this->default_sort_dir:'ASC');
				}
		$limit = "";		
		$sql = "SELECT ".$fields.$from.$where.$limit.$order_by;
		$items = array();
		foreach ($this->db->select($sql) as $key=>$val){
			$items[$val[$this->primary_key]] = $val;
			$subitem = $this->get_items_tree2($val[$this->primary_key]);
			if (count($subitem)) $items[$val[$this->primary_key]]['subitem'] = $subitem;
			}
		return $items;
	}
	
	
	public function get_childrens($items, $id){
		$childrens = array();
		foreach ($items as $val) if ($val[$this->parent_key] == $id) {
			$childrens[] = $val[$this->primary_key];
			if ($first_children = $this->get_childrens($items,$val[$this->primary_key])) $childrens = array_merge($childrens,$first_children);
			}
		if (isset($childrens[0])) return $childrens;
			else return false;
		}	
		
	public function field_exist($field, $value){
		if (count($this->db->select("SELECT * FROM ".DB_PREFIX .$this->table." WHERE ".$field."='".$value."'"))) return true;
			else return false;		
		}	
		
	public function delete_item($id){
		$res = $this->db->select('SELECT '.$this->primary_key.' FROM '.DB_PREFIX .$this->table.' WHERE '.$this->parent_key.' = '.$id);
		if (count($res)) {
			$del_id = $this->get_items($id);
			$id_array = array();
			foreach($res as $val) $id_array[] = $val[$this->primary_key];
			$this->db->query('UPDATE '.DB_PREFIX .$this->table.' SET '.$this->parent_key.' = '.$del_id[0][$this->parent_key].' WHERE '.$this->primary_key.' IN ('.implode(',',$id_array).')');
			}	
		if ($this->db->delete(DB_PREFIX .$this->table,array($this->primary_key => $id))) return $id;	
			else return 'delete';
		}			
}
