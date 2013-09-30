<?php
class Language_model extends Model {

    function __construct($registry)
    {
        parent::__construct($registry);
    }

	public function getLanguages() {
		$language_data = $this->cache->get('language');
		
		if (!$language_data) {		
			$query = $this->db->select("SELECT * FROM " . DB_PREFIX . "language ORDER BY sort_order");
		
    		foreach ($query as $result) {
      			$language_data[$result['language_id']] = $result;
    		}	
			
			$this->cache->set('language', $language_data);
		} 
		
		return $language_data;	
	}
}
?>