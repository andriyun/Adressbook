<?php
final class Language {
  	private $lang_path;
  	private $directory;
	private $data = array();
 
	public function __construct($lang_path,$directory) {
		$this->lang_path = $lang_path;
		$this->directory = $directory;
		$this->load('language');			
	}
	
  	public function get($key) {
   		return (isset($this->data[$key]) ? $this->data[$key] : $key);
  	}
	
	public function load($filename) {
		$_ = array();	
		
		$default = $this->lang_path . 'en_US/' . $filename . '.php';
		
		if (file_exists($default)) {
			require($default);
		}		
		
		$file = $this->lang_path.$this->directory . '/' . $filename . '.php';
		//echo '<pre>';print_r($file);echo '</pre>';
    	if (file_exists($file) && $file != $default) {
			require($file);
		}
	  	
		$this->data = array_merge($this->data, $_);
		
		return $this->data;
  	}

	public function get_language() {
		return $this->data;
  	}
	
	
    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        } else {

            /* Если закомментировать эту строчку, то будем видеть ошибки о несуществующих переменных */
            return '';
        }

        $trace = debug_backtrace();
        trigger_error('Undefined property : <b>' . $name . '</b>' . ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'], E_USER_NOTICE);
        return null;
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function __unset($name)
    {
        unset($this->data[$name]);
    }
    	
}
?>