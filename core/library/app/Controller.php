<?php 
	defined("_EXE") or die ("CORE VARIABLE IS NOT DEFINED.");
	class Controller extends Palm {
		protected $model = null ;
		private  $layout = "" ;
		private $calledClass= "" ;
		private $cmpName= "";
		private $view = null ;
		public function __construct(){}

		public function calledClass($calledClass=""){
			if($calledClass!=""){
				$this->calledClass = $calledClass;
				$this->cmpName=str_replace("Controller","",$this->calledClass);
				$this->model=Basic::fileExist(APP_COMPONENT.$this->cmpName.DS.$this->cmpName."Model.php")?$this->loadModel($this->cmpName):null;
			}
		}

		public function layout($layout,$buffer=false){
			$this->layout = $layout;
			$view = new View($this->cmpName);
			$view->set($this->getData());
			if($buffer) {
				return $view->layout($layout,$buffer);
			}else{
				$view->layout($layout,$buffer);
			}
		}

	}
