<?php
/**
 * Шаблонизатор
 *
 * Использование:
 * $template = new View;
 * $template->test_var = "Hello, world!";
 * $template->display('index');
 * В шаблоне:
 * <div><?php echo $this->test_var; ?></div>
 */
class View
{
    /**
     * Путь к директории с шаблонами
     */
    private $view_dir;

    /**
     * Переменные
     */
    private $data = array();
    protected $_headers;
    /**
     * Установить директорию с шаблонами
     */
    function __construct($registry,$view_dir = VIEWS)
    {
        $this->view_dir = $view_dir;
		$this->document = $registry->get('document');
        $this->_headers = array();

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
    
    /**
     * Добавить к существующим данным массив данных
     * @param array
     */ 
    public function append_data($new_data)
    {
        if (is_array($new_data)) {
            $this->data = array_merge($this->data, $new_data);
        }
    }

    /**
     * Отобразить шаблон
     */
    public function display($file, $default = false)
    {
        if (empty($file)) {
            $file = $default;
        }
        $path_to_file = $this->view_dir . $file . '.php';
    
        if (isset($file) and file_exists($path_to_file)) {
            include ($path_to_file);
        }
    }
    
    /**
     * Вывести шаблон в переменную и вернуть ее
     */
    public function get_html_display($file, $default = false)
    {
        if (empty($file)) {
            $file = $default;
        }
        $path_to_file = $this->view_dir . $file . '.php';
		
        if (isset($file) and file_exists($path_to_file)) {
			ob_start();
            include ($path_to_file);
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
			
        }
    }	
	
    public function setHeader ($header, $value) {
        $this->_headers[$header] = $value;
    }

    public function sendHeaders() {
        foreach ($this->_headers as $header => $value) {
            header("{$header}: {$value}");
        }
    }
    
    public function clearHeaders() {
        $this->_headers = array();
    }
	
    /**
     * Выводит значение всех переменных шаблона
     * print_r
     */
    public function print_var($variable)
    {
        echo "<pre>";
        print_r($variable);
        echo "</pre>";
    }	
}