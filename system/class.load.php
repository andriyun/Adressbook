<?php
/**
 * Класс загрузчик.
 * Подгружает нужные модули, библиотеки и т.д.
 * 
 */ 
abstract class Load
{
    protected $path_to_system;
    protected $registry;
    
    function __construct($registry)
    {
        $this->path_to_system = $registry->get('path_to_system')?$registry->get('path_to_system'):ROOT;		
        $this->registry = $registry;
    }
    
    private function load($load)
    {
        $class = '';
        $class = explode(DIRECTORY_SEPARATOR, $load);
        $class = array_pop($class);
        
        if ($this->include_file($load)) {
            $this->$class = new $class($this->registry);
        }
    }
    
    private function include_file($load)
    {
        $file = $this->path_to_system . $load . '.php';
        if (file_exists($file)) {
            include_once($file);
            return true;
        } else {
            return false;
        }
    }
	
    public function path_to_system($path_to_system)
    {
        $this->path_to_system = $path_to_system;
    }		
    
    public function load_model($load)
    {
        $load = 'models'. DIRECTORY_SEPARATOR . $load;
        $this->load($load);
    }
    
    public function redirect($location)
    {
		if ($location) {
			header("Location: ".$location);
			exit;
			}
		return false;
    }
    
    public function load_library($load)
    {
        $load = 'libraries' . DIRECTORY_SEPARATOR . $load;
        $this->load($load);
    }
    
    public function load_helper($load)
    {
        $load = 'helpers' . DIRECTORY_SEPARATOR . $load;
        $this->include_file($load);
    }
    
    public function load_controller($load, $func = 'index', $args = array())
    {	
        $load = 'controllers' . DIRECTORY_SEPARATOR . $load;
        if($this->include_file($load)) {
            $controller = '';
            $controller = explode(DIRECTORY_SEPARATOR, $load);
            $controller = array_pop($controller);
            $controller = new $controller($this->registry);
            /**
             * Если есть метод в классе, то вызываем его,
             * если нет, то на главную
             * Используется для роутера
             * Например, если пользователь неправельно ввел адрес, то его перекидывает на главную
             * @see Init::init()
             */ 

            if (method_exists($controller, $func)) {
                call_user_func_array(array($controller, $func), $args);			
				return true;
				} else {
				return false;
            }
            
        }
        
    }
}
