<?php

Class URI_Template_Parser {

	public $template;
	
	public function __construct($template) {
		$this->template = $template;
	}
	
	public function match($uri) {
		
	}
	
	public function expand($data) {
		switch(gettype($data)) {
			case "boolean":
			case "integer":
			case "double":
			case "string":
			case "object":
				$data = (array)$data;
			break;
		}
		
		$expansion = $this->template;
		
		preg_match_all('/\{([^\}]*)\}/', $this->template, $expressions);
		
		foreach($expressions[0] as $exp) {
			$vars = explode(',',substr($exp, 1, -1));
			$delimiter = ',';
			foreach($vars as $varname) {
				if(isset($data[$varname])) {
					if(is_array($data[$varname])) {
						$val = implode($delimiter, $data[$varname]);
					} else {
						$val = $data[$varname];
					}
					$vals[] = rawurlencode($val);
				} else {
					throw new Exception('Missing template parameter '.$exp);
					return false;
				}
			}
			$expansion = str_replace($exp, implode($delimiter, $vals), $expansion);
		}
		
		return $expansion;
	}
	
	public function __toString() {
		return $this->template;
	}
	
}