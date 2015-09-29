<?php
	defined("_EXE") or die ("CORE VARIABLE IS NOT DEFINED.");
	class Service extends Palm {
		private $template = "" ;
		private $name = "" ;
		private $action = "" ;
		private $view = null ;
		public function __construct($name="",$action=""){
			$this->view = new View();
			$this->name = $name ;
			$this->action = $action ;
		}
		public function template($template=""){
			$this->template = $template;
			$this->view->set($this->getData());
			$this->view->template($template);
		}
		public function block($block="",$vars=array(),$buffer=false){
			if($buffer)return $this->view->block($block,$vars,$buffer);
			else $this->view->block($block,$vars,$buffer);
		}
	}
