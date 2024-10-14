<?php 

require_once('Collection.php');

class Hierarchy
{
	private $_name = NULL;
	private $_root = NULL;
	private $_system = NULL;
	private $_delimiter = NULL;
	
	private function __construct($name){
		$this->_name = $name;
		$this->_root = 'Root';
		$this->_system = Collection::create($this->_root);
		$this->_delimiter = chr(31);
	}
	
	public static function create($name=NULL){
		if ( is_string($name) && $name !== '' ) {
			return new Hierarchy($name);
		} else {
			throw new Exception('Error: name must be a non-empty string.');
		}
	}
	
	public function addMember($arg=NULL){
		if ( $arg instanceof Member ) {
			$nodes = explode($this->_delimiter, $arg->getPath());
			$this->_system = $this->_addMemberToSystem($arg, $nodes, $this->_system);
		} else {
			throw new Exception('Error: addMember() only accepts an instance of Member.');
		}
	}
	
	private function _addMemberToSystem($member, $nodes=array(), &$sys=NULL){
		if ( count($nodes) > 1 ) {
			$node = array_shift($nodes);
			if ( ! isset($sys->getColl()[$node]) && $sys->getName() !== $node ) {
				$sys->addColl(Collection::create($node),$node);
			}
			$this->_addMemberToSystem($member, $nodes, $sys->getColl()[$node]);
		} elseif ( count($nodes) === 1 ) {
			$node = array_shift($nodes);
			if ( isset($sys->getColl()[$node]) ) {
				$sys->getColl()[$node]->addMember($member, $node);
			} else {
				$sys->addColl(Collection::create($node), $node);
				$sys->getColl()[$node]->addMember($member, $node);
			}
		}
		return $sys;
	}
	
	public function getMemberByKey($key, $a=NULL, &$member=NULL) {
		if ( $a === NULL ) {
			$a = $this->_system->getColl();
		}
		if(is_array($a)) {
			foreach($a as $k => $c){
				$set = $c->getSet();
				if ( ! empty($set) ) {
					foreach ( $c->getSet() as $m ) {
						if ( $m->getKey() === $key ) {
							$member = $m;
						}
					}
				}
				$this->getMemberByKey($key, $c->getColl(), $member);
			}
		}
		return $member;
	}
	
	public function getBranchMembers($path, $a=NULL, &$members=array()) {
		if ( $a === NULL ) {
			$a = $this->_system->getColl();
		}
		if(is_array($a)) {
			foreach($a as $k => $c){
				$set = $c->getSet();
				if ( ! empty($set) ) {
					foreach ( $c->getSet() as $m ) {
						if ( $path === substr($m->getPath(), 0, strlen($path)) ) {
							$members[$m->getKey()] = $m;
						}
					}
				}
				$this->getBranchMembers($path, $c->getColl(), $members); 
			}
		}
		return $members;
	}
	
	public function getMemberUriByKey($key, $a=NULL, &$uri=NULL) {
		if ( $a === NULL ) {
			$a = $this->_system->getColl();
		}
		if(is_array($a)) {
			foreach($a as $k => $c){
				$set = $c->getSet();
				if ( ! empty($set) ) {
					foreach ( $c->getSet() as $m ) {
						if ( $m->getKey() === $key ) {
							$uri = $m->getUri();
						}
					}
				}
				$this->getMemberUriByKey($key, $c->getColl(), $uri);
			}
		}
		return $uri;
	}
	
	public function buildHTMLList($a=NULL, &$html=NULL, $name=NULL, $counter=0) {
		if ( $a === NULL ) {
			$a = $this->_system->getColl();
		}
		$indent = "\t";
		$counter++;
		foreach($a as $k => $c){
			$set = $c->getSet();
			$html .= str_repeat($indent, $counter) . '<li>' . "\n";
			if ( ! empty($set) ) {
				$html .= str_repeat($indent, ($counter)) . '<span class="collection">' . str_replace('_', ' ', $k) . '</span>' . "\n";
				$html .= str_repeat($indent, ($counter)) . '<ul>' . "\n";
				foreach ( $c->getSet() as $m ) {
					$uri = $m->getUri();
					$html .= str_repeat($indent, ($counter+1)) . '<li class="member">' . "\n";
					$html .= str_repeat($indent, ($counter+2)) . '<a href="' . $uri . '">' . $m->getName() . '</a>' . "\n";
					$html .= str_repeat($indent, ($counter+1)) . '</li>' . "\n";
				}
				$this->buildHTMLList($c->getColl(), $html, $c->getName(), $counter);
			} else {
				$html .= str_repeat($indent, ($counter)) . '<span class="COLL:' . $k . '">' . str_replace('_', ' ', $k) . '</span>' . "\n";
				$html .= str_repeat($indent, ($counter)) . '<ul>' . "\n";
				$this->buildHTMLList($c->getColl(), $html, $c->getName(), $counter);
			}
		}
		$counter--;
		if ( $counter > 0 ) {
			$html .= str_repeat($indent, ($counter)) . '</ul>' . "\n";
			$html .= str_repeat($indent, $counter) . '</li>' . "\n";
		}
		return $html;
	}
	
	public function getName(){
		return $this->_name;
	}
	
	public function getSystem(){
		return $this->_system;
	}
	
	public function getColl(){
		return $this->_system->getColl();
	}
}
?>