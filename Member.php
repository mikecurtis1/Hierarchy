<?php 

class Member
{
	private $_path = NULL;
	private $_key = NULL;
	private $_name = NULL;
	private $_uri = NULL;
	
	private function __construct($key, $name, $uri, $path){
		$this->_path = $path;
		$this->_key = $key;
		$this->_name = $name;
		$this->_uri = $uri;
	}
	
	static public function create($key=NULL, $name=NULL, $uri=NULL, $path=NULL){
		if ( is_string($key) && is_string($name) && is_string($uri) && is_string($path) ) {	
			return new Member($key, $name, $uri, $path);
		} else {
			throw new Exception('Error: data validation failed (key, name, uri, and path must be strings, instance of Member not created.');
		}
	}
	
	public function getPath(){
		return $this->_path;
	}
	
	public function getKey(){
		return $this->_key;
	}
	
	public function getName(){
		return $this->_name;
	}
	
	public function getUri(){
		return $this->_uri;
	}
	
	public function __toString(){
		return '<a class="member" href="' . htmlspecialchars($this->_uri) . '">' . htmlspecialchars($this->_name) . '</a><span class="member_key">' . $this->_key . '</span>';
	}
}
?>