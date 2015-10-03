<?php

class exchange {
	
	private $currfile = '';
	private $apiKey = '';
	private $apiUrl = 'http://api.exchangeratelab.com/api/current?apikey=';
	private $cacheTTL = 20000;
	public $currs;
			
	function __construct() {
		global $cfg;
		$this->currfile = $cfg['realpath'].'/curr.json';
		if (!file_exists($this->currfile)) {
			touch($this->currfile);
			$this->updateCurrency();
		}
		if ($this->apiUrl=='' || $this->apiKey=='') {
			exit('Currency vars failed!');
		}
		if ($this->checkCache()==false) {
			$this->updateCurrency();
		} else {
			$this->currs = json_decode(file_get_contents($this->currfile));
		}
		
	}
	
	function updateCurrency() {
		$json = file_get_contents($this->apiUrl.$this->apiKey);
		$json = json_decode($json);
		$json = $json->rates;
		$rates = array();
		foreach($json as $v) {
			$rates[$v->to] = $v->rate;
		}
		$this->currs = $rates;
		file_put_contents($this->currfile, json_encode($rates));
	}
	
		
	function checkCache() {
		$utime = filemtime($this->currfile);
		if ($utime<time()-$this->cacheTTL) {
			$out = false;
		} else {
			$out = true;
		}
		return $out;
	}
	
	function getRate($from, $to, $amount){
		
		$from = strtoupper($from);
		$to = strtoupper($to);
		
		$amount = preg_replace("/\s+/", '', $amount);
		$amount = preg_replace("/,/", '.', $amount);
		
		if (!isset($this->currs->$from) || !isset($this->currs->$to)) {
			$out = 'ERROR in exchange class';
		}
		
		return $this->currs->$to/$this->currs->$from*$amount;
		
	}
	
	
}

?>