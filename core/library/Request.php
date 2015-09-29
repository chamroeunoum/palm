<?php
	defined("_EXE") or die ("CORE VARIABLE IS NOT DEFINED.");
	class Request extends Palm {

		private $uri = "" ;
		private $service = "page" ;
		private $action = "index";

		public function __construct(){
			$this->parseURI();
		}

		private function parseURI(){
			$this->uri = $_SERVER["REQUEST_URI"];
			$vars = array();
			$outputs = explode("/",substr($_SERVER["REQUEST_URI"],1) );
			$this->setService(isset($outputs[0])&&$outputs[0]!==""?$outputs[0]:$this->getService());
			$this->setAction(isset($outputs[1])&&$outputs[1]!==""?$outputs[1]:$this->getAction());
			unset($outputs[0]);
			unset($outputs[1]);

			foreach($outputs AS $output ){
				if(strpos($output,":")!==false){
					list($key,$val)=explode(":",$output);
					$vars = array_merge($vars,array($key=>urldecode($val)));
				}
			}
			isset($_POST)&&!empty($_POST)?$vars=array_merge($vars,$_POST):false;
			$this->set($vars);
		}

		public function setURI($uri){
			$this->uri = $uri ;
			$this->parseURI();
		}

		public function getURI(){
			return $this->uri ;
		}

		public function setService($service){
			$this->service = $service ;
		}

		public function setAction($action){
			$this->action = $action ;
		}

		public function getService(){
			return $this->service;
		}

		public function getAction(){
			return $this->action ;
		}

	}
