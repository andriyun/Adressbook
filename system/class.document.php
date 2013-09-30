<?php
final class Document {
	public $title;
	public $description;
	public $keywords;
	public $base;	
	public $page_url;	
	public $page_url_referer;	
	public $charset = 'utf-8';		
	public $language = 'en-gb';	
	public $direction = 'ltr';		
	public $links = array();		
	public $styles = array();
	public $scripts = array();
	public $breadcrumbs = array();
	public $messages = array(
		'info' => array(),
		'error' => array()
		);
	
    function __construct($registry)
    {	
		$request = $registry->get('request');
		$this->page_url = SITE_URL.'/'.isset($request->get['q'])?$request->get['q']:'';
		$this->page_url_referer =  isset($request->server['HTTP_REFERER'])?$request->server['HTTP_REFERER']:false;
    }
	
	public function setTitle($title) {
		$this->title = $title;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function setDescription($description) {
		$this->description = $description;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function setKeywords($keywords) {
		$this->keywords = $keywords;
	}
	
	public function getKeywords() {
		return $this->keywords;
	}
	
	public function setBase($base) {
		$this->base = $base;
	}
	
	public function getBase() {
		return $this->base;
	}		
	
	public function setCharset($charset) {
		$this->charset = $charset;
	}
	
	public function getCharset() {
		return $this->charset;
	}	
	
	public function setLanguage($language) {
		$this->language = $language;
	}
	
	public function getLanguage() {
		return $this->language;
	}	
	
	public function setDirection($direction) {
		$this->direction = $direction;
	}
	
	public function getDirection() {
		return $this->direction;
	}	
	
	public function addLink($href, $rel) {
		$this->links[] = array(
			'href' => $href,
			'rel'  => $rel
		);			
	}
	
	public function getLinks() {
		return $this->links;
	}	
	
	public function addStyle($href, $rel = 'stylesheet', $media = 'screen') {
		$this->styles[] = array(
			'href'  => $href,
			'rel'   => $rel,
			'media' => $media
		);			
	}
	
	public function getStyles() {
		return $this->styles;
	}	
	
	public function addScript($script) {
		$this->scripts[] = $script;			
	}
	
	public function getScripts() {
		return $this->scripts;
	}
	
	public function addBreadcrumb($text, $href, $separator = ' &gt; ') {
		$this->breadcrumbs[] = array(
			'text'      => $text,
			'href'      => $href,
			'separator' => $separator
		);			
	}
	
	public function getBreadcrumbs() {
		return $this->breadcrumbs;
	}	
	
	public function addMessage($text, $status = 'info') {
		switch($status){
			case 'error':
			$this->messages['error'][] = $text;
				break;
			case 'info':
			default:
			$this->messages['info'][] = $text;
			}
					
	}
	
	public function getMessages() {
		return $this->messages;
	}	
}
?>