<?php
	defined("_EXE") or die ("CORE VARIABLE IS NOT DEFINED.");
	class PageController extends Controller {
		public function __construct(){
			$this->calledClass(get_class($this));
		}
		public function index(){
			$this->get("variable_from_service");
			$this->set(array());
			$this->layout("default");
		}
		public function main(){ 

		}
	}
