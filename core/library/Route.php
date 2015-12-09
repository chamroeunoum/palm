<?php
	defined("_EXE") or die ("CORE VARIABLE IS NOT DEFINED.");
	Import::core("Request");
	class Route extends Palm {
		private $request = null ;
		private $service = null ;
		public function __construct(){}
		public function Route(){}
		/*
		 * start route request
		 */
		public function spread(){
			$this->read();
			if($this->validateRequest()){
				$this->service->set( $this->request->getData() );
				call_user_func(array($this->service,$this->request->getAction()));
			}
		}
		/*
		 * Read request
		 */
		private function read(){
			// Load the request
			$this->request = new Request();
		}

		public function validateRequest(){
			if(Basic::fileExist(APP_SERVICE.Basic::getMVCName($this->request->getService()."Service").".php")){
				$this->service = $this->loadService(Basic::getMVCName($this->request->getService()));
				if(method_exists($this->service,$this->request->getAction()))return true;
				else{
					include(CORE_APP."views/errors/service_not_found.pm");
					die();
				}
			}else{
				include(CORE_APP."views/errors/service_not_install.pm");
				die();
			}
			// echo error page for not found service
		}
	}
