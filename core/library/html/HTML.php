<?php
	defined("_EXE") or die ("CORE VARIABLE IS NOT DEFINED.");
	class HTML {
		private $html = null ;
		static $instance = null ;
		private function __construct(){
			$this->html = new DOMDocument("1.0","UTF8");
		}
		public static function getInstance(){
			if(HTML::$instance===null) HTML::$instance=new HTML();
			return HTML::$instance;
		}
		public function create($tag="",$attrs=array()){
			if(isset($tag)&&$tag!==""){
				$element = $this->html->createElement($tag);
				if(is_array($attrs)&&!empty($attrs)){
					foreach($attrs as $key => $val ){
						$element->setAttribute($key,$val);
					}
				}
				return $element;
			}
			return null ;
		}
		public static function control($name,$data=array()){
			$element = CORE_HTML."elements".DS.$name.".pm";
			if (Basic::fileExist($element)) {
				extract($data, EXTR_SKIP);
				ob_start();
				include($element);
				ob_get_flush();
			} else {
				echo "ERROR: HTML Element is not defined.<br/>Element : $name ";
			}
		}
		public function toString(){
			return $this->html->html_dump_mem();
		}
	}
