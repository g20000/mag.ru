<?php

class routerClass {

	public $type = null;
	public $value = null;
	public $param = null;
	public $action = null;
	public $url = null;
	private $paths = array();

	function addPath($arr) {
		$this->paths[] = $arr;	
	}
	
	function __construct($url) {
		$this->url = $url;
	}
	

	function getRoute() {
		$route = explode("/", $this->url);
		if (!isset($route[1]) || empty($route[1])) {
			$this->type = 'page';
			$this->value = 'stats';
		} elseif (preg_match("/^[a-zA-Z0-9_-]+$/i", $route[1])) {
			// если соответствует регулярке то посмотрим что за страница
			$this->type = 'page';
			$this->value = '404';
			foreach($this->paths as $v) {
				if ($v['name']==$route[1]) {
					if ($v['params']!==false && preg_match($v['params'], $route[2])!=false) {
						$this->value = $v['file'];
						$this->param = $route[2];
						if (isset($route[3])) $this->action = $route[3];
					} elseif($v['params']!==false && preg_match($v['params'], $route[2])==false) {
						$this->value = '404';
					} else {
						$this->value = $v['file'];
					}
					
				}
			}
			
			if ($this->value=='') {
				$this->value='stats';
			}
			
		} else {
			// если не то не другое то 404
			$this->type = 'page';
			$this->value = '404';
		}
		
		return array('type'=>$this->type,'value'=>$this->value,'param'=>$this->param);
	}
	
	function isPageExist($page) {
		
		if (!file_exists($page)) {
			echo "<div style='text-align:center;'><h1>404!</h1><h2>Page '".$this->value."' not found!</h2></div>";
			$out = false;
		} else {
			$out = true;
		}
		return $out;
	}


}

?>