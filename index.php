<?php 
include('config.php');
$session = new Session();
include(SITE.'libraries/class.timer.php');
// Registry
$registry = new Registry();
// Session
$registry->set('session', $session);
$db = new MySql(array('status'=>1));
$registry->set('db', $db);
// Request
$request = new Request();
$registry->set('request', $request); 
// Cache
$cache = new Cache();
$registry->set('cache', $cache); 
$registry->set('path_to_system', SITE);
$user = new User($registry);
// User
$registry->set('user', $user);
// Document
$registry->set('document', new Document($registry));
// Language	
$code = 'en';
$languages = array();
$query = $db->select("SELECT * FROM " . DB_PREFIX . "language WHERE status = 1"); 
foreach ($query as $result) {
	if ($code == $result['code']) {
		$registry->set('default_language', $result['language_id']);
 
		define('DEFAULT_LANGUAGE', $result['code']);	
		}
	$languages[$result['code']] = $result;	
	}
	

if (!isset($session->data['language']) || $session->data['language'] != $code) {
	$session->data['language'] = $code;
	}	

$language = new Language(LANGUAGE,$languages[$code]['directory']);
define('L_ID',$languages[$code]['language_id']);
$registry->set('language', $language);  
$view = new View($registry,VIEWS);
$registry->set('view', $view);
// Front Controller 
$controller = new Front($registry);



$controller->addPreAction(new Action($registry,'common'));

// Router
// Router
if (!isset($request->get['q'])) $request->get['q'] = DEFAULT_ROUTE;
$action = new Action($registry,$request->get['q']);

// Dispatch
$controller->dispatch($action, new Action($registry,ERROR_ROUTE));
$view->append_data($language->get_language());
$timer_show = false;
if (!empty($view->ajax)) $view->index = 'ajax';
if (empty($view->index)) $view->index = 'index'; else {
$timer_show = true;
	}
$view->sendHeaders(); 
$view->display($view->index); 

?>