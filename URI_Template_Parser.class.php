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
			$variable_list = substr($exp, 1, -1);
			$vars = explode(',',$variable_list);
			$defaults = array();
			$modifiers = array();
			foreach($vars as $i => &$var) {
				$m = substr($var,-1);
				if($m == '*' || $m == '+') {
					$var = substr($var, 0,-1);
					$modifier = $m;
				} else {
					$modifier = false;
				}
				if($p = strpos($var, '=')) {
					$default = substr($var, $p+1);
					$var = substr($var, 0,$p);
					$defaults[$var] = $default;
				}
				if($modifier) {
					$modifiers[$var] = $modifier;
				}
			}
			$delimiter = ',';
			foreach($vars as $i => &$var) {
				if(isset($data[$var])) {
					if(is_array($data[$var])) {
						$a = $data[$var];
						if(isset($modifiers[$var]) && $modifiers[$var] == '+') {
							foreach($a as $k => $v) {
								$a[$k] = $var.'.'.$v;
							}
						}
						$val = implode($delimiter, $a);
					} else if(empty($data[$var])) {
						$val = $defaults[$var];
					} else {
						$val = $data[$var];
					}
				} else if(isset($defaults[$var])) {
					$val = $defaults[$var];
				} else {
					throw new Exception('Missing template parameter '.$exp);
					return false;
				}
				$vals[] = str_replace('%2C',',',rawurlencode($val));
			}
			$expansion = str_replace($exp, implode($delimiter, $vals), $expansion);
		}
		
		return $expansion;
	}
	
	public function __toString() {
		return $this->template;
	}
	
}