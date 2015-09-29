<?php
	/* @date : 4 March 2015
	 * @author : Chamroeun OUM
	 * @description : This file contains class name "Import" which is used as the importer of the framework. To get more details about this class please read the details of each functions
	 */
	defined("_EXE") or die ("CORE VARIABLE IS NOT DEFINED.");
	require_once("Basic.php");
	class Import {
		/* @date : 4 March 2015
		 * @description : This function is used to generate the link of the specific CSS file
		 *
		 */
		public static function css($css){
			if(is_string($css)){
				$css=str_replace(".",DS,$css);
				Basic::eco("\r\n<link type='text/css' rel='stylesheet' href='".(CSS.$css.".css")."' />" );
			}else if(is_array($css)){
				foreach($css as $cs){
					$cs=str_replace(".",DS,$cs);
					Basic::eco("\r\n<link type='text/css' rel='stylesheet' href='".(CSS.$cs.".css")."' />" );
				}
			}
		}
		/* @date : 4 March 2015
		 * @description : This function is used to generate the link of the specific JS file
		 *
		 */
		public static function js($js){
			if(is_string($js)){
				$js=str_replace(".",DS,$js);
				Basic::eco("\r\n<script type='text/javascript' src='".(JS.$js.".js")."' ></script>" );
			}else if(is_array($js)){
				foreach($js as $j){
					$j=str_replace(".",DS,$j);
					Basic::eco("\r\n<script type='text/javascript' src='".(JS.$j.".js")."' ></script>" );
				}
			}
		}
		/* @date : 4 March 2015
		 * @description : This function is used to create link from "web" folder with the user given path
		 * 		eg: web/$path => web/img/.......
		 *
		 */
		public static function link($path=""){
			$extension = substr($path,strrpos($path,".",1)+1,(strlen($path)-strrpos($path,".",1)));
			$path = str_replace(".","/",substr($path,0,strrpos($path,".",1))).".".$extension;
			return WEB.$path;
		}
		/* @date : 4 March 2015
		 *
		 */
		public static function appLibs($file){
			if(is_string($file)){
				Basic::fileExist(APP_LIBS.$file.".php")?BASIC::need(APP_LIBS.$file.".php"):die("Error: Model with name '$file' does not exists.");
			}else if( is_array($file) ){
				foreach($file as $fil )Basic::fileExist(APP_LIBS.$fil.".php")?BASIC::need(APP_LIBS.$fil.".php"):die("Error: Model with name '$fil' does not exists.");
			}
		}
		public static function service($service){
			if(is_string($service)){
				Basic::fileExist(APP_SERVICE.Basic::getMVCName($service).".php")?BASIC::need(APP_SERVICE.Basic::getMVCName($service).".php"):die("Error: Service with name '$service' does not exists.");
			}else if( is_array($service) ){
				foreach($service as $svc )Basic::fileExist(APP_SERVICE.Basic::getMVCName($svc).".php")?BASIC::need(APP_SERVICE.Basic::getMVCName($svc).".php"):die("Error: Service with name '$svc' does not exists.");
			}
		}
		public static function component($component){
			if(is_string($component)){
				Basic::fileExist(APP_COMPONENT.strtolower($component).DS.$component."Controller.php")?BASIC::need(APP_COMPONENT.strtolower($component).DS.$component."Controller.php"):null;
			}else if( is_array($component) ){
				foreach($component as $cntlr )Basic::fileExist(APP_COMPONENT.strtolower($cntlr).DS.$component."Controller.php")?BASIC::need(APP_COMPONENT.strtolower($cntlr).DS.$component."Controller.php"):null;
			}
		}
        public static function componentModel($component){
            if(is_string($component)){
                Basic::fileExist(APP_COMPONENT.strtolower($component).DS.$component."Model.php")?BASIC::need(APP_COMPONENT.strtolower($component).DS.$component."Model.php"):null;
            }else if(is_array($component)){
                foreach($component as $com)Basic::fileExist(APP_COMPONENT.strtolower($com).DS.$component."Model.php")?BASIC::need(APP_COMPONENT.strtolower($com).DS.$component."Model.php"):null;
            }
        }
		public static function config($file){
			if(is_string($file)){
				Basic::fileExist(APP_CONFIG.$file.".php")?BASIC::need(APP_CONFIG.$file.".php"):null;
			}else if( is_array($file) ){
				foreach($file as $fil )Basic::fileExist(APP_CONFIG.$fil.".php")?BASIC::need(APP_CONFIG.$fil.".php"):null;
			}
		}
		public static function core($file){
			if(is_string($file)){
				Basic::fileExist(CORE.$file.".php")?Basic::need(CORE.$file.".php"):null;
			}else if( is_array($file) ){
				foreach($file as $fil )Basic::fileExist(CORE.$fil.".php")?Basic::need(CORE.$fil.".php"):null;
			}
		}
		public static function coreapp($file){
			if(is_string($file)){
				Basic::fileExist(CORE_APP.$file.".php")?Basic::need(CORE_APP.$file.".php"):null;
			}else if( is_array($file) ){
				foreach($file as $fil )Basic::fileExist(CORE_APP.$fil.".php")?Basic::need(CORE_APP.$fil.".php"):null;
			}
		}
		public static function util($file){
			if(is_string($file)){
				Basic::fileExist(CORE_UTIL.$file.".php")?Basic::need(CORE_UTIL.$file.".php"):null;
			}else if( is_array($file) ){
				foreach($file as $fil )Basic::fileExist(CORE_UTIL.$fil.".php")?Basic::need(CORE_UTIL.$fil.".php"):null;
			}
		}
		public static function html($file){
			if(is_string($file)){
				Basic::fileExist(CORE_HTML.$file.".php")?Basic::need(CORE_HTML.$file.".php"):null;
			}else if( is_array($file) ){
				foreach($file as $fil )Basic::fileExist(CORE_HTML.$fil.".php")?Basic::need(CORE_HTML.$fil.".php"):null;
			}
		}
	}
