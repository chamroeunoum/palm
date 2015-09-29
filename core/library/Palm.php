<?php
	defined("_EXE") or die ("CORE VARIABLE IS NOT DEFINED.");
	class Object {}
	class Palm extends Object {
		private $data = array() ;
		public function loadService($service)
		{
			Import::service($service."Service");
			$service = $service."Service" ;
			return new $service();
		}

		public function loadComponent($component)
		{
			Import::component($component);
			$component = $component."Controller" ;
			return new $component();
		}

		public function loadModel($model)
		{
			Import::componentModel($model);
			$model = $model."Model";
			return new $model();
		}

		public function getPath($path=array("page"=>"index"),$vars=array())
		{
			if(is_array($path)&&!empty($path)){
//				if(($_SERVER["QUERY_STRING"]!==null)&&($_SERVER["QUERY_STRING"]!=="")){
//					$link = array();
//					foreach($path as $service =>$action ){
//						$link[]="com=".$service."&action=".$action;
//						foreach($vars as $key =>$value )$link[]=$key."=".$value;
//						$link = implode("&",$link) ;
//						break;
//					}
//				}
//				else{
					foreach($path as $service =>$action ){
						$link = $service."/".$action;
						foreach($vars as $key=>$val)$link.="/".$key.":".$val;
						break;
					}
				//}
				return SERVER.ROOT.$link;
			}
			return SERVER.ROOT;
		}

		public function redirect($path=array("page"=>"index")){
			header("location:".$this->getPath($path));
		}

		public function refresh($path=array("page"=>"index"),$second=3)
		{
			header("refresh:" . $second . ",url=" . $this->getPath($path));
		}

		public function set($key,$val=false){
			if(is_array($key)&&!empty($key))$this->data = array_merge($this->data,$key);
			else if(!is_array($key)&&isset($val)&&$key!="")$this->data= array_merge($this->data,array($key=>$val));
		}

		public function get($key){
			return isset($this->data[$key])?$this->data[$key]:NULL;
		}

		public function getData(){
			return $this->data;
		}

	}
