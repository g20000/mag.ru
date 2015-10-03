<?php

class parseUserInput {

	private $userInput = '';
	
	// erase intags arguments (example: onclick, onmouseover, etc.)
	//$normalised = preg_replace("/<(\/?[^\s>]+)\s([^href|^src|^lang|^class]+[^=]+=)([\"|'][^\"|^']+[\"|'])/i", "<$1", $text);	
	
	// get attr and value
	//preg_match_all("/<[a-z]+\s(?!lang|href|src|class)([^=]+)=([^>]+)>/", $text, $m);
	
	// del disallowed attributes in tags
	function strip_tag_attr($attrs=array('lang','href','src','class')) {
		if (is_array($attrs)) { $allowed_attrs = implode("|", $attrs); } else { $allowed_attrs = $attrs; }
		$normalised = preg_replace_callback("/<[a-z]+(\s(?!".$allowed_attrs.")([^>]+))>/isuU", create_function('$matches', 'return str_replace($matches[1], "", $matches[0]);'), $this->userInput);
		$this->userInput = $normalised;
	}


	// wrap code tags to pre
	function wrap_code_blocks() {

		function codeWrapper($lang, $code) {
			$out = '<pre class="brush: '.$lang.';">'.htmlspecialchars($code).'</pre>';
			return $out;
		}			
		// wrap code section
		$wrapped = preg_replace_callback("/(<code\slang=([^>]+)>)(.*)(<\/code>)/isuU", create_function('$matches','return codeWrapper($matches[2], $matches[3]);'), $this->userInput);
		$this->userInput = $wrapped;
	
	}
	
	// strip tags with content
	function strip_tags_content($tags, $invert = FALSE) {
		preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
		$tagsArr = array_unique($tags[1]);

		if (is_array($tagsArr) AND count($tagsArr) > 0) {
			if ($invert == FALSE) {
				$out = preg_replace('@<(?!(?:' . implode('|', $tagsArr) . ')\b)(\w+)\b.*?>.*?</\1>\n?@si', '', $this->userInput);
			} else {
				$out = preg_replace('@<(' . implode('|', $tagsArr) . ')\b.*?>(.*?</\1>)?\n?@si', '', $this->userInput);
			}
		} elseif ($invert == FALSE) {
			$out = preg_replace('@<(\w+)\b.*?>.*?</\1>\n?@si', '', $this->userInput);
		} else {
			$out = $this->userInput;
		}
		$this->userInput = $out;
	}
	
	// strip javascript in href
	function strip_js_from_attr() {
		$this->userInput = preg_replace("/(<[^<]+href=.?)(javascript:[^\"|^']+)(.*?\/?>)/is", '\1\3', $this->userInput);
	}
	
	
	function anonim_href() {
		$parsed = preg_replace("/(<[^<]+href=.?)(javascript:[^\"|^']+)(.*?\/?>)/is", '\1\3', $this->userInput);
		$this->userInput = $parsed;
	}
	
	
	// main func
	function parse($text) {
		$this->userInput = $text;
		//debug($this->userInput);
		//$this->strip_tag_attr(array('lang','href','src'));
		//debug($this->userInput);
		//$this->wrap_code_blocks();
		//$this->strip_tags_content('<a><strong><img><i><u><pre><body><html>',0);
		//debug($this->userInput);
		$this->strip_js_from_attr();
		//debug($this->userInput);
		
		return $this->userInput;		
	}
	
	
}

?>