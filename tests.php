<?php
	
	require "URI_Template_Parser.class.php";
	
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html><head><title>PHP URI Template Parser - Tests</title></head>
<body>
	<div id="main" class="c">
	<ul class="test-results" style="float: right; width: 120px;">
		<li><div class="wrap">
			<div class="input">input</div>
			<div class="output">output</div>
			<div class="expected">expected</div>
		</div></li>
	</ul>
	<h1 style="margin-bottom: 0;color: #000;">PHP URI Template Parser</h1>
	<h2 style="color: #000;">Expansion Tests</h2>
<?
	$std_vars = array(
		'var'   => 'value',
		'hello' => 'Hello World!',
		'undef' => null,
		'empty' => '',
		'list'  => array('val1','val2','val3'),
		'keys'  => array('key1'=>'val1','key2'=>'val2'),
		'path'  => '/foo/bar',
		'x'     => '1024',
		'y'     => '768'
	);
	$std_tests = array(
		"Simple expansion with comma-separated values" => array(
			'{var}'           => "value",
			'{hello}'         => "Hello%20World%21",
			'{path}/here'     => "%2Ffoo%2Fbar/here",
			'{x,y}'           => "1024,768",
			'{var=default}'   => "value",
			'{undef=default}' => "default",
			'{list}'          => "val1,val2,val3",
			'{list*}'         => "val1,val2,val3",
			'{list+}'         => "list.val1,list.val2,list.val3",
			'{keys}'          => "key1,val1,key2,val2",
			'{keys*}'         => "key1,val1,key2,val2",
			'{keys+}'         => "keys.key1,val1,keys.key2,val2",
		)
	);
?>
	<h2>Vars for standard tests</h2>
	<pre><?=print_r($std_vars)?></pre>
<?
	foreach($std_tests as $title => $tests) {
?>
		<h2><?=$title?></h2>
		<ul id="<?=md5($title)?>" class="test-results">
		<? foreach($tests as $uri_template => $expected) {
			$parser = new URI_Template_Parser($uri_template);
			$output = $msg = '';
			try {
				$output = $parser->expand($std_vars);
			} catch(Exception $e) {
				$msg = "Error: ". $e->getMessage();
			}
			$class = $output == $expected ? 'pass' : 'fail';
			$output = $output?$output:$msg;
			renderTest($class, '', $uri_template, $output, $expected);
		} ?>
		</ul>
<?} 

	$template_tests = array(
		'{var}' => array(
			array(
				array('var'=>'value'),
				'value'
			)
		),
		'{hello}' => array(
			array(
				array('hello'=>"hello world!"),
				"hello%20world%21"
			)
		)
	);
	
	foreach($template_tests as $uri_template => $tests) {
	$parser = new URI_Template_Parser($uri_template);
?>
	<h2><?=$uri_template?></h2>
	<ul id="<?=md5($uri_template)?>" class="test-results">
		<? foreach($tests as $n => $io) {
			list($input, $expected) = $io;
			$output = $msg = '';
			try {
				$output = $parser->expand($input);
			} catch(Exception $e) {
				$msg = "Error: ". $e->getMessage();
			}
			$class = $output == $expected ? 'pass' : 'fail';
			$output = $output?$output:$msg;
			$id = md5($uri_template)."-".$n;
			renderTest($class, $id, $input, $output, $expected);
		} ?>
	</ul>
<? } ?>
	</div>
	
<style>
/* reset.css */
html, body, div, span, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, code, del, dfn, em, img, q, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td {margin:0;padding:0;border:0;font-weight:inherit;font-style:inherit;font-size:100%;font-family:inherit;vertical-align:baseline;}body {line-height:1.5;}table {border-collapse:separate;border-spacing:0;}caption, th, td {text-align:left;font-weight:normal;}table, td, th {vertical-align:middle;}blockquote:before, blockquote:after, q:before, q:after {content:"";}blockquote, q {quotes:"" "";}a img {border:none;}
body {font-size:75%;color:#555;background:#fff;font-family:"Helvetica Neue", Arial, Helvetica, sans-serif;}h1, h2, h3, h4, h5, h6 {font-weight:normal;}h1,.fauxh1 {font-size:3em;line-height:1;margin-bottom:0.5em;}h2 {font-size:2em;margin-bottom:0.75em;}h3 {font-size:1.5em;line-height:1;margin-bottom:1em;}h4 {font-size:1.2em;line-height:1.25;margin-bottom:1.25em;}h5 {font-size:1em;font-weight:bold;margin-bottom:1.5em;}h6 {font-size:1em;font-weight:bold;}h1 img, h2 img, h3 img, h4 img, h5 img, h6 img {margin:0;}p {margin:0 0 1.5em;}p img.left {float:left;margin:1.5em 1.5em 1.5em 0;padding:0;}p img.right {float:right;margin:1.5em 0 1.5em 1.5em;}a:focus, a:hover {color:#000;}a {color:#009;text-decoration:underline;}blockquote {margin:1.5em;color:#666;font-style:italic;}strong {font-weight:bold;}em, dfn {font-style:italic;}dfn {font-weight:bold;}sup, sub {line-height:0;}abbr, acronym {border-bottom:1px dotted #666;}address {margin:0 0 1.5em;font-style:italic;}del {color:#666;}pre {margin:1.5em 0;white-space:pre;}pre, code, tt {font:1em 'andale mono', 'lucida console', monospace;line-height:1.5;}li ul, li ol {margin:0 1.5em;}ul, ol {margin:0 1.5em 1.5em 1.5em;}dl {margin:0 0 1.5em 0;}dl dt {font-weight:bold;}dd {margin-left:1.5em;}th {font-weight:bold;}thead th {background:#c3d9ff;}th, td, caption {padding:2px 4px;}tr.even td {background:#e5ecf9;}tfoot {font-style:italic;}caption {background:#eee;}
html, body { height: 100%; border-bottom: 1px solid transparent; }
body { background: #fff url(../i/sprites-x.png) repeat-x center top; }
a { outline: 0; }
a:link, a:visited { color: #00A7E2; text-decoration: none; }
a:hover, a:active {/*  color: #2E5CAA;  */text-decoration: underline; }
a img { border: 0; }
ol, ul { list-style: disc; }
#main { width: 806px; margin: 0 auto; }
.test-resultss { margin-left: 2em; }
.test-results li div.wrap { padding: 0.2em 0.5em 0.5em; border: 2px solid #999;  }
.test-results li { margin: 0.5em 0;  background: #aaa; list-style: none; color: #444; border: 1px solid #555; }
.test-results li.pass { background: #4a4; color: #040; border: 1px solid #050; }
.test-results li.pass div.wrap { border: 2px solid #090;  }
.test-results li.fail { background: #a44; color: #500; border: 1px solid #500; }
.test-results li.fail div.wrap { border: 2px solid #900;  }
.test-results li { font-size: 1.3em; }
.test-results li .input {  }
.test-results li .output { background: #fff; opacity: 0.8; padding: 0.15em 0.4em; margin-top: 0.2em; }
.test-results li .expected { background: #fff; opacity: 0.8; padding: 0.15em 0.4em; margin-top: 5px; }
</style>
</body>
</html>
<? 
	function renderTest($class, $id, $input, $output, $expected) {
?>
		<li class="<?=$class?>" id="<?=$id?>">
			<div class="wrap">
				<div class="input"><?=print_r($input,1)?></div>
				<div class="output"><?=$output?></div>
				<div class="expected"><?=$expected?></div>
			</div>
		</li>
<?
	}