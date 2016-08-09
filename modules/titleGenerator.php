<?php

class titleGenerator {
	protected $xml;
	public $replacePlace = array( '#title#', '#manufacturer#', '#category#', '#rutitle#', '#color#' );

	function __construct($template) {
		$file = fopen($template, "r+");
		$buff = fread($file, 10000);
		$this->xml = new SimpleXMLElement($buff);
		$this->xml->templates->count = count($this->xml->templates->template);
	}

	function getTitle($input) {
		/* 0 - title
		 * 1 - manufacturer
		 * 2 - category
		 * 3 - rutitle
		 * 4 - color
		 */


		$string = $this->xml->templates->template[ rand(0, $this->xml->templates->count - 1) ];

		$string = str_replace($this->replacePlace, $input, $string);

		preg_match_all("/#mod:([a-z]+)#/", $string, $mods);

		if (isset($mods[ 1 ])) {
			foreach ($mods[ 1 ] as $index) {
				$mod = (array)$this->xml->mod;
				$mod = $mod[ $index ];
				$i = count($mod) - 1;
				$repl_str = $mod[ rand(0, $i) ];
				$string = str_replace("#mod:" . $index . "#", $repl_str, $string);
			}
		}

		return preg_replace("/[\s]+/", " ", $string);

	}

}